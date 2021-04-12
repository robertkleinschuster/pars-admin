<?php

namespace Pars\Admin\File;

use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Image;

class  FileDetail extends BaseDetail
{
    protected ?string $assetDomain = null;

    protected function initName()
    {
        $this->setName('{File_Name}');
    }


    protected function initialize()
    {
        $path = "/u/{FileDirectory_Code}/{File_Code}.{FileType_Code}";
        if ($this->hasAssetDomain()) {
            $path = $this->getAssetDomain() . $path;
        }
        $this->addField('File_Name', $this->translate('file.name'));
        $image = new Image($path);
        $image->addInlineStyle('max-height', '200px');
        $image->setLabel($this->translate('file.preview'));
        $this->append($image);
        $this->addField('File_Code', $this->translate('file.code'));

        $this->addField('File_Code', $this->translate('file.path'))
            ->setContent($path)
            ->setPath($path)
            ->setTarget('_blank');

        $this->addField('FileType_Name', $this->translate('filetype.name'));
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

    /**
    * @return string
    */
    public function getAssetDomain(): string
    {
        return $this->assetDomain;
    }

    /**
    * @param string $assetDomain
    *
    * @return $this
    */
    public function setAssetDomain(string $assetDomain): self
    {
        $this->assetDomain = $assetDomain;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasAssetDomain(): bool
    {
        return isset($this->assetDomain);
    }
}
