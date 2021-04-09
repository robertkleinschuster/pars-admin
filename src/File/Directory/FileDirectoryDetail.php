<?php

namespace Pars\Admin\File\Directory;

use Pars\Admin\Base\BaseDetail;

class FileDirectoryDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setSection('{FileDirectory_Name}');

        $this->setHeading('{FileDirectory_Name}');
        $this->addField('FileDirectory_Code', $this->translate('filedirectory.code'));
        $this->addField('FileDirectory_Active', $this->translate('filedirectory.active'));

        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'filedirectory';
    }

    protected function getEditIdFields(): array
    {
        return [
            'FileDirectory_ID'
        ];
    }
}
