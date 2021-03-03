<?php

namespace Pars\Admin\Cms\Page;

use Niceshops\Bean\Type\Base\BeanAwareInterface;
use Niceshops\Bean\Type\Base\BeanAwareTrait;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Model\Cms\Block\CmsBlockBeanFinder;
use Pars\Model\Cms\Block\CmsBlockBeanProcessor;
use Pars\Mvc\Controller\ControllerRequest;
use Pars\Mvc\Controller\ControllerResponse;

class CmsPollHandler implements BeanAwareInterface
{
    use BeanAwareTrait;

    private ControllerRequest $request;
    private ControllerResponse $response;

    /**
     * CmsPollHandler constructor.
     * @param ControllerRequest $request
     * @param ControllerResponse $response
     */
    public function __construct(ControllerRequest $request, ControllerResponse $response, BeanInterface $bean)
    {
        $this->request = $request;
        $this->response = $response;
        $this->setBean($bean);
    }


    public function handle()
    {
        if ($this->getRequest()->hasAttribute('reset_poll')) {
            $blockFinder = new CmsBlockBeanFinder($this->getModel()->getDbAdpater());
            $blockFinder->initByValueList('Article_Code', $blockList->column('Article_Code'));
            $beanList = $blockFinder->getBeanList(true);
            foreach ($beanList as $item) {
                $item->getArticle_Data()->set('poll', 0);
                $item->getArticle_Data()->set('poll_value', 0);
            }
            $blockProcessor = new CmsBlockBeanProcessor($this->getModel()->getDbAdpater());
            $blockProcessor->setBeanList($beanList);
            $blockProcessor->save();
            $this->getResponse()->setRedirect($this->getPathHelper(true)->getPath());
        }
        if ($this->getRequest()->hasAttribute('show_poll')) {
            $blockFinder = new CmsBlockBeanFinder($this->getModel()->getDbAdpater());
            $blockFinder->initByValueList('Article_Code', $blockList->column('Article_Code'));
            $beanList = $blockFinder->getBeanList(true);
            foreach ($beanList as $item) {
                $value = $item->getArticle_Data()->get('poll');
                if ($max > 0 && $value > 0) {
                    $item->getArticle_Data()->set('poll_value', $value / $max * 100);
                }
            }
            $blockProcessor = new CmsBlockBeanProcessor($this->getModel()->getDbAdpater());
            $blockProcessor->setBeanList($beanList);
            $blockProcessor->save();
            $this->getResponse()->setRedirect($this->getPathHelper(true)->getPath());
        }
    }

    /**
     * @return ControllerRequest
     */
    public function getRequest(): ControllerRequest
    {
        return $this->request;
    }

    /**
     * @return ControllerResponse
     */
    public function getResponse(): ControllerResponse
    {
        return $this->response;
    }
}
