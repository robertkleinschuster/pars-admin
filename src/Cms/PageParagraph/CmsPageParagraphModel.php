<?php

namespace Pars\Admin\Cms\PageParagraph;

use Niceshops\Bean\Processor\BeanOrderProcessor;
use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Cms\Paragraph\CmsParagraphModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\PageParagraph\CmsPageParagraphBeanFinder;
use Pars\Model\Cms\PageParagraph\CmsPageParagraphBeanProcessor;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanList;
use Pars\Mvc\Exception\MvcException;

/**
 * Class CmsPageParagraphModel
 * @package Pars\Admin\Cms\PageParagraph
 * @method CmsPageParagraphBeanFinder getBeanFinder()
 * @method CmsPageParagraphBeanProcessor getBeanProcessor()
 */
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
     * @throws BeanException
     */
    public function getParagraph_Options(): array
    {
        $options = [];
        $finder = new CmsParagraphBeanFinder($this->getDbAdpater());
        $finder->setLocale_Code($this->getTranslator()->getLocale());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsParagraph_ID')] = $bean->get('ArticleTranslation_Name');
        }
        return $options;
    }

    /**
     * @return CmsParagraphBeanList
     */
    public function getParagraphBeanList(): CmsParagraphBeanList
    {
        $finder = new CmsParagraphBeanFinder($this->getDbAdpater());
        $finder->setLocale_Code($this->getTranslator()->getLocale());
        return $finder->getBeanList();
    }

    /**
     * @param IdParameter $idParameter
     * @param array $attributes
     */
    protected function create(IdParameter $idParameter, array &$attributes): void
    {
        parent::initialize();
        parent::initializeDependencies();
        parent::create($idParameter, $attributes);
        if (!$this->getValidationHelper()->hasError()) {
            $this->initialize();
            $this->initializeDependencies();
            parent::create($idParameter, $attributes);
        }
    }

    /**
     * @param array $data
     * @return BeanInterface
     * @throws BeanException
     * @throws MvcException
     */
    public function getEmptyBean(array $data = []): BeanInterface
    {
        $bean = parent::getEmptyBean($data);
        if (isset($data['CmsPage_ID'])) {
            $finder = new CmsPageBeanFinder($this->getDbAdpater());
            $finder->setLocale_Code($this->getTranslator()->getLocale());
            $finder->setCmsPage_ID($data['CmsPage_ID']);
            $page = $finder->getBean();
            $count = $page->get('CmsParagraph_BeanList')->count() + 1;
            $bean->set('Article_Code', $page->get('Article_Code') . '-' . $count);
            $bean->set('ArticleTranslation_Code', $page->get('ArticleTranslation_Code') . '-' . $count);
            $bean->set('ArticleTranslation_Name', $page->get('ArticleTranslation_Name'));
        }
        return $bean;
    }

}
