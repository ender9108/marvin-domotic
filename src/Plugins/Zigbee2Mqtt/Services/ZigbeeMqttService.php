<?php
namespace App\Plugins\Zigbee2Mqtt\Services;

use App\Entity\Module;
use App\Entity\ModuleCommand;
use App\Entity\Protocol;
use App\Entity\Vendor;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

class ZigbeeMqttService
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator
    ) {}

    public function onMessage(string $topic, string $message, Protocol $zigbee, array $options = []): ?array
    {
        if (true === $options['enable'] && method_exists($this, $options['method'])) {
            dump('Message on '.$topic);

            try {
                return call_user_func_array([$this, $options['method']], [
                    $topic,
                    $options['decodeValue'] ? json_decode($message, true) : $message,
                    $zigbee
                ]);
            } catch (Exception $e) {
                return [
                    'status' => 'nok',
                    'message' => 'File '.$e->getFile().' on line '.$e->getLine().' - '.$e->getMessage(),
                ];
            }
        }

        return null;
    }

    protected function parseBridgeInfo(string $topic, array $infos, Protocol $zigbee): array
    {
        $config = $zigbee->getConfiguration();
        $config['zigbee2mqttConfig'] = $infos;

        $zigbee
            ->setState('online')
            ->setAddingModuleAllowed($infos['permit_join'])
            ->setConfiguration($config);

        $this->em->persist($zigbee);
        $this->em->flush();

        return [
            'status' => 'ok',
            'update_interface' => [
                'type' => 'protocol',
                'data' => [
                    'zigbee-2-mqtt_state' => $zigbee->getState(),
                    'zigbee-2-mqtt_permit-join' => $zigbee->getAddingModuleAllowed()
                ],
            ],
        ];
    }

    protected function parseBridgeState(string $topic, string $state, Protocol $zigbee): ?array
    {
        if ($zigbee->getState() !== $state) {
            $zigbee->setState($state);

            $this->em->persist($zigbee);
            $this->em->flush();

            return [
                'status' => 'ok',
                'update_interface' => [
                    'zigbee-2-mqtt-state' => $zigbee->getState(),
                ],
                'alert' => [
                    'type' => 'info',
                    'message' => $this->translator->trans(
                        strtolower($state) === 'online' ? 'protocols.controller.online' : 'protocols.controller.offline'
                    )
                ],
            ];
        }

        return null;
    }

    protected function parseModulesList(string $topic, array $modules, Protocol $zigbee): ?array
    {
        $flush = false;
        $sendResponse = false;
        $response = [
            'status' => 'ok'
        ];

        foreach ($modules as $key => $module) {
            if (!in_array(strtolower($module['type']), ['coordinator'])) {
                $vendor = null;
                $newModule = false;
                $marvinModule = $this->em->getRepository(Module::class)->findOneBy([
                    'uniqueIdentifier' => $module['ieee_address']
                ]);

                if (is_null($marvinModule)) {
                    $marvinModule = new Module();
                    $newModule = true;
                }

                if (isset($module['manufacturer'])) {
                    $vendor = $this->em->getRepository(Vendor::class)->findOneBy([
                        'name' => $module['definition']['vendor']
                    ]);

                    if (is_null($vendor)) {
                        $vendor = new Vendor();
                        $vendor
                            ->setName($module['definition']['vendor'])
                        ;
                    }
                }

                $marvinModule
                    ->setUniqueIdentifier($module['ieee_address'])
                    ->setName(true === $newModule ? $module['friendly_name'] : $marvinModule->getName())
                    ->setDescription(true === $newModule ? $module['definition']['description'] : $marvinModule->getDescription())
                    ->setProtocol($zigbee)
                    ->setVendor($vendor)
                    ->setParameters([
                        'friendly_name' => $module['friendly_name'] ?? null,
                        'ieee_address' => $module['ieee_address'] ?? null,
                        'interview_completed' => $module['interview_completed'] ?? null,
                        'interviewing' => $module['interviewing'] ?? null,
                        'manufacturer' => $module['manufacturer'] ?? null,
                        'model_id' => $module['model_id'] ?? null,
                        'network_address' => $module['network_address'] ?? null,
                        'power_source' => $module['power_source'] ?? null,
                        'software_build_id' => $module['software_build_id'] ?? null,
                        'supported' => $module['supported'] ?? null,
                        'type' => $module['type'] ?? null,
                    ])
                ;

                if (isset($module['definition']['exposes'])) {
                    foreach ($module['definition']['exposes'] as $command) {
                        if (isset($command['features'])) {
                            foreach ($command['features'] as $subCommand) {
                                $moduleCommand = $this->createModuleCommande($subCommand, $marvinModule);
                                $marvinModule->addModuleCommand($moduleCommand);
                            }
                        } else {
                            $moduleCommand = $this->createModuleCommande($command, $marvinModule);
                            $marvinModule->addModuleCommand($moduleCommand);
                        }
                    }
                }

                $this->em->persist($marvinModule);
                $flush = true;

                if (true === $newModule) {
                    $response['addSubscriber'][] = [
                        'topic' => $zigbee->getConfiguration()['zigbee2mqttConfig']['config']['mqtt']['base_topic'].'/'.$module['friendly_name'],
                        'module' => $marvinModule
                    ];

                    $response['alert'][] = [
                        'type' => 'info',
                        'message' => $this->translator->trans('zigbee2mqtt.new_device_added', [
                            '%module%' => $marvinModule->getName()
                        ], 'zigbee2mqtt.messages')
                    ];

                    $sendResponse = true;
                }
            } // endif !in_array(strtolower($module['type']), ['coordinator'])
        } // endforeach $modules

        if (true === $flush) {
            $this->em->flush();
        }

        return true === $sendResponse ? $response : null;
    }

    protected function parseModuleRename(string $topic, array $data, Protocol $zigbee): ?array
    {
        /** @var Module $module */
        $module = $this->em->getRepository(Module::class)->findByFirendlyName($data['data']['to']);
        $baseTopic = $zigbee->getConfiguration()['zigbee2mqttConfig']['config']['mqtt']['base_topic'];

        return [
            'status' => 'ok',
            'removeSubsciber' => [$baseTopic.'/'.$data['data']['from']],
            'addSubscriber' => [
                [
                    'topic' => $baseTopic.'/'.$data['data']['to'],
                    'module' => $module
                ]
            ],
            'alert' => [
                'type' => 'info',
                'message' => $this->translator->trans('zigbee2mqtt.friendly_name_update', [
                    '%module' => $module->getName(),
                    '%from%' => $data['data']['from'],
                    '%to%' => $data['data']['to'],
                ], 'zigbee2mqtt.messages')
            ]
        ];
    }

    protected function parseEvents(string $topic, array $event, Protocol $zigbee)
    {
        switch($event['type']) {
            case 'device_joined':
                $module = $this->em->getRepository(Module::class)->findByFirendlyName($event['data']['friendly_name']);
                $module->setState(Module::STATE_ONLINE);

                $this->em->persist($module);
                $this->em->flush();
                break;
            case 'device_leave':
                $module = $this->em->getRepository(Module::class)->findByFirendlyName($event['data']['friendly_name']);
                $module->setState(Module::STATE_LEAVE);

                $this->em->persist($module);
                $this->em->flush();
                break;
        }

        return null;
    }

    protected function parseDeviceEvent(string $topic, array $deviceEvent, Protocol $zigbee): ?array
    {
        $response = [];

        $topicParts = explode('/', $topic);
        $friendlyName = end($topicParts);

        /** @var Module $module */
        $module = $this->em->getRepository(Module::class)->findByFirendlyName(
            $friendlyName
        );

        if (is_null($module)) {
            $response['status'] = 'error';
            $response['message'][] = $this->translator->trans(
                'zigbee2mqtt.error.device_not_found', [
                    '%module%' => $friendlyName
                ]
            );

            return $response;
        }

        $module->setData($deviceEvent);
        $this->em->persist($module);
        $this->em->flush();

        $response['message'][] = $this->translator->trans(
            'zigbee2mqtt.events.module_updated', [
                '%module%' => $friendlyName
            ]
        );

        return null;
    }

    private function createModuleCommande(array $command, Module $module): ModuleCommand
    {
        $moduleCommand = $this->em->getRepository(ModuleCommand::class)->findOneBy([
            'property' => $command['property'],
            'module' => $module
        ]);

        if (is_null($moduleCommand)) {
            $moduleCommand = new ModuleCommand();
        }

        $moduleCommand
            ->setName($command['name'])
            ->setProperty($command['property'])
            ->setDescription($command['description'])
            ->setAccess($command['access'])
            ->setParameters($command)
        ;

        return $moduleCommand;
    }
}