<?php

namespace AppBundle\Tests\Controller;

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
            ['dishwasher', 200, ['name' => 'dishwasher', 'program' => 'super clean', 'remaining' => 0, 'state' => 'ready']],
            ['kitten-washer', 200, ['name' => 'kitten-washer', 'program' => 'kitten clean', 'remaining' => 10, 'state' => 'running']],
            ['dishwasher-perfect-edition', 200, ['name' => 'dishwasher-perfect-edition', 'program' => 'perfect wash', 'remaining' => 0, 'state' => 'open']],
            ['oven', 200, ['name' => 'oven', 'program' => 'hot like hell', 'remaining' => 20, 'state' => 'running']],
        ];
    }

    /**
     * @dataProvider stateChangeDataProvider
     */
    public function testStateChange(string $device, array $request, int $responseCode, array $response = null)
    {
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
            ['not-existing', ['state' => 'running'], 404, null],

            ['kitten-washer', ['state' => 'open'], 400, null],
            ['kitten-washer', ['state' => 'ready'], 200, ['name' => 'kitten-washer', 'program' => 'kitten clean', 'remaining' => 0, 'state' => 'ready']],

            ['dishwasher-perfect-edition', ['state' => 'running'], 400, null],
            ['dishwasher-perfect-edition', ['state' => 'ready'], 200, ['name' => 'dishwasher-perfect-edition', 'program' => 'perfect wash', 'remaining' => 0, 'state' => 'ready']],

            ['dishwasher', ['state' => 'running'], 200, ['name' => 'dishwasher', 'program' => 'super clean', 'remaining' => 10, 'state' => 'running']],
            ['dishwasher', ['state' => 'open'], 200, ['name' => 'dishwasher', 'program' => 'super clean', 'remaining' => 0, 'state' => 'open']],
        ];
    }


}
