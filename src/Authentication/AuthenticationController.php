<?php

namespace Pars\Admin\Authentication;

use Pars\Component\Base\Alert\Alert;
use Pars\Model\Authentication\User\UserBeanFinder;
use Pars\Admin\Base\BaseController;
use Mezzio\Authentication\UserInterface;

/**
 * Class AuthenticationController
 * @package Pars\Admin\Authentication
 */
class AuthenticationController extends BaseController
{
    protected function initView()
    {
        parent::initView();
        $layout = new SigninLayout();
        $this->getView()->setLayout($layout);
    }


    public function loginAction()
    {
        try {
            $userFinder = new UserBeanFinder($this->getModel()->getDbAdpater());
            $count = $userFinder->count();
        } catch (\Exception $ex) {
            $count = 0;
        }
        if ($count == 0) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('setup')->setAction('index')->getPath());
        }
        if ($this->getSession()->has(UserInterface::class)) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('index')->setAction('index')->getPath());
            return;
        }

        $signin = new SigninForm();
        $signin->setToken($this->generateToken('login_token'));
        $signin->setTranslator($this->getTranslator());
        $this->getView()->append($signin);
        $login_error = $this->getFlashMessanger()->getFlash('login_error');
        if ($login_error == 'credentials') {
            $signin->setError($this->translate('login.error.credentials'));
        }
        if ($login_error == 'token') {
            $signin->setError($this->translate('login.error.token'));
        }
    }

    public function logoutAction()
    {
        $this->getSession()->unset(UserInterface::class);
        return $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('auth')->setAction('login')->getPath());
    }
}
