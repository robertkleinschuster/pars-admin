<?php

namespace Pars\Admin\File;


use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

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


    protected function getDetailPath(): PathHelper
    {
        if ($this->getControllerRequest()->getId()->hasAttribute('FileDirectory_ID')) {
            return $this->getPathHelper()->setController('file')->setId($this->getControllerRequest()->getId()->addId('File_ID'));
        }
        return $this->getPathHelper()->setController('file')->setId((new IdParameter())->addId('File_ID'));
    }

    protected function getCreatePath(): PathHelper
    {
        if ($this->getControllerRequest()->getId()->hasAttribute('FileDirectory_ID')) {
            return parent::getCreatePath()->setController('file')->setId($this->getControllerRequest()->getId());
        }
        return parent::getCreatePath();
    }

    protected function getIndexPath(): PathHelper
    {
        if ($this->getControllerRequest()->getId()->hasAttribute('FileDirectory_ID')) {
            return parent::getIndexPath()->setController('filedirectory')->setAction('detail')->setId($this->getControllerRequest()->getId()->unsetAttribute('File_ID'));
        }
        return parent::getIndexPath();
    }


    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addText('File_Name', $this->translate('file.name'));
        $overview->addText('FileType_Name', $this->translate('filetype.name'));
        $overview->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addText('File_Name', $this->translate('file.name'));
        $detail->addText('FileType_Name', $this->translate('filetype.name'));
        $detail->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
    }

    protected function addEditFields(Edit $edit): void
    {
        if (!$this->getControllerRequest()->getId()->hasAttribute('File_ID')) {
            $edit->addSelect('FileType_Code', $this->translate('filetype.name'))
                ->setSelectOptions($this->getModel()->getFileType_Options());
        }
        if (!$this->getControllerRequest()->getId()->hasAttribute('FileDirectory_ID')
            && !$this->getControllerRequest()->getId()->hasAttribute('File_ID')) {
            $edit->addSelect('FileDirectory_ID', $this->translate('filedirectory.name'))
                ->setSelectOptions($this->getModel()->getFileDirectory_Options());
        }
        $edit->addText('File_Name', $this->translate('file.name'));
        $edit->addFile('Upload', $this->translate('file.upload'))->setShow(function (BeanInterface $bean) {
            return !$bean->hasData('File_ID') || !$bean->hasData('File_Code');
        });
    }
}
