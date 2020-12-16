<?php


namespace Pars\Admin\Import;


use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;

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
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('import');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }




    /**
     * @return BaseOverview
     * @throws \Niceshops\Bean\Type\Base\BeanException
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    protected function createOverview(): BaseOverview
    {
        return new ImportOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    /**
     * @return BaseDetail
     * @throws \Niceshops\Bean\Type\Base\BeanException
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    protected function createDetail(): BaseDetail
    {
        return new ImportDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    /**
     * @return BaseEdit
     * @throws \Niceshops\Bean\Type\Base\BeanException
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    protected function createEdit(): BaseEdit
    {
        $edit = new ImportEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setImportTypeOptions($this->getModel()->getImportTypeOptions());
        return $edit;
    }

    /**
     * @return BaseDelete
     * @throws \Niceshops\Bean\Type\Base\BeanException
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    protected function createDelete(): BaseDelete
    {
        return new ImportDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

}
