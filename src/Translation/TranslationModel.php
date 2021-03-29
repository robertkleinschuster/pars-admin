<?php

namespace Pars\Admin\Translation;

use Laminas\Db\Sql\Predicate\Predicate;
use Laminas\Db\Sql\Where;
use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudModel;
use Pars\Helper\Parameter\FilterParameter;
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
    public function getLocale_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        $finder->setLocale_Active(true);
        return array_merge($options, $finder->getBeanList()->getSelectOptions());
    }

    public function getNamespaceOptions(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $options['frontend'] = 'frontend';
        $options['validation'] = 'validation';
        $options['admin'] = 'admin';
        return $options;
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

    /**
     * @param FilterParameter $filterParameter
     * @return mixed|void
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    public function handleFilter(FilterParameter $filterParameter)
    {
        parent::handleFilter($filterParameter);
        if (
            $filterParameter->hasAttribute('Translation_State')
            && $filterParameter->getAttribute('Translation_State') == 'true'
        ) {
            $where = new Where();
            $where->notEqualTo('Translation_Text', 'Translation_Code', Where::TYPE_IDENTIFIER, Where::TYPE_IDENTIFIER);
            $this->getBeanFinder()->getBeanLoader()->filterValue($where);
        }
        if (
            $filterParameter->hasAttribute('Translation_State')
            && $filterParameter->getAttribute('Translation_State') == 'false'
        ) {
            $where = new Where();
            $where->equalTo('Translation_Text', 'Translation_Code', Where::TYPE_IDENTIFIER, Where::TYPE_IDENTIFIER);
            $this->getBeanFinder()->getBeanLoader()->filterValue($where);
        }
    }


}
