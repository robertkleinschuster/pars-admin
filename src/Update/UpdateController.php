<?php

namespace Pars\Admin\Update;

use Pars\Model\Database\Updater\AbstractUpdater;
use Pars\Admin\Base\BaseController;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Navigation\Navigation;

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


    public function indexAction()
    {
        $this->getView()->setHeading('Updates');
        $navigation = new Navigation($this->translate('update.database'));
        $dataComponent = $this->initUpdaterTemplate($this->getModel()->getDataUpdater(), $this->translate('update.database.data'), 'data');
        $dataComponent->setPermission('update.data');
        $navigation->addComponent($dataComponent);
        $schemaComponent = $this->initUpdaterTemplate($this->getModel()->getSchemaUpdater(), $this->translate('update.database.schema'), 'schema');
        $schemaComponent->setPermission('update.schema');
        $navigation->addComponent($schemaComponent);
        $navigation->setPermission('update');
        $navigation->setActive($this->getNavigationState($navigation->getId()));
        $this->getView()->append($navigation);
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
