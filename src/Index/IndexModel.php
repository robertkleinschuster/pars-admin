<?php

namespace Pars\Admin\Index;

use Pars\Admin\Base\BaseModel;
use Pars\Model\Cms\Menu\CmsMenuBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Model\Config\ConfigBeanFinder;
use Pars\Model\Localization\Locale\LocaleBeanFinder;

class IndexModel extends BaseModel
{
    public function hasAssetDomain()
    {
        $configFinder = new ConfigBeanFinder($this->getDbAdpater());
        $configFinder->setConfig_Code('asset.domain');
        if ($configFinder->count() == 1) {
            $bean = $configFinder->getBean();
            if (!$bean->empty('Config_Value')) {
                $value = $bean->get('Config_Value');
                if (strlen(trim($value))) {
                    return true;
                }
            }
        }
        return false;
    }


    public function hasPage()
    {
        return (new CmsPageBeanFinder($this->getDbAdpater()))->setCmsPageState_Code('active')->count();
    }

    public function hasStartpage()
    {
        return (new CmsPageBeanFinder($this->getDbAdpater()))->setCmsPageState_Code('active')->setArticleTranslation_Code('/')->count();
    }

    public function hasParagraph()
    {
        return (new CmsParagraphBeanFinder($this->getDbAdpater()))->setCmsParagraphState_Code('active')->count();
    }

    public function hasMenu()
    {
        return (new CmsMenuBeanFinder($this->getDbAdpater()))->setCmsMenuState_Code('active')->count();

    }

    public function hasLocale()
    {
        return (new LocaleBeanFinder($this->getDbAdpater()))->setLocale_Active(true)->count() > 1;
    }

}
