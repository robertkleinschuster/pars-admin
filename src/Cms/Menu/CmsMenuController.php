<?php

namespace Pars\Admin\Cms\Menu;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudController;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Path\PathHelper;


/**
 * Class CmsMenuController
 * @package Pars\Admin\Cms\Menu
 * @method CmsMenuModel getModel()
 */
class CmsMenuController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmsmenu.create', 'cmsmenu.edit', 'cmsmenu.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmsmenu');
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('CmsMenu_ID'));
    }

    protected function addOverviewFields(Overview $overview): void
    {
        $redirect = $this->getPathHelper()->getPath();
        $overview->addMoveDownIcon($this->getDetailPath()->addParameter((new MoveParameter())->setDown('CmsMenu_Order'))->addParameter((new RedirectParameter())->setLink($redirect))->getPath())
            ->setWidth(85)
            ->setShow(function (BeanInterface $bean) {
                return $bean->hasData('CmsMenu_Order')
                    && $bean->getData('CmsMenu_Order') < $this->getModel()->getBeanFinder()->count();
            });
        $overview->addMoveUpIcon($this->getDetailPath()->addParameter((new MoveParameter())->setUp('CmsMenu_Order'))->addParameter((new RedirectParameter())->setLink($redirect))->getPath())
            ->setShow(function (BeanInterface $bean) {
                return $bean->hasData('CmsMenu_Order') && $bean->getData('CmsMenu_Order') > 1;
            });


        $overview->addText('Article_Code', $this->translate('article.code'))->setWidth(150);
        $overview->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $overview->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addText('Article_Code', $this->translate('article.code'));
        $detail->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $detail->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));
    }

    protected function addEditFields(Edit $edit): void
    {
        $edit->addSelect('CmsPage_ID', $this->translate('articletranslation.name'))
            ->setSelectOptions($this->getModel()->getCmsPage_Options());
        $edit->addSelect('CmsMenuState_Code', $this->translate('articlestate.code'))
            ->setSelectOptions($this->getModel()->getCmsMenuState_Options());
        $edit->addSubmitAttribute('CmsMenuType_Code', 'header');
    }
}
