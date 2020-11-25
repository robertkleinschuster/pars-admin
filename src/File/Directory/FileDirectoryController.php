<?php

namespace Pars\Admin\File\Directory;


use Pars\Admin\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

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


    protected function getDetailPath(): PathHelper
    {
        return parent::getDetailPath()->setId((new IdParameter())->addId('FileDirectory_ID'));
    }

    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addBadgeBoolean(
            'FileDirectory_Active',
            $this->translate('filedirectory.active'),
            $this->translate('filedirectory.active.true'),
            $this->translate('filedirectory.active.false')
        );
        $overview->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addBadgeBoolean(
            'FileDirectory_Active',
            $this->translate('filedirectory.active'),
            $this->translate('filedirectory.active.true'),
            $this->translate('filedirectory.active.false')
        );
        $detail->addText('FileDirectory_Name', $this->translate('filedirectory.name'));

        $this->addSubController('file', 'index');
    }

    protected function addEditFields(Edit $edit): void
    {
        $edit->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
        $edit->addCheckbox('FileDirectory_Active', $this->translate('filedirectory.active'));
    }
}
