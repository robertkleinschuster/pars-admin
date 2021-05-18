<?php


namespace ParsTest\Admin;


use GuzzleHttp\Client;
use Pars\Bean\PHPUnit\DefaultTestCase;

class ReachableTest extends DefaultTestCase
{
    /**
     * @group unit
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testIsReachable()
    {
        $client = new Client();
        $response = $client->get('localhost:9090');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
