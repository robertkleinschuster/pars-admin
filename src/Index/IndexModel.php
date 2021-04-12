<?php

namespace Pars\Admin\Index;

use Pars\Admin\Base\BaseModel;
use Pars\Model\Cms\Menu\CmsMenuBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\Block\CmsBlockBeanFinder;
use Pars\Model\Localization\Locale\LocaleBeanFinder;

class IndexModel extends BaseModel
{

    public function hasPage()
    {
        return (new CmsPageBeanFinder($this->getDbAdpater()))->setCmsPageState_Code('active')->count();
    }

    public function hasStartpage()
    {
        return (new CmsPageBeanFinder($this->getDbAdpater()))->setCmsPageState_Code('active')->setArticleTranslation_Code('/')->count();
    }

    public function hasBlock()
    {
        return (new CmsBlockBeanFinder($this->getDbAdpater()))->setCmsBlockState_Code('active')->count();
    }

    public function hasMenu()
    {
        return (new CmsMenuBeanFinder($this->getDbAdpater()))->setCmsMenuState_Code('active')->count();
    }

    public function hasLocale()
    {
        return (new LocaleBeanFinder($this->getDbAdpater()))->filterLocale_Active(true)->count() > 1;
    }
}
