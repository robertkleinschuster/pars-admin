<?php


namespace Pars\Admin\Cms\Block;

/**
 * Class CmsSubBlockOverview
 * @package Pars\Admin\Cms\Block
 */
class CmsSubBlockOverview extends CmsBlockOverview
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected function initBase()
    {
        parent::initBase();
        $this->setShowOrder(false);
        $this->setShowMove(true);
    }


    protected function getController(): string
    {
        return 'cmssubblock';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'CmsBlock_ID'
        ];
    }

    protected function getCreateIdFields(): array
    {
        return [
            'CmsBlock_ID_Parent'
        ];
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'CmsBlock_ID' => '{CmsBlock_ID_Parent}'
        ];
    }

    protected function getRedirectController(): string
    {
        return 'cmsblock';
    }
}
