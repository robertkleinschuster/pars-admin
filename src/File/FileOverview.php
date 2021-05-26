<?php

namespace Pars\Admin\File;

use Pars\Admin\Base\BaseOverview;

class FileOverview extends BaseOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.file'));
    }

    protected function initBase()
    {
        parent::initBase();
        $this->setShowOrder(true);
    }


    protected function initFields()
    {
        $this->addFieldOrderable('File_Name', $this->translate('file.name'));
        $this->addFieldOrderable('FileType_Name', $this->translate('filetype.name'));
        $this->addFieldOrderable('File_Code', $this->translate('file.code'));
    }


    protected function getController(): string
    {
        return 'file';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'File_ID'
        ];
    }

    protected function getCreateIdFields(): array
    {
        if ($this->getControllerRequest()->hasId()) {
            return $this->getControllerRequest()->getId()->getAttributes();
        }
        return [];
    }
}
