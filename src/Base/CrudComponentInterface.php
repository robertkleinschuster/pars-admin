<?php

namespace Pars\Admin\Base;

use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareInterface;
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
interface CrudComponentInterface extends HtmlInterface, ComponentInterface, PathHelperAwareInterface, ParsTranslatorAwareInterface
{
    /**
     * CrudComponentInterface constructor.
     * @param PathHelper $pathHelper
     * @param ParsTranslator $translator
     * @param UserBean $userBean
     */
    public function __construct(
        PathHelper $pathHelper,
        ParsTranslator $translator,
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
