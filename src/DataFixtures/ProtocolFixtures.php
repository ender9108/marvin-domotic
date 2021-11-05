<?php

namespace App\DataFixtures;

use App\Entity\Protocol;
use App\Plugins\Zigbee2Mqtt\Services\ZigbeeMqttService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class ProtocolFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $protocol = new Protocol();
        $protocol
            ->setName('Zigbee2mqtt')
            ->setPath('zigbee2mqtt-616f3e04d6a9e258435194.png')
            ->setTag('zigbee-2-mqtt')
            ->setActionEditPath('protocol.zigbee2mqtt.edit')
            ->setActionDeletePath('protocol.zigbee2mqtt.delete')
            ->setService(ZigbeeMqttService::class)
            ->setConfiguration([
                'zigbee2mqttConfig' => [],
                'zigbee2mqttTopics' => [
                    'subscribe' => [
                        '${base_topic}/bridge/info' => [
                            'enable' => true,
                            'type' => 'bridge',
                            'method' => 'parseBridgeInfo',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/state' => [
                            'enable' => true,
                            'type' => 'bridge',
                            'method' => 'parseBridgeState',
                            'decodeValue' => false,
                        ],
                        '${base_topic}/bridge/devices' => [
                            'enable' => true,
                            'type' => 'bridge',
                            'method' => 'parseModulesList',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/event' => [
                            'enable' => true,
                            'type' => 'bridge',
                            'method' => 'parseEvents',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/${device_friendly_name}' => [
                            'enable' => true,
                            'type' => 'device',
                            'method' => 'parseDeviceEvent',
                            'decodeValue' => true,
                        ],
                        /*'${base_topic}/bridge/logging' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/groups' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/permit_join' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/health_check' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/restart' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/networkmap' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/device/remove' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/device/ota_update/check' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/device/ota_update/update' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/device/configure' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/device/options' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/device/rename' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/group/remove' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/group/add' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/group/rename' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/group/options' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/group/members/add' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/group/members/remove' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/group/members/remove_all' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],
                        '${base_topic}/bridge/response/options' => [
                            'enable' => false,
                            'type' => 'bridge',
                            'decodeValue' => true,
                        ],*/
                    ],
                    'publish' => [
                        'permitJoin' => [
                            'topic' => '${base_topic}/bridge/request/permit_join',
                            'enable' => true,
                            'payload' => [
                                'value' => 'boolean',
                            ],
                        ],
                        'healthCheck' => [
                            'topic' => '${base_topic}/bridge/request/health_check',
                            'enable' => true,
                            'payload' => null,
                        ],
                        'restart' => [
                            'topic' => '${base_topic}/bridge/request/restart',
                            'enable' => true,
                            'payload' => null,
                        ],
                        'networkMap' => [
                            'topic' => '${base_topic}/bridge/request/networkmap',
                            'enable' => true,
                            'payload' => [
                                'type' => 'string(raw, graphviz, plantuml)',
                                'routes' => 'boolean'
                            ],
                        ],
                        'removeDevice' => [
                            'topic' => '${base_topic}/bridge/request/device/remove',
                            'enable' => true,
                            'payload' => [
                                'id' => 'string',
                            ],
                        ],
                        'checkOtaUpdate' => [
                            'topic' => '${base_topic}/bridge/request/device/ota_update/check',
                            'enable' => true,
                            'payload' => [
                                'id' => 'string',
                            ],
                        ],
                        'applyOtaUpdate' => [
                            'topic' => '${base_topic}/bridge/request/device/ota_update/update',
                            'enable' => true,
                            'payload' => [
                                'id' => 'string',
                            ],
                        ],
                        'configureDevice' => [
                            'topic' => '${base_topic}/bridge/request/device/configure',
                            'enable' => true,
                            'payload' => [
                                'id' => 'string',
                            ],
                        ],
                        'optionDevice' => [
                            'topic' => '${base_topic}/bridge/request/device/options',
                            'enable' => true,
                            'payload' => [
                                'id' => 'string',
                                'options' => 'array'
                            ],
                        ],
                        'renameDevice' => [
                            'topic' => '${base_topic}/bridge/request/device/rename',
                            'enable' => true,
                            'payload' => [
                                'from' => 'string',
                                'to' => 'string'
                            ],
                        ],
                        'removeGroup' => [
                            'topic' => '${base_topic}/bridge/request/group/remove',
                            'enable' => true,
                            'payload' => [
                                'id' => 'string',
                            ],
                        ],
                        'addGroup' => [
                            'topic' => '${base_topic}/bridge/request/group/add',
                            'enable' => true,
                            'payload' => [
                                'friendly_name' => 'string',
                                'id' => 'integer',
                            ],
                        ],
                        'renameGroup' => [
                            'topic' => '${base_topic}/bridge/request/group/rename',
                            'enable' => true,
                            'payload' => [
                                'from' => 'string',
                                'to' => 'string'
                            ],
                        ],
                        'optionGroup' => [
                            'topic' => '${base_topic}/bridge/request/group/options',
                            'enable' => true,
                            'payload' => [
                                'id' => 'string',
                                'options' => 'array'
                            ],
                        ],
                        'addDeviceToGroup' => [
                            'topic' => '${base_topic}/bridge/request/group/members/add',
                            'enable' => true,
                            'payload' => [
                                'group' => 'string',
                                'device' => 'string'
                            ],
                        ],
                        'removeDeviceToGroup' => [
                            'topic' => '${base_topic}/bridge/request/group/members/remove',
                            'enable' => true,
                            'payload' => [
                                'group' => 'string',
                                'device' => 'string'
                            ],
                        ],
                        'removeAllDeviceToGroup' => [
                            'topic' => '${base_topic}/bridge/request/group/members/remove_all',
                            'enable' => true,
                            'payload' => [
                                'group' => 'string',
                                'device' => 'string'
                            ],
                        ],
                        'options' => [
                            'topic' => '${base_topic}/bridge/request/options',
                            'enable' => true,
                            'payload' => [
                                'options' => 'array'
                            ],
                        ],
                    ],
                ],
            ]);

        $manager->persist($protocol);

        /*$protocol = new Protocol();
        $protocol
            ->setName('Marvin2mqtt')
            ->setPath('marvin2mqtt-616f3d0ea6b88439739889.png')
            ->setTag('marvin-2-mqtt')
            ->setService('')
            ->setConfiguration([
                'marvin2mqtt_config' => [],
            ])
        ;
        $manager->persist($protocol);*/

        $manager->flush();
    }
}
