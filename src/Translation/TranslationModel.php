<?php

namespace Pars\Admin\Translation;

use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudModel;
use Pars\Model\Localization\Locale\LocaleBeanFinder;
use Pars\Model\Translation\TranslationLoader\TranslationBean;
use Pars\Model\Translation\TranslationLoader\TranslationBeanFinder;
use Pars\Model\Translation\TranslationLoader\TranslationBeanProcessor;
use Pars\Mvc\Exception\NotFoundException;

class TranslationModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new TranslationBeanFinder($this->getDbAdpater()));
        $this->getBeanFinder()->setEscapePlaceholder(true);
        $this->setBeanProcessor(new TranslationBeanProcessor($this->getDbAdpater()));
    }

    /**
     * @return array
     */
    public function getLocale_Options(): array
    {
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        $finder->setLocale_Active(true);
        return $finder->getBeanList()->getSelectOptions();
    }

    /**
     * @return BeanInterface|TranslationBean|null
     * @throws BeanException
     * @throws NotFoundException
     */
    public function getTranslationSource()
    {
        if ($this->getConfig('locale.default')) {
            $bean = $this->getBean();
            $locale = $this->getConfig('locale.default');
            $finder = new TranslationBeanFinder($this->getDbAdpater());
            $finder->setLocale_Code($locale);
            $finder->setTranslation_Code($bean->get('Translation_Code'));
            $finder->setTranslation_Namespace($bean->get('Translation_Namespace'));
            if ($finder->count() == 1) {
                return $finder->getBean();
            } else {
                $source = $finder->getBeanFactory()->getEmptyBean([]);
                $text = $this->getTranslator()->translate($bean->get('Translation_Code'), $bean->get('Translation_Namespace'), $locale);
                $source->set('Translation_Text', $text);
                $source->set('Translation_Code', $bean->get('Translation_Code'));
                $source->set('Translation_Namespace', $bean->get('Translation_Namespace'));
                return $source;
            }
        }
        return null;
    }
}
