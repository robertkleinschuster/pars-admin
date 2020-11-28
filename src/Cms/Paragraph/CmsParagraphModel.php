<?php

namespace Pars\Admin\Cms\Paragraph;

use Pars\Admin\Article\ArticleModel;
use Pars\Model\Cms\Paragraph\State\CmsParagraphStateBeanFinder;
use Pars\Model\Cms\Paragraph\Type\CmsParagraphTypeBeanFinder;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanProcessor;

class CmsParagraphModel extends ArticleModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsParagraphBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsParagraphBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }


    public function getCmsParagraphType_Options(): array
    {
        $options = [];
        $finder = new CmsParagraphTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsParagraphType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsParagraphType_Code')] = $this->translate('cmsparagraphtype.code.' . $bean->get('CmsParagraphType_Code'));
        }
        return $options;
    }

    public function getCmsParagraphState_Options(): array
    {
        $options = [];
        $finder = new CmsParagraphStateBeanFinder($this->getDbAdpater());
        $finder->setCmsParagraphState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsParagraphState_Code')] = $this->translate('cmsparagraphstate.code.' . $bean->get('CmsParagraphState_Code'));
        }
        return $options;
    }
}
