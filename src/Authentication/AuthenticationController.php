<?php

namespace Pars\Admin\Authentication;

use Mezzio\Authentication\UserInterface;
use Pars\Admin\Base\BaseController;
use Pars\Model\Authentication\User\UserBeanFinder;

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
        $this->getSession()->regenerate();
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
            if ($this->getSession()->has('requested_path')) {
                $this->getControllerResponse()->setRedirect($this->getSession()->get('requested_path'));
                $this->getSession()->unset('requested_path');
            } else {
                $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('index')->setAction('index')->getPath());
            }
            return;
        }

        $signin = new SigninForm();
        $signin->setToken($this->generateToken('login_token'));
        $signin->setTranslator($this->getTranslator());
        $this->getView()->pushComponent($signin);
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
        $this->getSession()->clear();
        $this->getSession()->regenerate();
        return $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('auth')->setAction('login')->getPath());
    }
}
