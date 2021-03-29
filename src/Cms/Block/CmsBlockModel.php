<?php

namespace Pars\Admin\Cms\Block;

use Niceshops\Bean\Finder\BeanFinderInterface;
use Niceshops\Bean\Processor\BeanProcessorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Type\Base\BeanListInterface;
use Pars\Admin\Article\ArticleModel;
use Pars\Model\Cms\Block\CmsBlockBean;
use Pars\Model\Cms\Block\CmsBlockBeanFinder;
use Pars\Model\Cms\Block\CmsBlockBeanList;
use Pars\Model\Cms\Block\CmsBlockBeanProcessor;
use Pars\Model\Cms\Block\State\CmsBlockStateBeanFinder;
use Pars\Model\Cms\Block\Type\CmsBlockTypeBeanFinder;

/**
 * Class CmsBlockModel
 * @package Pars\Admin\Cms\Block
 * @method CmsBlockBeanFinder getBeanFinder()
 * @method CmsBlockBeanProcessor getBeanProcessor()
 * @method CmsBlockBean getBean()
 * @method CmsBlockBeanList getBeanList()
 */
class CmsBlockModel extends ArticleModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsBlockBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsBlockBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
        $this->initFinder();
    }

    /**
     *
     */
    protected function initFinder()
    {
        $this->getBeanFinder()->setCmsBlock_ID_Parent(null);
    }


    public function getCmsBlockType_Options(): array
    {
        $options = [];
        $finder = new CmsBlockTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsBlockType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsBlockType_Code')] = $this->translate('cmsblocktype.code.' . $bean->get('CmsBlockType_Code'));
        }
        return $options;
    }

    public function getCmsBlockState_Options(): array
    {
        $options = [];
        $finder = new CmsBlockStateBeanFinder($this->getDbAdpater());
        $finder->setCmsBlockState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsBlockState_Code')] = $this->translate('cmsblockstate.code.' . $bean->get('CmsBlockState_Code'));
        }
        return $options;
    }

    public function getEmptyBean(array $data = []): BeanInterface
    {
        $bean = parent::getEmptyBean($data);
        $bean->set('CmsBlockType_Code', 'text');
        $bean->set('CmsBlockState_Code', 'active');
        return $bean;
    }
}
