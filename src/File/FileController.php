<?php

namespace Pars\Admin\File;

use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\MediaNavigation;

/**
 * Class FileController
 * @package Pars\Admin\File
 * @method FileModel getModel()
 */
class FileController extends CrudController
{
    /**
     * @return mixed|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('file.create', 'file.edit', 'file.delete');
    }


    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('file');
    }

    /**
     * @return mixed|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws BeanException
     */
    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('media');
        $subNavigation = new MediaNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('file');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    /**
     * @return BaseOverview
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function createOverview(): BaseOverview
    {
        return new FileOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    /**
     * @return BaseDetail
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function createDetail(): BaseDetail
    {
        return new FileDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    /**
     * @return BaseEdit
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function createEdit(): BaseEdit
    {
        $edit = new FileEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setTypeOptions($this->getModel()->getFileType_Options());
        $edit->setDirectoryOptions($this->getModel()->getFileDirectory_Options());
        return $edit;
    }

    /**
     * @return BaseDelete
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function createDelete(): BaseDelete
    {
        return new FileDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }
}
