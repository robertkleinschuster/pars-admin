<?php


namespace Pars\Admin\Article\Picture;


use Pars\Admin\Picture\PictureModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\Picture\ArticlePictureBeanFinder;
use Pars\Model\Article\Picture\ArticlePictureBeanProcessor;
use Pars\Model\Picture\PictureBeanFinder;


class ArticlePictureModel extends PictureModel
{
    /**
     *
     */
    public function initialize()
    {
        $this->setBeanFinder(new ArticlePictureBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new ArticlePictureBeanProcessor($this->getParsContainer()));
    }


    /**
     * @param IdParameter $idParameter
     * @param array $attributes
     */
    protected function create(IdParameter $idParameter, array &$attributes): void
    {
        parent::initialize();
        parent::initializeDependencies();
        parent::create($idParameter, $attributes);
        if (!$this->getValidationHelper()->hasError()) {
            $this->initialize();
            $this->initializeDependencies();
            parent::create($idParameter, $attributes);
        }
    }

    public function getPictureBeanList()
    {
        $finder = new PictureBeanFinder($this->getDatabaseAdapter());
        return $finder->getBeanListDecorator();
    }
}
