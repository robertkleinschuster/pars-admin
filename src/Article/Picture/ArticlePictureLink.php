<?php


namespace Pars\Admin\Article\Picture;


use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Picture\PictureOverview;
use Pars\Bean\Type\Base\BeanListAwareInterface;
use Pars\Bean\Type\Base\BeanListAwareTrait;

class ArticlePictureLink extends BaseEdit implements BeanListAwareInterface
{
    use BeanListAwareTrait;

    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('link.name'));
    }


    protected function getRedirectController(): string
    {
        return 'article';
    }

    protected function getRedirectIdFields(): array
    {
        return ['Article_ID'];
    }

    protected function initFields()
    {
        parent::initFields();
        $this->setCreateBulk(true);
        $this->setCreate(false);
        $overview = new PictureOverview($this->getTranslator(), $this->getUserBean());
        $overview->setName(null);
        $overview->setShowCreate(false);
        $overview->setShowEdit(false);
        $overview->setShowLink(false);
        $overview->setShowDeleteBulk(false);
        $overview->setShowDelete(false);
        $overview->setShowMove(false);
        $overview->setShowDetail(false);
        $overview->setShowOrder(false);
        if ($this->hasBeanList()) {
            $overview->setBeanList($this->getBeanList());
        }
        $this->getForm()->push($overview);
    }

}
