<?php

namespace Pars\Admin\Base;

use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Pars\Helper\Path\PathHelper;
use Pars\Helper\Path\PathHelperAwareInterface;
use Pars\Model\Authentication\User\UserBean;

/**
 * Interface CrudComponentInterface
 * @package Pars\Admin\Base
 */
interface CrudComponentInterface extends TranslatorAwareInterface, PathHelperAwareInterface
{
    public const CONTEXT_OVERVIEW = 'overview';
    public const CONTEXT_DETAIL = 'detail';

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
     * @return string
     */
    public function getCurrentContext(): string;

    /**
     * @param string $context
     *
     * @return $this
     */
    public function setCurrentContext(string $context);

    /**
     * @return bool
     */
    public function hasCurrentContext(): bool;

    /**
     * @return string
     */
    public function getNextContext(): string;

    /**
     * @param string $nextContext
     *
     * @return $this
     */
    public function setNextContext(string $nextContext);

    /**
     * @return bool
     */
    public function hasNextContext(): bool;
}
