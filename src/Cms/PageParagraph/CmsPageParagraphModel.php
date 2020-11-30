<?php

namespace Pars\Admin\Cms\PageParagraph;

use Niceshops\Bean\Processor\BeanOrderProcessor;
use Pars\Admin\Cms\Paragraph\CmsParagraphModel;
use Pars\Model\Cms\PageParagraph\CmsPageParagraphBeanFinder;
use Pars\Model\Cms\PageParagraph\CmsPageParagraphBeanProcessor;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanFinder;

class CmsPageParagraphModel extends CmsParagraphModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsPageParagraphBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPageParagraphBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
        $this->setBeanOrderProcessor(new BeanOrderProcessor(
            new CmsPageParagraphBeanProcessor($this->getDbAdpater()),
            new CmsPageParagraphBeanFinder($this->getDbAdpater()),
            'CmsPage_CmsParagraph_Order',
            'CmsPage_ID'
        ));
    }

    /**
     * @return array
     */
    public function getParagraph_Options()
    {
        $options = [];
        $finder = new CmsParagraphBeanFinder($this->getDbAdpater());

        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsParagraph_ID')] = $bean->get('ArticleTranslation_Name');
        }
        return $options;
    }

}
