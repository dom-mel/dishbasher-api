<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Model\DeviceState;
use AppBundle\Model\Time;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->loadFixtures([
            'AppBundle\\DataFixtures\\ORM\\LoadDevices'
        ]);
    }


    /**
     * @dataProvider getDeviceDataProvider
     */
    public function testGetDevice(string $device, int $responseCode, array $response = null)
    {
        $client = static::createClient();

        $client->request('GET', '/' . $device);

        $this->assertEquals($responseCode, $client->getResponse()->getStatusCode());
        $this->assertEquals($response, json_decode($client->getResponse()->getContent(), true));
    }

    public function getDeviceDataProvider()
    {
        return [
            ['not-existing', 404, null],
            ['dishwasher', 200, ['name' => 'dishwasher', 'program' => 'super clean', 'finishes_at' => 0, 'state' => DeviceState::STATE_READY, 'door_open' => false]],
            ['kitten-washer', 200, ['name' => 'kitten-washer', 'program' => 'kitten clean', 'finishes_at' => 10, 'state' => DeviceState::STATE_RUNNING, 'door_open' => false]],
            ['dishwasher-perfect-edition', 200, ['name' => 'dishwasher-perfect-edition', 'program' => 'perfect wash', 'finishes_at' => 0, 'state' => DeviceState::STATE_READY, 'door_open' => true]],
            ['oven', 200, ['name' => 'oven', 'program' => 'hot like hell', 'finishes_at' => 20, 'state' => DeviceState::STATE_RUNNING, 'door_open' => false]],
        ];
    }

    /**
     * @dataProvider stateChangeDataProvider
     */
    public function testStateChange(string $device, array $request, int $responseCode, array $response = null)
    {
        Time::$currentTime = 5;
        $client = static::createClient();

        $serializer = $client->getKernel()->getContainer()->get('jms_serializer');

        $requestBody = $serializer->serialize($request, 'json');

        $client->request('PATCH', '/' . $device, [], [], [], $requestBody);

        $this->assertEquals($responseCode, $client->getResponse()->getStatusCode());
        $this->assertEquals($response, json_decode($client->getResponse()->getContent(), true));
    }

    public function stateChangeDataProvider()
    {
        return [
            ['not-existing', ['state' => DeviceState::STATE_RUNNING], 404, null],

            ['kitten-washer', ['door_open' => true], 400, null],
            ['kitten-washer', ['state' => DeviceState::STATE_READY], 200, ['name' => 'kitten-washer', 'program' => 'kitten clean', 'finishes_at' => 0, 'state' => DeviceState::STATE_READY, 'door_open' => false]],

            ['dishwasher-perfect-edition', ['state' => DeviceState::STATE_RUNNING], 400, null],
            ['dishwasher-perfect-edition', ['door_open' => false], 200, ['name' => 'dishwasher-perfect-edition', 'program' => 'perfect wash', 'finishes_at' => 0, 'state' => DeviceState::STATE_READY, 'door_open' => false]],

            ['dishwasher', ['state' => DeviceState::STATE_RUNNING], 200, ['name' => 'dishwasher', 'program' => 'super clean', 'finishes_at' => 15, 'state' => DeviceState::STATE_RUNNING, 'door_open' => false]],
            ['dishwasher', ['door_open' => true], 200, ['name' => 'dishwasher', 'program' => 'super clean', 'finishes_at' => 0, 'state' => DeviceState::STATE_READY, 'door_open' => true]],
        ];
    }


}
