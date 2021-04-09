<?php

namespace Pars\Admin\Base;

use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Pars\Helper\Parameter\ContextParameter;
use Pars\Helper\Path\PathHelper;
use Pars\Helper\Path\PathHelperAwareInterface;
use Pars\Model\Authentication\User\UserBean;
use Pars\Mvc\View\ComponentInterface;
use Pars\Mvc\View\HtmlInterface;

/**
 * Interface CrudComponentInterface
 * @package Pars\Admin\Base
 */
interface CrudComponentInterface extends HtmlInterface, ComponentInterface, TranslatorAwareInterface, PathHelperAwareInterface
{
    /**
     * CrudComponentInterface constructor.
     * @param PathHelper $pathHelper
     * @param TranslatorInterface $translator
     * @param UserBean $userBean
     */
    public function __construct(
        PathHelper $pathHelper,
        TranslatorInterface $translator,
        UserBean $userBean
    );

    /**
     * @return ContextParameter
     */
    public function getCurrentContext(): ContextParameter;

    /**
     * @param ContextParameter $context
     *
     * @return $this
     */
    public function setCurrentContext(ContextParameter $context);

    /**
     * @return bool
     */
    public function hasCurrentContext(): bool;

    /**
     * @return ContextParameter
     */
    public function getNextContext(): ContextParameter;

    /**
     * @param ContextParameter $nextContext
     *
     * @return $this
     */
    public function setNextContext(ContextParameter $nextContext);

    /**
     * @return bool
     */
    public function hasNextContext(): bool;
}
