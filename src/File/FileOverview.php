<?php

namespace Pars\Admin\File;

use Pars\Admin\Base\BaseOverview;

class FileOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.file'));

        $this->addField('File_Name', $this->translate('file.name'));
        $this->addField('FileType_Name', $this->translate('filetype.name'));
        $this->addField('File_Code', $this->translate('file.code'));
        parent::initialize();
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
}
