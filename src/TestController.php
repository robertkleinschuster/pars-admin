<?php

namespace Pars\Admin;

use Pars\Component\Base\Alert\Alert;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Field\Paragraph;
use Pars\Component\Base\Table\Table;
use Pars\Component\Base\View\BaseView;
use Pars\Component\Base\Layout\DashboardLayout;
use Pars\Component\Signin\SigninForm;
use Pars\Component\Signin\SigninLayout;
use Pars\Component\User\UserDelete;
use Pars\Component\User\UserDetail;
use Pars\Component\User\UserEdit;
use Pars\Component\User\UserOverview;
use Pars\Helper\Validation\ValidationHelper;
use Pars\Model\Authentication\User\UserBean;
use Pars\Model\Authentication\User\UserBeanList;

class TestController extends \Pars\Mvc\Controller\AbstractController
{
    protected function initView()
    {
        $this->setView(new BaseView());
        $layout = new DashboardLayout();
        $logo = new Icon('pars-logo');
        $logo->setWidth('100px');
        $logo->addInlineStyle('fill', '#fff');
        $logo->addInlineStyle('margin-top', '-7px');
        $layout->getNavigation()->setBrand('', '#')->push($logo);
        $layout->getNavigation()->addItem('Cms', '#', 'cms');
        $layout->getNavigation()->setActive('cms');
        $layout->getSubNavigation()->addItem('Menü', '#', 'menu');
        $layout->getSubNavigation()->addItem('Seiten', '#', 'site');
        $layout->getSubNavigation()->addItem('Absätze', '#', 'paragraph');
        $layout->getSubNavigation()->setActive('menu');
        $this->getView()->setLayout($layout);
    }

    public function detailAction()
    {
        $bean = new UserBean();
        $bean->Person_Firstname = 'Robert';
        $bean->Person_Lastname = 'Kleinschuster';
        $bean->User_Username = 'robert';
        $bean->User_Displayname = 'robert';
        $bean->UserState_Code = 'active';

        $userDetail = new UserDetail();
        $userDetail->setBean($bean);
        $this->getView()->append($userDetail);
       /* $jumbotron = new Jumbotron();
        $jumbotron->setHeadline('Headline');
        $jumbotron->setLead('Lead text...');
        $jumbotron->getFieldList()->push(new Span('Max Mustermann', 'Name'));
        $jumbotron->getFieldList()->push(new Span('Admin', 'Gruppe' ));
        $elem = new BadgeGroup(null, 'Badges');
        $elem->append(new Badge('Eigenschaft'));
        $elem->append(new Badge('Test', Badge::STYLE_DANGER));
        $elem->append(new Badge('Aktiv', Badge::STYLE_SUCCESS));
        $jumbotron->getFieldList()->push($elem);
        $this->getView()->append($jumbotron);*/
    }

    public function testAction()
    {
        $list = new UserBeanList();

        $bean = new UserBean();
        $bean->set('username', 'muster0');
        $list->push($bean);
        $bean = new UserBean();
        $bean->set('username', 'muster1');
        $list->push($bean);
        $bean = new UserBean();
        $bean->set('username', 'muster2');
        $list->push($bean);
        $table = new Table();
        $table->setBeanList($list);
        $p = new Paragraph();
        $p->setLabel('username');
        $p->setContent('{username}');
        $table->push($p);
        $p = new Paragraph();
        $icon = new Icon();
        $icon->setName(Icon::ICON_AIRPLAY);
        $p->setLabel('username');
        $p->setContent('{username}');
        $p->setPath('asdf');
        $table->push($icon);
        $table->push($p);
        $this->getView()->append($table);
    }

    public function error(\Throwable $exception)
    {
        $alert = new Alert();
        $alert->setStyle(Alert::STYLE_DANGER);
        $alert->setHeading('Fehler');
        $alert->addParagraph($exception->getMessage());
        $this->getView()->prepend($alert);
    }


    public function formAction()
    {
        $bean = new UserBean();
        $bean->Person_Firstname = 'Robert';
        $bean->Person_Lastname = 'Kleinschuster';
        $bean->User_Username = 'robert';
        $bean->User_Displayname = 'robert';
        $bean->UserState_Code = 'active';

        $userDetail = new UserEdit();
        $userDetail->setBean($bean);
        $this->getView()->append($userDetail);
    }


    public function overviewAction()
    {
        $bean = new UserBean();
        $bean->Person_Firstname = 'Robert';
        $bean->Person_Lastname = 'Kleinschuster';
        $bean->User_Username = 'robert';
        $bean->User_Displayname = 'robert';
        $bean->UserState_Code = 'active';
        $beanlist = new UserBeanList();
        $beanlist->push($bean);

        $userOverview = new UserOverview();
        $userOverview->setDetailPath('#');
        $userOverview->setEditPath('#');
        $userOverview->setDeletePath('#');
        $userOverview->setMoveUpPath('#');
        $userOverview->setMoveDownPath('#');

        $userOverview->setBeanList($beanlist);
        $this->getView()->append($userOverview);
    }

    public function loginAction() {
        $this->getView()->setLayout(new SigninLayout());
        $components = $this->getView()->getLayout()->getComponentList();
        $form = new SigninForm();
        $components->push($form);
    }

    public function deleteAction()
    {
        $delete = new UserDelete();
        $delete->setHeading('Löschen');
        $delete->setText('Löschen text');
        $this->getView()->append($delete);
    }


    protected function initModel()
    {
        // TODO: Implement initModel() method.
    }

    protected function handleSubmitSecurity(): bool
    {
        return true;
    }

    protected function handleValidationError(ValidationHelper $validationHelper)
    {
        // TODO: Implement handleValidationError() method.
    }

    protected function handleNavigationState(string $id, int $index)
    {
        // TODO: Implement handleNavigationState() method.
    }


}
