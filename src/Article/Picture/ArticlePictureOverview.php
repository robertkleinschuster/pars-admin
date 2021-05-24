<?php


namespace Pars\Admin\Article\Picture;


use Pars\Admin\Picture\PictureOverview;

class ArticlePictureOverview extends PictureOverview
{
    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('articlepicture.overview'));
    }

    protected function initAdditionalBefore()
    {
        parent::initAdditionalBefore();
        $this->setShowLink(true);
    }

    protected function getController(): string
    {
        return 'articlepicture';
    }

    protected function getDetailIdFields(): array
    {
        return ['Article_ID', 'Picture_ID'];
    }

    protected function getCreateIdFields(): array
    {
        return ['Article_ID'];
    }
}
