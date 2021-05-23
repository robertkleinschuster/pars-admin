<?php

namespace Pars\Admin\Article;

use Pars\Bean\Type\Base\BeanException;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\DataBean;
use Pars\Model\Article\Translation\ArticleTranslationBeanFinder;
use Pars\Model\Cms\Block\CmsBlockBeanFinder;
use Pars\Model\File\FileBeanFinder;
use Pars\Model\File\FileBeanList;
use Pars\Model\Localization\Locale\LocaleBeanFinder;
use Pars\Model\Localization\Locale\LocaleBeanList;
use Pars\Model\Translation\TranslationLoader\TranslationBeanFinder;

/**
 * Class ArticleModel
 * @package Pars\Admin\Article
 *
 */
abstract class ArticleModel extends CrudModel
{

    /**
     * @param IdParameter $idParameter
     * @param array $attributes
     */
    protected function create(IdParameter $idParameter, array &$attributes): void
    {
        $attributes['Article_Data']['__class'] = DataBean::class;
        $attributes['Article_Data']['author'] = $this->getUserBean()->User_Displayname;
        parent::create($idParameter, $attributes);
    }

    /**
     * @param array $attributes
     */
    protected function save(array $attributes): void
    {
        $attributes['Article_Data']['__class'] = DataBean::class;
        $attributes['Article_Data']['editor'] = $this->getUserBean()->User_Displayname;
        parent::save($attributes);
    }

    /**
     * @return array
     * @throws BeanException
     */
    public function getFileOptions(): array
    {
        $options = [];
        $finder = new FileBeanFinder($this->getDbAdpater());
        $options[null] = $this->translate('noselection');
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('File_ID')] = $bean->get('File_Name');
        }
        return $options;
    }

    /**
     * @return FileBeanList
     */
    public function getFileBeanList(): FileBeanList
    {
        $finder = new FileBeanFinder($this->getDbAdpater());
        return $finder->getBeanList();
    }

    public function getPlaceholderOptions()
    {
        $options = [];
        $finder = new CmsBlockBeanFinder($this->getDbAdpater());
        $finder->filterLocale_Code($this->getUserBean()->getLocale()->getLocale_Code());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options["{block:{$bean->Article_Code}}"] = "{$bean->ArticleTranslation_Name} ({$bean->Article_Code})";
        }
        $finder = new TranslationBeanFinder($this->getDbAdpater());
        $finder->filterLocale_Code($this->getUserBean()->getLocale()->getLocale_Code());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options["{translation:{$bean->Translation_Code}}"] = "{$bean->Translation_Text} ({$bean->Translation_Code})";
        }
        return $options;
    }

    /**
     * @return LocaleBeanList
     */
    public function getLocale_List(): LocaleBeanList
    {
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        $finder->filterLocale_Active(true);
        return $finder->getBeanList();
    }

    /**
     * @param BeanInterface $bean
     * @throws BeanException
     */
    public function loadTranslationDefaults(BeanInterface $bean): void
    {
        $default = (new ArticleTranslationBeanFinder($this->getDbAdpater()))
            ->setArticle_ID($bean->Article_ID)
            ->filterLocale_Code($this->getConfigValue('locale.default'))
            ->limit(1, 0)->getBean();

        if ($bean->empty('ArticleTranslation_Code') && $default->isset('ArticleTranslation_Code')) {
            $bean->set('ArticleTranslation_Code', $default->get('ArticleTranslation_Code'));
        }
        if ($bean->empty('ArticleTranslation_Host') && $default->isset('ArticleTranslation_Host')) {
            $bean->set('ArticleTranslation_Host', $default->get('ArticleTranslation_Host'));
        }
        if ($bean->empty('ArticleTranslation_Active') && $default->isset('ArticleTranslation_Active')) {
            $bean->set('ArticleTranslation_Active', $default->get('ArticleTranslation_Active'));
        }
        if ($bean->empty('ArticleTranslation_Name') && $default->isset('ArticleTranslation_Name')) {
            $bean->set('ArticleTranslation_Name', $default->get('ArticleTranslation_Name'));
        }
        if ($bean->empty('ArticleTranslation_Title') && $default->isset('ArticleTranslation_Title')) {
            $bean->set('ArticleTranslation_Title', $default->get('ArticleTranslation_Title'));
        }
        if ($bean->empty('ArticleTranslation_Keywords') && $default->isset('ArticleTranslation_Keywords')) {
            $bean->set('ArticleTranslation_Keywords', $default->get('ArticleTranslation_Keywords'));
        }
        if ($bean->empty('ArticleTranslation_Heading') && $default->isset('ArticleTranslation_Heading')) {
            $bean->set('ArticleTranslation_Heading', $default->get('ArticleTranslation_Heading'));
        }
        if ($bean->empty('ArticleTranslation_SubHeading') && $default->isset('ArticleTranslation_SubHeading')) {
            $bean->set('ArticleTranslation_SubHeading', $default->get('ArticleTranslation_SubHeading'));
        }
        if ($bean->empty('ArticleTranslation_Path')  && $default->isset('ArticleTranslation_Path')) {
            $bean->set('ArticleTranslation_Path', $default->get('ArticleTranslation_Path'));
        }
        if ($bean->empty('ArticleTranslation_Teaser') && $default->isset('ArticleTranslation_Teaser')) {
            $bean->set('ArticleTranslation_Teaser', $default->get('ArticleTranslation_Teaser'));
        }
        if ($bean->empty('ArticleTranslation_Text') && $default->isset('ArticleTranslation_Text')) {
            $bean->set('ArticleTranslation_Text', $default->get('ArticleTranslation_Text'));
        }
        if ($bean->empty('ArticleTranslation_Footer') && $default->isset('ArticleTranslation_Footer')) {
            $bean->set('ArticleTranslation_Footer', $default->get('ArticleTranslation_Footer'));
        }
        if ($bean->empty('File_ID') && $default->isset('File_ID')) {
            $bean->set('File_ID', $default->get('File_ID'));
        }
    }

    public function getEmptyBean(array $data = []): BeanInterface
    {
        $bean = parent::getEmptyBean($data);
        $bean->set('Article_Data', new DataBean());
        return $bean;
    }
}
