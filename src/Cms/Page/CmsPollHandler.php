<?php


namespace Pars\Admin\Cms\Page;


use Niceshops\Bean\Type\Base\BeanAwareInterface;
use Niceshops\Bean\Type\Base\BeanAwareTrait;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanProcessor;
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
            $paragraphFinder = new CmsParagraphBeanFinder($this->getModel()->getDbAdpater());
            $paragraphFinder->initByValueList('Article_Code', $paragraphList->column('Article_Code'));
            $beanList = $paragraphFinder->getBeanList(true);
            foreach ($beanList as $item) {
                $item->getArticle_Data()->set('poll', 0);
                $item->getArticle_Data()->set('poll_value', 0);
            }
            $paragraphProcessor = new CmsParagraphBeanProcessor($this->getModel()->getDbAdpater());
            $paragraphProcessor->setBeanList($beanList);
            $paragraphProcessor->save();
            $this->getResponse()->setRedirect($this->getPathHelper(true)->getPath());
        }
        if ($this->getRequest()->hasAttribute('show_poll')) {
            $paragraphFinder = new CmsParagraphBeanFinder($this->getModel()->getDbAdpater());
            $paragraphFinder->initByValueList('Article_Code', $paragraphList->column('Article_Code'));
            $beanList = $paragraphFinder->getBeanList(true);
            foreach ($beanList as $item) {
                $value =  $item->getArticle_Data()->get('poll');
                if ($max > 0 && $value > 0) {
                    $item->getArticle_Data()->set('poll_value', $value/$max *100);
                }
            }
            $paragraphProcessor = new CmsParagraphBeanProcessor($this->getModel()->getDbAdpater());
            $paragraphProcessor->setBeanList($beanList);
            $paragraphProcessor->save();
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
