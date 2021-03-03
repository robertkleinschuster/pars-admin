<?php

namespace Pars\Admin\Setup;

use Pars\Admin\User\UserEdit;
use Pars\Component\Base\Alert\Alert;
use Pars\Component\Base\Field\Headline;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Form\Form;

class Setup extends UserEdit
{
    protected function initialize()
    {
        parent::initialize();
        $this->getForm()->setBackground(Form::BACKGROUND_LIGHT);
        $this->getForm()->setRounded(Form::ROUNDED_NONE);
        $this->getForm()->setShadow(Form::SHADOW_LARGE);
        $this->getForm()->setColor(Form::COLOR_DARK);
        $this->getForm()->addOption('py-4');
        $this->getForm()->addOption('px-sm-5');
        $this->getForm()->addOption('px-3');
        $this->getForm()->addInlineStyle('max-width', '750px');
        $this->getForm()->addOption('mx-auto');
        $icon = new Icon('pars-logo');
        $icon->addInlineStyle('max-width', '200px');
        $icon->addInlineStyle('fill', '#343a40');
        $icon->addOption('mx-auto');
        $icon->addOption('mb-3');
        $this->getForm()->push($icon);
        $this->getForm()->push(new Headline($this->translate('setup')));
        if ($this->getValidationHelper()->hasError('error')) {
            $alert = new Alert(
                $this->getValidationHelper()->getSummary('error'),
                $this->getValidationHelper()->getSummary('errorDetails')
            );
            $this->getElementList()->unshift($alert);
        }
    }
}
