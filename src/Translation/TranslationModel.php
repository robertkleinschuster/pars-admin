<?php

namespace Pars\Admin\Translation;

use Pars\Admin\Base\CrudModel;
use Pars\Model\Localization\Locale\LocaleBeanFinder;
use Pars\Model\Translation\TranslationLoader\TranslationBeanFinder;
use Pars\Model\Translation\TranslationLoader\TranslationBeanProcessor;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;

class TranslationModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new TranslationBeanFinder($this->getDbAdpater()));
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

    /**
     * @param SubmitParameter $submitParameter
     * @param IdParameter $idParameter
     * @param array $attribute_List
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    public function handleSubmit(SubmitParameter $submitParameter, IdParameter $idParameter, array $attribute_List)
    {
        parent::handleSubmit($submitParameter, $idParameter, $attribute_List);
        $this->getTranslator()->clearCache($attributes['Translation_Namespace'] ?? 'default', $attributes['Locale_Code'] ?? $this->getTranslator()->getLocale());
    }
}
