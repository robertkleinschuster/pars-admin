<?php

namespace Pars\Admin\Update;

use Pars\Admin\Base\BaseController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Component\Base\Navigation\Navigation;
use Pars\Core\Database\Updater\DataUpdater;
use Pars\Helper\Parameter\SubmitParameter;

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
        $this->getView()->append($this->updateNavigation);
    }


    public function indexAction()
    {

        /*$this->getView()->setHeading('Updates');
        $navigation = new Navigation($this->translate('update.database'));
        $dataComponent = $this->initUpdaterTemplate($this->getModel()->getDataUpdater(), $this->translate('update.database.data'), 'data');
        $dataComponent->setPermission('update.data');
        $navigation->addComponent($dataComponent);
        $schemaComponent = $this->initUpdaterTemplate($this->getModel()->getSchemaUpdater(), $this->translate('update.database.schema'), 'schema');
        $schemaComponent->setPermission('update.schema');
        $navigation->addComponent($schemaComponent);
        $navigation->setPermission('update');
        $navigation->setActive($this->getNavigationState($navigation->getId()));
        $this->getView()->append($navigation);*/
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

    public function initUpdaterTemplate(AbstractUpdater $updater, string $title, string $submitAction)
    {
        $previewList = $updater->getPreviewMap();
        $edit = new Edit($title);
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());

        $edit->setCols(1);
        foreach ($previewList as $key => $item) {
            $edit->addCheckbox($key, 'Update Methode: ' . $key)
                ->setValue($key)
                ->setChecked(true)
                ->setHint('<pre>' . json_encode($item, JSON_PRETTY_PRINT) . '</pre>');
        }
        $edit->addSubmit((new SubmitParameter())->setMode($submitAction), $this->translate('update.submit'));
        return $edit;
    }
}
