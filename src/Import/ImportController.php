<?php

namespace Pars\Admin\Import;

use Pars\Bean\Type\Base\BeanException;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Admin\Import\Tesla\TeslaImportConfigure;
use Pars\Component\Base\Alert\Alert;
use Pars\Mvc\Exception\MvcException;
use Pars\Mvc\Exception\NotFoundException;

class ImportController extends CrudController
{

    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('import.create', 'import.edit', 'import.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('import');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('import');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }


    /**
     * @return BaseEdit
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     */
    protected function createEdit(): BaseEdit
    {
        $edit = parent::createEdit();
        $edit->setImportTypeOptions($this->getModel()->getImportTypeOptions());
        $edit->setArticleOptions($this->getModel()->getArticleOptions());
        return $edit;
    }


    /**
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws NotFoundException
     */
    public function configureAction()
    {
        $bean = $this->getModel()->getBean();
        switch ($bean->get('ImportType_Code')) {
            case 'tesla':
                if (isset($bean->get('Import_Data')['access_token'])) {
                    $alert = new Alert($this->translate('tesla.authentication.alert'));
                    $this->getView()->pushComponent($alert);
                }
                $configure = new TeslaImportConfigure($this->getTranslator(), $this->getUserBean());
                $configure->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
                $configure->setBean($bean);
                $this->getModel()->getBeanConverter()
                    ->convert($configure->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
                $configure->setToken($this->getTokenName(), $this->generateToken($this->getTokenName()));
                $this->getView()->pushComponent($configure);
                break;
        }
    }

    /**
     */
    public function runAction()
    {
        if (!count($this->getValidationErrorMap())) {
            $this->getModel()->run();
        }
    }
}
