<?php

namespace Pars\Admin\File\Directory;

use Pars\Admin\Base\BaseDetail;

class FileDirectoryDetail extends BaseDetail
{
    protected function initName()
    {
        $this->setName('{FileDirectory_Name}');
    }


    protected function initialize()
    {

        $this->setHeading('{FileDirectory_Name}');
        $this->addSpan('FileDirectory_Code', $this->translate('filedirectory.code'));
        $this->addSpan('FileDirectory_Active', $this->translate('filedirectory.active'));

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
