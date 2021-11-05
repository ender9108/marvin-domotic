<?php

namespace App\Plugins\Zigbee2Mqtt\Command;

use App\Entity\Module;
use App\Entity\Protocol;
use App\Plugins\Zigbee2Mqtt\Services\ZigbeeMqttService;
use Doctrine\ORM\EntityManagerInterface;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\MqttClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use WebSocket\Client;

#[AsCommand(
    name: 'app:zigbee2mqtt:command',
    description: 'Subscribe to zigbee2mqtt topics',
)]
class Zigbee2MqttCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private ParameterBagInterface $parameterBag,
        private TranslatorInterface $translator,
        string $name = null
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addOption(
            'time-limit',
            null,
            InputOption::VALUE_OPTIONAL,
            'Execution time limit',
            3600
        );
    }

    /**
     * @throws ConnectingToBrokerFailedException
     * @throws MqttClientException
     * @throws RepositoryException
     * @throws ConfigurationInvalidException
     * @throws ProtocolViolationException
     * @throws ProtocolNotSupportedException
     * @throws DataTransferException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $timeout = (int)$input->getOption('time-limit');
        $zigbee = $this->em->getRepository(Protocol::class)->findOneBy([
            'tag' => 'zigbee-2-mqtt'
        ]);
        $zigbeeMqttService = new ZigbeeMqttService(
            $this->em,
            $this->translator
        );
        $mqtt = new MqttClient(
            $this->parameterBag->get('communication.mqtt.host'),
            $this->parameterBag->get('communication.mqtt.port'),
            $this->parameterBag->get('communication.mqtt.client_id')
        );
        /** @var Module[] $modules */
        $modules = $this->em->getRepository(Module::class)->findBy([
            'protocol' => $zigbee
        ]);
        $websocketClient = new Client("ws://localhost:9001");

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($this->parameterBag->get('communication.mqtt.username'))
            ->setPassword($this->parameterBag->get('communication.mqtt.password'));

        $mqtt->connect($connectionSettings, true);

        if (
            isset($zigbee->getConfiguration()['zigbee2mqttTopics']) &&
            isset($zigbee->getConfiguration()['zigbee2mqttTopics']['subscribe']) &&
            count($zigbee->getConfiguration()['zigbee2mqttTopics']['subscribe']) > 0
        ) {
            foreach ($zigbee->getConfiguration()['zigbee2mqttTopics']['subscribe'] as $zigbeeTopic => $options) {
                if (true === $options['enable']) {
                    if ('bridge' === $options['type']) {
                        $this->subscribe(
                            $mqtt,
                            $zigbeeMqttService,
                            $websocketClient,
                            $zigbeeTopic,
                            $options,
                            $zigbee
                        );
                    }

                    if ('device' === $options['type']) {
                        foreach ($modules as $module) {
                            $this->subscribe(
                                $mqtt,
                                $zigbeeMqttService,
                                $websocketClient,
                                $zigbeeTopic,
                                $options,
                                $zigbee,
                                $module
                            );
                        }
                    }
                }
            }
        }

        $mqtt->registerLoopEventHandler(function(
            MqttClient $mqtt,
            float $elapsedTime
        ) use ($timeout) {
            if ($timeout !== 0 && $elapsedTime > $timeout) {
                $mqtt->interrupt();
            }
        });

        $mqtt->loop(true);
        $mqtt->disconnect();
        $websocketClient->close();

        $io->success('Stop Zigbee2MqttCommand !');

        return Command::SUCCESS;
    }

    /**
     * @throws RepositoryException
     * @throws DataTransferException
     */
    private function subscribe(
        MqttClient $mqtt,
        ZigbeeMqttService $zigbeeMqttService,
        Client $websocketClient,
        string $topic,
        array $options,
        Protocol $zigbee,
        ?Module $module = null
    ): void
    {
        $baseTopic = isset($zigbee->getConfiguration()['zigbee2mqttConfig']['config']) ?
            $zigbee->getConfiguration()['zigbee2mqttConfig']['config']['mqtt']['base_topic'] :
            'zigbee2mqtt'
        ;
        $translator = $this->translator;

        dump('Subscribe to '.strtr($topic, [
            '${base_topic}' => $baseTopic,
            '${device_friendly_name}' => $module?->getParameters()['friendly_name']
        ]));

        $mqtt->subscribe(strtr($topic, [
            '${base_topic}' => $baseTopic,
            '${device_friendly_name}' => $module?->getParameters()['friendly_name']
        ]), function ($topic, $message) use ($mqtt, $zigbeeMqttService, $options, $websocketClient, $zigbee, $translator) {
            $response = $zigbeeMqttService->onMessage(
                $topic,
                $message,
                $zigbee,
                $options
            );

            if (!is_null($response)) {
                if ($response['status'] === 'ok') {
                    if (isset($response['removeSubsciber']) && count($response['removeSubsciber']) > 0) {
                        foreach ($response['removeSubsciber'] as $topic) {
                            $mqtt->unsubscribe($topic);
                        }

                        unset($response['removeSubsciber']);
                        dump('Unsubscribe '.$topic);
                    }

                    if (isset($response['addSubscriber']) && count($response['addSubscriber']) > 0) {
                        foreach ($response['addSubscriber'] as $subscriber) {
                            $this->subscribe(
                                $mqtt,
                                $zigbeeMqttService,
                                $websocketClient,
                                $subscriber['topic'],
                                $options,
                                $zigbee,
                                $subscriber['module'] ?? null
                            );
                        }

                        unset($response['addSubscriber']);
                    }

                    $websocketClient->send(json_encode($response));
                }

                if ($response['status'] === 'nok') {
                    $message = $response['message'] ?? $translator->trans('zigbee2mqtt.error.unknown_error');

                    $websocketClient->send(json_encode([
                        'alert' => [
                            'type' => 'error',
                            'message' => $message,
                        ],
                    ]));
                }
            }
        });
    }
}
