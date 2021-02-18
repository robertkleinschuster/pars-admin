<?php

namespace Pars\Admin\File\Directory;


use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\MediaNavigation;

/**
 * Class FileDirectoryController
 * @package Pars\Admin\File\Directory
 * @method FileDirectoryModel getModel()
 */
class FileDirectoryController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('filedirectory.create', 'filedirectory.edit', 'filedirectory.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('filedirectory');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('media');
        $subNavigation = new MediaNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('filedirectory');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    public function detailAction()
    {
        $this->pushAction('file', 'index', $this->translate('section.file'));
        return parent::detailAction();
    }

}
