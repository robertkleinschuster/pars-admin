<?php


namespace Pars\Admin\File\Directory;


use Pars\Admin\Base\BaseEdit;

class FileDirectoryEdit extends BaseEdit
{
    protected function initialize()
    {
        $this->getForm()->addText('FileDirectory_Code', '{FileDirectory_Code}', $this->translate('filedirectory.code'));
        $this->getForm()->addText('FileDirectory_Name', '{FileDirectory_Name}', $this->translate('filedirectory.name'));
        $this->getForm()->addCheckbox('FileDirectory_Active', '{FileDirectory_Active}', $this->translate('filedirectory.active'));
        parent::initialize();
    }


    protected function getRedirectController(): string
    {
        return 'filedirectory';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'FileDirectory_ID'
        ];
    }

}
