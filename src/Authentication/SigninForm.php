<?php

namespace Pars\Admin\Authentication;

use Pars\Component\Base\Alert\Alert;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Field\Paragraph;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Form\Form;
use Pars\Core\Translation\ParsTranslatorAwareTrait;

class SigninForm extends Form
{
    use ParsTranslatorAwareTrait;

    public ?string $token = null;
    public ?string $error = null;
    public ?string $signupPath = null;

    protected function initialize()
    {
        $this->setBackground(Form::BACKGROUND_LIGHT);
        $this->setRounded(Form::ROUNDED_NONE);
        $this->setShadow(Form::SHADOW_LARGE);
        $this->setColor(Form::COLOR_DARK);
        $this->addOption('py-4');
        $this->addOption('px-sm-5');
        $this->addOption('px-3');
        $this->addInlineStyle('max-width', '450px');
        $this->addOption('mx-auto');
        $icon = new Icon('pars-logo');
        $icon->addInlineStyle('max-width', '200px');
        $icon->addInlineStyle('fill', '#343a40');
        $icon->addOption('mx-auto');
        $icon->addOption('mb-3');
        $this->push($icon);
        $username = $this->addText('login_username', '', $this->translate('login.username'));
        $password = $this->addPassword('login_password', '', $this->translate('login.password'));
        if ($this->hasError()) {
            $alert = new Alert();
            $alert->setHeading($this->translate('login.error'));
            $this->push($alert);
            $username->setError($this->getError());
            $password->setError($this->getError());
        }

        if ($this->hasToken()) {
            $this->addHidden('login_token', $this->getToken());
        }
        $this->addSubmit('signin', $this->translate('login.submit'), 'sigin');

        parent::initialize();

        if ($this->hasSignupPath()) {
            $block = new Paragraph();
            $span = new Span($this->translate('login.register.label') . ' ');
            $block->push($span);
            $span = new Span($this->translate('login.register.link.label'));
            $span->setColor(Span::COLOR_PRIMARY);
            $span->setPath($this->getSignupPath());
            $block->push($span);
            $this->push($block);
        }
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return $this
     */
    public function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return isset($this->error);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasToken(): bool
    {
        return isset($this->token);
    }

    /**
     * @return string
     */
    public function getSignupPath(): string
    {
        return $this->signupPath;
    }

    /**
     * @param string $signupPath
     *
     * @return $this
     */
    public function setSignupPath(string $signupPath): self
    {
        $this->signupPath = $signupPath;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSignupPath(): bool
    {
        return isset($this->signupPath);
    }
}
