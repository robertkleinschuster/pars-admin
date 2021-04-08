<?php


namespace Pars\Admin\Cms\Block;


class CmsSubBlockController extends CmsBlockController
{

    public function indexAction()
    {
        $this->getModel()->getBeanFinder()->getBeanLoader()->unsetValue('CmsBlock_ID');
        $this->getModel()->getBeanFinder()->setCmsBlock_ID_Parent($this->getControllerRequest()->getId()->getAttribute('CmsBlock_ID'));
        return parent::indexAction();
    }

    public function initSubcontroller()
    {

    }
}
