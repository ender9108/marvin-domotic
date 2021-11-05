<?php

namespace App\DataFixtures;

use App\Entity\Parameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParameterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /* *************** MQTT *************** */
        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('mqtt')
            ->setTitle('parameters.mqtt.host')
            ->setValuetype('string')
            ->setValue('localhost')
            ->setParamOrder(10)
            ->setTag('communication.mqtt.host')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('mqtt')
            ->setTitle('parameters.mqtt.port')
            ->setValuetype('integer')
            ->setValue('1883')
            ->setParamOrder(20)
            ->setTag('communication.mqtt.port')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('mqtt')
            ->setTitle('parameters.mqtt.username')
            ->setValuetype('string')
            ->setValue('ender')
            ->setParamOrder(30)
            ->setTag('communication.mqtt.username')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('mqtt')
            ->setTitle('parameters.mqtt.password')
            ->setValuetype('string')
            ->setValue('n0c3d2l8-A')
            ->setParamOrder(40)
            ->setTag('communication.mqtt.password')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('mqtt')
            ->setTitle('parameters.mqtt.client_id')
            ->setValuetype('string')
            ->setValue('marvin-mqtt')
            ->setParamOrder(50)
            ->setTag('communication.mqtt.client_id')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('mqtt')
            ->setTitle('parameters.mqtt.ca')
            ->setValuetype('string')
            ->setValue('')
            ->setParamOrder(60)
            ->setTag('communication.mqtt.ca')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('mqtt')
            ->setTitle('parameters.mqtt.key')
            ->setValuetype('string')
            ->setValue('')
            ->setParamOrder(70)
            ->setTag('communication.mqtt.key')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('mqtt')
            ->setTitle('parameters.mqtt.cert')
            ->setValuetype('string')
            ->setValue('')
            ->setParamOrder(80)
            ->setTag('communication.mqtt.cert')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        /* *************** WEBSOCKET *************** */
        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('websocket')
            ->setTitle('parameters.websocket.host')
            ->setValuetype('string')
            ->setValue('')
            ->setParamOrder(10)
            ->setTag('communication.websocket.host')
            ->setRestartRequired(true)
            ->setIsAccessibleToTemplate(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('websocket')
            ->setTitle('parameters.websocket.port')
            ->setValuetype('integer')
            ->setValue(null)
            ->setParamOrder(10)
            ->setTag('communication.websocket.port')
            ->setRestartRequired(true)
            ->setIsAccessibleToTemplate(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('websocket')
            ->setTitle('parameters.websocket.ssl')
            ->setValuetype('binary')
            ->setValue(false)
            ->setParamOrder(10)
            ->setTag('communication.websocket.ssl')
            ->setRestartRequired(true)
            ->setIsAccessibleToTemplate(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('wifi')
            ->setTitle('parameters.wifi.ssid')
            ->setValuetype('string')
            ->setValue('')
            ->setParamOrder(10)
            ->setTag('communication.wifi.ssid')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        $parameter = new Parameter();
        $parameter
            ->setType('communication')
            ->setSubtype('wifi')
            ->setTitle('parameters.wifi.password')
            ->setValuetype('string')
            ->setValue('')
            ->setParamOrder(10)
            ->setTag('communication.wifi.password')
            ->setRestartRequired(true)
        ;
        $manager->persist($parameter);

        $manager->flush();
    }
}
