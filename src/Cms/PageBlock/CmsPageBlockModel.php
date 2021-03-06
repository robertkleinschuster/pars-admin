<?php

namespace Pars\Admin\Cms\PageBlock;

use Pars\Bean\Processor\BeanOrderProcessor;
use Pars\Bean\Type\Base\BeanException;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Admin\Cms\Block\CmsBlockModel;
use Pars\Helper\Parameter\FilterParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\SearchParameter;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\PageBlock\CmsPageBlockBeanFinder;
use Pars\Model\Cms\PageBlock\CmsPageBlockBeanProcessor;
use Pars\Model\Cms\Block\CmsBlockBeanFinder;
use Pars\Mvc\Exception\MvcException;

/**
 * Class CmsPageBlockModel
 * @package Pars\Admin\Cms\PageBlock
 * @method CmsPageBlockBeanFinder getBeanFinder()
 * @method CmsPageBlockBeanProcessor getBeanProcessor()
 */
class CmsPageBlockModel extends CmsBlockModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsPageBlockBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new CmsPageBlockBeanProcessor($this->getDatabaseAdapter()));
        $this->getBeanFinder()->filterLocale_Code($this->getTranslator()->getLocale());
        $this->setBeanOrderProcessor(new BeanOrderProcessor(
            new CmsPageBlockBeanProcessor($this->getDatabaseAdapter()),
            (new CmsPageBlockBeanFinder($this->getDatabaseAdapter()))->filterLocale_Code($this->getTranslator()->getLocale()),
            'CmsPage_CmsBlock_Order',
            'CmsPage_ID'
        ));
    }

    /**
     * @return array
     * @throws BeanException
     */
    public function getBlock_Options(): array
    {
        $options = [];
        $finder = new CmsBlockBeanFinder($this->getDatabaseAdapter());
        $finder->filterLocale_Code($this->getTranslator()->getLocale());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsBlock_ID')] = $bean->get('ArticleTranslation_Name');
        }
        return $options;
    }

    public function getBlockBeanFinder(?FilterParameter $filterParameter = null, ?SearchParameter $searchParameter = null)
    {
        $finder = new CmsBlockBeanFinder($this->getDatabaseAdapter());
        $finder->setCmsBlock_ID_Parent(null);
        $finder->filterLocale_Code($this->getTranslator()->getLocale());
        if ($filterParameter) {
            $finder->filter($filterParameter->getAttributes());
        }
        if ($searchParameter && $searchParameter->hasText()) {
            $finder->search($searchParameter->getText());
        }
        return $finder;
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
            $finder = new CmsPageBeanFinder($this->getDatabaseAdapter());
            $finder->filterLocale_Code($this->getTranslator()->getLocale());
            $finder->setCmsPage_ID($data['CmsPage_ID']);
            $page = $finder->getBean();
            $count = $page->get('CmsBlock_BeanList')->count() + 1;
            $bean->set('Article_Code', $page->get('Article_Code') . '-' . $count);
            $bean->set('ArticleTranslation_Code', $page->get('ArticleTranslation_Code') . '-' . $count);
            $bean->set('ArticleTranslation_Name', $page->get('ArticleTranslation_Name'));
        }
        return $bean;
    }
}
