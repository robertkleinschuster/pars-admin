<?php


namespace Pars\Admin\User;


use Niceshops\Bean\Type\Base\BeanListAwareTrait;
use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Button;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\HtmlElement;

class UserOverview extends BaseOverview
{
    use BeanListAwareTrait;

    protected function initialize()
    {

        $toolbar = new HtmlElement('div.btn-toolbar.mb-3');
        $button = new Button($this->translate('create.title'), Button::STYLE_SUCCESS);
        $button->setPath($this->getPathHelper()->setController('user')->setAction('create'));
        $toolbar->push($button);
        $this->push($toolbar);

        $badge = new Badge('{UserState_Code}');
        $badge->setFormat(new UserStateFieldFormat($this->getTranslator()));
        $this->append($badge);
        $this->addField('User_Username', $this->translate('user.username'));
        $this->addField('Person_Firstname', $this->translate('person.firstname'));
        $this->addField('Person_Lastname', $this->translate('person.lastname'));
        $this->setDetailPath($this->getPathHelper()->setAction('detail')->setId((new IdParameter())->addId('Person_ID')));
        $this->setEditPath($this->getPathHelper()->setAction('edit')->setId((new IdParameter())->addId('Person_ID')));
        $this->setDeletePath($this->getPathHelper()->setAction('delete')->setId((new IdParameter())->addId('Person_ID')));
        parent::initialize();
    }

}
