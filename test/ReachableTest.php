<?php


namespace ParsTest\Admin;


use GuzzleHttp\Client;
use Pars\Admin\Setup\Setup;
use Pars\Admin\Setup\SetupModel;
use Pars\Bean\PHPUnit\DefaultTestCase;
use Pars\Core\Container\ParsContainer;
use Pars\Core\Deployment\UpdaterInterface;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Mvc\View\ViewBeanConverter;
use Psr\Container\ContainerInterface;

class ReachableTest extends DefaultTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $updater = $this->getContainer()->get(UpdaterInterface::class);
        $updater->updateDB();

        $setupModel = new SetupModel($this->getContainer());
        $setupModel->setBeanConverter(new ViewBeanConverter());
        $setupModel->initialize();
        $setupModel->initializeDependencies();
        $setupModel->addOption(SetupModel::OPTION_CREATE_ALLOWED);
        $setupModel->handleSubmit(
            SubmitParameter::create(),
            IdParameter::fromMap([]),
            IdListParameter::fromMap([]),
            [
                'Person_Firstname' => 'Max',
                'Person_Lastname' => 'Mustermann',
                'User_Username' => 'max',
                'User_Displayname' => 'Max M.',
                'User_Password' => 'password',
                'UserState_Code' => 'active',
            ]
        );
    }

    protected function getParsContainer(): ParsContainer
    {
        $container = $this->getContainer();
        return $container->get(ParsContainer::class);
    }

    protected function getContainer(): ContainerInterface
    {
        return require PARS_CONTAINER;
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
