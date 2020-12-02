<?php

namespace Pars\Admin\Cms\Page;

use Pars\Admin\Article\ArticleModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanProcessor;
use Pars\Model\Cms\Page\State\CmsPageStateBeanFinder;
use Pars\Model\Cms\Page\Type\CmsPageTypeBeanFinder;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanProcessor;

/**
 * Class CmsPageModel
 * @package Pars\Admin\Cms\Page
 */
class CmsPageModel extends ArticleModel
{

    /**
     * @inheritDoc
     */
    public function initialize()
    {
        $this->setBeanFinder(new CmsPageBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPageBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    public function getCmsPageType_Options(): array
    {
        $options = [];
        $finder = new CmsPageTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPageType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPageType_Code')] = $this->translate("cmspagetype.code." . $bean->get('CmsPageType_Code'));
        }
        return $options;
    }

    public function getCmsPageState_Options(): array
    {
        $options = [];
        $finder = new CmsPageStateBeanFinder($this->getDbAdpater());
        $finder->setCmsPageState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPageState_Code')] = $this->translate("cmspagestate.code." . $bean->get('CmsPageState_Code'));
        }
        return $options;
    }

    public function handleSubmit(SubmitParameter $submitParameter, IdParameter $idParameter, array $attribute_List)
    {
        switch ($submitParameter->getMode()) {
            case 'reset_poll':
                $paragraphFinder = new CmsParagraphBeanFinder($this->getDbAdpater());
                $paragraphFinder->initByValueList(
                    'Article_Code',
                    $this->getBean()->get('CmsParagraph_BeanList')->column('Article_Code')
                );
                $beanList = $paragraphFinder->getBeanList(true);
                foreach ($beanList as $item) {
                    $item->getArticle_Data()->set('poll', 0);
                    $item->getArticle_Data()->set('poll_value', 0);
                }
                $paragraphProcessor = new CmsParagraphBeanProcessor($this->getDbAdpater());
                $paragraphProcessor->setBeanList($beanList);
                $paragraphProcessor->save();
                break;
            case 'show_poll':
                $paragraphFinder = new CmsParagraphBeanFinder($this->getDbAdpater());
                $paragraphFinder->initByValueList(
                    'Article_Code',
                    $this->getBean()->get('CmsParagraph_BeanList')->column('Article_Code')
                );
                $max = max(array_column(
                        $this->getBean()->get('CmsParagraph_BeanList')->column('Article_Data'),
                        'poll')
                );
                $beanList = $paragraphFinder->getBeanList(true);
                foreach ($beanList as $item) {
                    $value = $item->getArticle_Data()->get('poll');
                    if ($max > 0 && $value > 0) {
                        $item->getArticle_Data()->set('poll_value', $value / $max * 100);
                    }
                }
                $paragraphProcessor = new CmsParagraphBeanProcessor($this->getDbAdpater());
                $paragraphProcessor->setBeanList($beanList);
                $paragraphProcessor->save();
                break;
        }
        parent::handleSubmit($submitParameter, $idParameter, $attribute_List);
    }


}
