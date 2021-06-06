<?php

namespace Pars\Admin\Cms\Menu;

use Pars\Bean\Processor\BeanOrderProcessor;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudModel;
use Pars\Model\Cms\Menu\CmsMenuBeanFinder;
use Pars\Model\Cms\Menu\CmsMenuBeanProcessor;
use Pars\Model\Cms\Menu\State\CmsMenuStateBeanFinder;
use Pars\Model\Cms\Menu\Type\CmsMenuTypeBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanFinder;

/**
 * Class CmsMenuModel
 * @package Pars\Admin\Cms\Menu
 * @method CmsMenuBeanFinder getBeanFinder()
 * @method CmsMenuBeanProcessor getBeanProcessor()
 */
class CmsMenuModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsMenuBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new CmsMenuBeanProcessor($this->getDatabaseAdapter()));
        $this->getBeanFinder()->filterLocale_Code($this->getTranslator()->getLocale());
        $this->setBeanOrderProcessor(new BeanOrderProcessor(
            new CmsMenuBeanProcessor($this->getDatabaseAdapter()),
            (new CmsMenuBeanFinder($this->getDatabaseAdapter()))->filterLocale_Code($this->getTranslator()->getLocale()),
            'CmsMenu_Order',
            'CmsMenu_ID_Parent'
        ));
        $this->initFinder();
    }

    /**
     *
     */
    protected function initFinder()
    {
        $this->getBeanFinder()->setCmsMenu_ID_Parent(null);
    }


    public function getCmsPage_Options(): array
    {
        $options = [];
        $finder = new CmsPageBeanFinder($this->getDatabaseAdapter());
        $finder->filterLocale_Code($this->getTranslator()->getLocale());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPage_ID')] = $bean->get('ArticleTranslation_Name') ?? '';
        }
        return $options;
    }

    public function getCmsMenuState_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $finder = new CmsMenuStateBeanFinder($this->getDatabaseAdapter());
        $finder->setCmsMenuState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsMenuState_Code')] = $this->translate('cmsmenustate.code.' . $bean->get('CmsMenuState_Code'));
        }
        return $options;
    }

    public function getCmsMenuType_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $finder = new CmsMenuTypeBeanFinder($this->getDatabaseAdapter());
        $finder->setCmsMenuType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsMenuType_Code')] = $this->translate('cmsmenutype.code.' . $bean->get('CmsMenuType_Code'));
        }
        return $options;
    }

    public function getEmptyBean(array $data = []): BeanInterface
    {
        $bean = parent::getEmptyBean($data);
        $bean->set('CmsMenuType_Code', 'header');
        $bean->set('CmsMenuState_Code', 'active');
        return $bean;
    }
}
