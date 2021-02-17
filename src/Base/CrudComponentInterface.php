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
    public function __construct(PathHelper $pathHelper, TranslatorInterface $translator, UserBean $userBean);

    /**
     * @return string
     */
    public function getContext(): string;

    /**
     * @param string $context
     *
     * @return $this
     */
    public function setContext(string $context);

    /**
     * @return bool
     */
    public function hasContext(): bool;
}
