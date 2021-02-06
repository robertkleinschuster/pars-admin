<?php


namespace Pars\Admin\Cms\Page;


use Pars\Admin\Base\BaseEdit;

class CmsPageImport extends BaseEdit
{
    protected function initialize()
    {
        $this->setMode('import');
        $this->getForm()->addFile('CmsPage_Import', '', $this->translate('cmspage.import'));
        parent::initialize();
    }


    protected function getRedirectController(): string
    {
        return 'cmspage';
    }

    protected function getRedirectIdFields(): array
    {
        return [];
    }

    protected function getRedirectAction(): string
    {
        return 'index';
    }


}
