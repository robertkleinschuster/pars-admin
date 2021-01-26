<?php

namespace Pars\Admin\Article;

use Pars\Admin\Base\CrudModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\DataBean;
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

}
