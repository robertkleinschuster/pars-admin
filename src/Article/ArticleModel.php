<?php

namespace Pars\Admin\Article;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\DataBean;
use Pars\Model\Article\Translation\ArticleTranslationBeanFinder;
use Pars\Model\File\FileBeanFinder;
use Pars\Model\Localization\Locale\LocaleBeanFinder;

abstract class ArticleModel extends CrudModel
{

    protected function create(IdParameter $idParameter, array &$attributes): void
    {
        $attributes['Article_Data']['__class'] = DataBean::class;
        $attributes['Article_Data']['author'] = $this->getUserBean()->User_Displayname;
        parent::create($idParameter, $attributes);
    }


    protected function save(array $attributes): void
    {
        $attributes['Article_Data']['__class'] = DataBean::class;
        $attributes['Article_Data']['editor'] = $this->getUserBean()->User_Displayname;
        parent::save($attributes);
    }


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
     * @return \Niceshops\Bean\Type\Base\BeanListInterface
     */
    public function getLocale_List()
    {
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        $finder->setLocale_Active(true);
        return $finder->getBeanList();
    }

    public function getTranslationDefaults(BeanInterface $bean)
    {
        $default = (new ArticleTranslationBeanFinder($this->getDbAdpater()))
            ->setArticle_ID($bean->Article_ID)
            ->setLocale_Code($this->getConfig('locale.default'))
            ->limit(1, 0)->getBean();

        if ($bean->empty('ArticleTranslation_Code')) {
            $bean->set('ArticleTranslation_Code', $default->get('ArticleTranslation_Code'));
        }
        if ($bean->empty('ArticleTranslation_Host')) {
            $bean->set('ArticleTranslation_Host', $default->get('ArticleTranslation_Host'));
        }
        if ($bean->empty('ArticleTranslation_Active')) {
            $bean->set('ArticleTranslation_Active', $default->get('ArticleTranslation_Active'));
        }
        if ($bean->empty('ArticleTranslation_Name')) {
            $bean->set('ArticleTranslation_Name', $default->get('ArticleTranslation_Name'));
        }
        if ($bean->empty('ArticleTranslation_Title')) {
            $bean->set('ArticleTranslation_Title', $default->get('ArticleTranslation_Title'));
        }
        if ($bean->empty('ArticleTranslation_Keywords')) {
            $bean->set('ArticleTranslation_Keywords', $default->get('ArticleTranslation_Keywords'));
        }
        if ($bean->empty('ArticleTranslation_Heading')) {
            $bean->set('ArticleTranslation_Heading', $default->get('ArticleTranslation_Heading'));
        }
        if ($bean->empty('ArticleTranslation_SubHeading')) {
            $bean->set('ArticleTranslation_SubHeading', $default->get('ArticleTranslation_SubHeading'));
        }
        if ($bean->empty('ArticleTranslation_Path')) {
            $bean->set('ArticleTranslation_Path', $default->get('ArticleTranslation_Path'));
        }
        if ($bean->empty('ArticleTranslation_Teaser')) {
            $bean->set('ArticleTranslation_Teaser', $default->get('ArticleTranslation_Teaser'));
        }
        if ($bean->empty('ArticleTranslation_Text')) {
            $bean->set('ArticleTranslation_Text', $default->get('ArticleTranslation_Text'));
        }
        if ($bean->empty('ArticleTranslation_Footer')) {
            $bean->set('ArticleTranslation_Footer', $default->get('ArticleTranslation_Footer'));
        }
        if ($bean->empty('File_ID')) {
            $bean->set('File_ID', $default->get('File_ID'));
        }
    }


}
