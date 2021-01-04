<?php

namespace Pars\Admin\File;


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
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('file.create', 'file.edit', 'file.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('file');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('media');
        $subNavigation = new MediaNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('file');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }


    protected function createOverview(): BaseOverview
    {
        return new FileOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createDetail(): BaseDetail
    {
        $detail = new FileDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $detail->setAssetDomain($this->getModel()->getConfig('asset.domain'));
        return $detail;
    }

    protected function createEdit(): BaseEdit
    {
        $edit = new FileEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setTypeOptions($this->getModel()->getFileType_Options());
        $edit->setDirectoryOptions($this->getModel()->getFileDirectory_Options());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        return new FileDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }


}
