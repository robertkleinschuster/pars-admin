<?php


namespace Pars\Admin\Base;


use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Pars\Helper\Path\PathHelper;
use Pars\Helper\Path\PathHelperAwareInterface;
use Pars\Model\Authentication\User\UserBean;

interface CrudComponentInterface extends TranslatorAwareInterface, PathHelperAwareInterface
{
    public function __construct(PathHelper $pathHelper, TranslatorInterface $translator, UserBean $userBean);

}
