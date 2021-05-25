<?php

namespace Pars\Admin\File;

use Pars\Admin\Base\BaseOverview;

class FileOverview extends BaseOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.file'));
    }

    protected function initFields()
    {
        $this->addField('File_Name', $this->translate('file.name'));
        $this->addField('FileType_Name', $this->translate('filetype.name'));
        $this->addField('File_Code', $this->translate('file.code'));
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
