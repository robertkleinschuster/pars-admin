<?php

namespace Pars\Admin\Translation;

use Pars\Admin\Base\CrudModel;
use Pars\Model\Localization\Locale\LocaleBeanFinder;
use Pars\Model\Translation\TranslationLoader\TranslationBeanFinder;
use Pars\Model\Translation\TranslationLoader\TranslationBeanProcessor;

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
        $options = [];
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        $finder->setLocale_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('Locale_Code')] = $bean->get('Locale_Name');
        }
        return $options;
    }

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
