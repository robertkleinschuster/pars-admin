<?php


namespace Pars\Admin\File;


use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Image;

class FileDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.file'));
        $this->setHeadline('{File_Name}');
        $this->addField('FileType_Name', $this->translate('filetype.name'));
        $this->addField('File_Code', $this->translate('file.code'));
        $image = new Image("/upload/{FileDirectory_Code}/{File_Code}.{FileType_Code}");
        $image->addInlineStyle('max-height', '200px');
        $this->append($image);
        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'file';
    }

    protected function getEditIdFields(): array
    {
        return [
            'File_ID'
        ];
    }

}
