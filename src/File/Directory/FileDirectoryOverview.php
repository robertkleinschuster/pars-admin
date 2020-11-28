<?php


namespace Pars\Admin\File\Directory;


use Pars\Admin\Base\BaseOverview;

class FileDirectoryOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->addField('FileDirectory_Code', $this->translate('filedirectory.code'));
        $this->addField('FileDirectory_Name', $this->translate('filedirectory.name'));
        $this->addField('FileDirectory_Active', $this->translate('filedirectory.active'));
        parent::initialize();
    }

    protected function getController(): string
    {
       return 'filedirectory';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'FileDirectory_ID'
        ];
    }

}
