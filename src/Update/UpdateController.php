<?php

namespace Pars\Admin\Update;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Pars\Admin\Base\BaseController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Jumbotron\Jumbotron;
use Pars\Component\Base\Navigation\Navigation;
use Pars\Helper\Parameter\Parameter;
use Pars\Helper\String\StringHelper;
use Pars\Model\Updater\ParsUpdater;

/**
 * Class UpdateController
 * @package Pars\Admin\Controller
 * @method UpdateModel getModel()
 */
class UpdateController extends BaseController
{
    protected function initModel()
    {
        parent::initModel();
        if ($this->checkPermission('update.schema')) {
            $this->getModel()->addOption(UpdateModel::OPTION_SCHEMA_ALLOWED);
        }
        if ($this->checkPermission('update.data')) {
            $this->getModel()->addOption(UpdateModel::OPTION_DATA_ALLOWED);
        }
        if ($this->checkPermission('update.special')) {
            $this->getModel()->addOption(UpdateModel::OPTION_SPECIAL_ALLOWED);
        }
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('update');
    }

    protected $updateNavigation = null;

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('update');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        $updateNavigation = new Navigation();
        $this->updateNavigation = $updateNavigation;
        $this->updateNavigation->setBackground(Navigation::BACKGROUND_LIGHT);
        $this->updateNavigation->addItem($this->translate('update.database.data'), $this->getPathHelper()->setController('update')->setAction('data'), 'data');
        $this->updateNavigation->addItem($this->translate('update.database.schema'), $this->getPathHelper()->setController('update')->setAction('schema'), 'schema');
        $this->updateNavigation->addItem($this->translate('update.database.special'), $this->getPathHelper()->setController('update')->setAction('special'), 'special');
        $this->getView()->pushComponent($this->updateNavigation);
    }


    public function indexAction()
    {
        $jumbo = new Jumbotron();
        $client = new Client();
        $frontendVersion = '';
        try {
            $frontendUri = new Uri($this->getConfig()->get('frontend.domain'));
            $frontendUri = Uri::withQueryValue($frontendUri, 'version', $this->getConfig()->getSecret());
            $frontendVersion = $client->get($frontendUri)->getBody()->getContents();
        } catch (\Throwable $exception) {
            $this->getLogger()->error('Error getting fronten version.', ['exception' => $exception]);
        }
        $field = new Span($frontendVersion, $this->translate('update.version.current'));
        $field->setGroup('PARS-Frontend');
        $jumbo->pushField($field);
        $response = $client->get('https://api.github.com/repos/PARS-Framework/pars-frontend/releases/latest');
        $data = json_decode($response->getBody()->getContents(), true);
        $field = new Span($data['tag_name'], $this->translate('update.version.available'));
        $field->setGroup('PARS-Frontend');
        $jumbo->pushField($field);
        $field = new Span(PARS_VERSION, $this->translate('update.version.current'));
        $field->setGroup('PARS-Admin');
        $jumbo->pushField($field);
        $response = $client->get('https://api.github.com/repos/PARS-Framework/pars-admin/releases/latest');
        $data = json_decode($response->getBody()->getContents(), true);
        $field = new Span($data['tag_name'], $this->translate('update.version.available'));
        $field->setGroup('PARS-Admin');
        $jumbo->pushField($field);
        $button = new Button($this->translate('update.version.start'),
            Button::STYLE_DANGER,
            $this->getPathHelper()
                ->setController('update')
                ->setAction('index')
                ->addParameter(new Parameter('update', $this->getConfig()->getSecret()))
                ->getPath());
        $button->addOption(Button::OPTION_DECORATION_NONE);
        if ($this->getConfig()->get('update.enabled')) {
            $jumbo->pushField($button);
        }
        $this->getView()->pushComponent($jumbo);
    }

    public function schemaAction()
    {
        $this->updateNavigation->setActive('schema');
        $update = new Update($this->getTranslator(), $this->getUserBean(), $this->getModel()->getSchemaUpdater());
        $update->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $update->setToken($this->generateToken('submit_token'));
        $this->getView()->pushComponent($update);
    }


    public function dataAction()
    {
        $this->updateNavigation->setActive('data');
        $update = new Update($this->getTranslator(), $this->getUserBean(), $this->getModel()->getDataUpdater());
        $update->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $update->setToken($this->generateToken('submit_token'));
        $this->getView()->pushComponent($update);
    }

    public function specialAction()
    {
        $this->updateNavigation->setActive('special');
        $update = new Update($this->getTranslator(), $this->getUserBean(), $this->getModel()->getSpecialUpdater());
        $update->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $update->setToken($this->generateToken('submit_token'));
        $this->getView()->pushComponent($update);
    }

}
