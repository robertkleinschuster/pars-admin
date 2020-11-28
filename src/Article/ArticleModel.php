<?php

namespace Pars\Admin\Article;

use Pars\Admin\Base\CrudModel;
use Pars\Model\File\FileBeanFinder;

abstract class ArticleModel extends CrudModel
{

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

}
