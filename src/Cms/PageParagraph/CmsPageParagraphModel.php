<?php

namespace Pars\Admin\Cms\PageParagraph;

use Pars\Admin\Base\CrudModel;
use Pars\Admin\Cms\Paragraph\CmsParagraphModel;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Model\Cms\PageParagraph\CmsPageParagraphBeanFinder;
use Pars\Model\Cms\PageParagraph\CmsPageParagraphBeanProcessor;

class CmsPageParagraphModel extends CmsParagraphModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsPageParagraphBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPageParagraphBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
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
