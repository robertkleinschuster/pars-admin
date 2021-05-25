<?php


namespace Pars\Admin\Picture;


use Pars\Admin\File\FileOverview;

class PictureOverview extends FileOverview
{
    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('articlepicture.overview'));
    }


    protected function getDetailIdFields(): array
    {
        return [
            'Picture_ID'
        ];
    }

    protected function getController(): string
    {
        return $this->getControllerRequest()->getController();
    }


}
