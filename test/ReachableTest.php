<?php


namespace ParsTest\Admin;


use GuzzleHttp\Client;
use Pars\Bean\PHPUnit\DefaultTestCase;
use Pars\Core\Container\ParsContainer;
use Pars\Core\Deployment\UpdaterInterface;
use Psr\Container\ContainerInterface;

class ReachableTest extends DefaultTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $updater = $this->getContainer()->get(UpdaterInterface::class);
        $updater->updateDB();
    }

    protected function getParsContainer(): ParsContainer
    {
        $container = $this->getContainer();
        return $container->get(ParsContainer::class);
    }

    protected function getContainer(): ContainerInterface
    {
        return require 'config/container.php';
    }

    /**
     * @group unit
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testIsReachable()
    {
        $client = new Client();
        $response = $client->get('localhost:9090');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('pars-admin-authentication-signinform', $response->getBody()->getContents());
    }
}
