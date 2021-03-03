<?php

namespace Pars\Admin\Update;

use Pars\Admin\Base\BaseController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Component\Base\Navigation\Navigation;

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
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('update');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        $updateNavigation = new Navigation();
        $this->updateNavigation = $updateNavigation;
        $this->updateNavigation->setBackground(Navigation::BACKGROUND_LIGHT);
        $this->updateNavigation->setRounded(Navigation::ROUNDED_NONE);
        $this->updateNavigation->addItem($this->translate('update.database.data'), $this->getPathHelper()->setController('update')->setAction('data'), 'data');
        $this->updateNavigation->addItem($this->translate('update.database.schema'), $this->getPathHelper()->setController('update')->setAction('schema'), 'schema');
        $this->updateNavigation->addItem($this->translate('update.database.special'), $this->getPathHelper()->setController('update')->setAction('special'), 'special');
        $this->getView()->append($this->updateNavigation);
    }


    public function indexAction()
    {
    }

    public function schemaAction()
    {
        $this->updateNavigation->setActive('schema');
        $update = new Update($this->getPathHelper(), $this->getTranslator(), $this->getUserBean(), $this->getModel()->getSchemaUpdater());
        $update->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $update->setToken($this->generateToken('submit_token'));
        $this->getView()->append($update);
    }


    public function dataAction()
    {
        $this->updateNavigation->setActive('data');
        $update = new Update($this->getPathHelper(), $this->getTranslator(), $this->getUserBean(), $this->getModel()->getDataUpdater());
        $update->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $update->setToken($this->generateToken('submit_token'));
        $this->getView()->append($update);
    }

    public function specialAction()
    {
        $this->updateNavigation->setActive('special');
        $update = new Update($this->getPathHelper(), $this->getTranslator(), $this->getUserBean(), $this->getModel()->getSpecialUpdater());
        $update->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $update->setToken($this->generateToken('submit_token'));
        $this->getView()->append($update);
    }
}
