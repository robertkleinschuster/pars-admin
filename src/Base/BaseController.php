<?php

namespace Pars\Admin\Base;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Profiler\ProfilerInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfGuardInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Mezzio\Session\LazySession;
use Mezzio\Session\SessionMiddleware;
use Niceshops\Bean\Converter\BeanConverterAwareInterface;
use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Attribute\AttributeAwareInterface;
use Niceshops\Core\Attribute\AttributeAwareTrait;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Niceshops\Core\Option\OptionAwareInterface;
use Niceshops\Core\Option\OptionAwareTrait;
use Pars\Component\Base\Alert\Alert;
use Pars\Component\Base\Layout\DashboardLayout;
use Pars\Component\Base\View\BaseView;
use Pars\Core\Database\DatabaseMiddleware;
use Pars\Core\Logging\LoggingMiddleware;
use Pars\Core\Translation\TranslatorMiddleware;
use Pars\Helper\Validation\ValidationHelper;
use Pars\Model\Authentication\User\UserBean;
use Pars\Mvc\Controller\AbstractController;
use Pars\Mvc\Controller\ControllerResponse;
use Pars\Mvc\Controller\ControllerResponseInjector;
use Pars\Mvc\View\ViewBeanConverter;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class BaseController
 * @package Pars\Admin\Controller
 * @method BaseModel getModel()
 */
abstract class BaseController extends AbstractController implements AttributeAwareInterface, OptionAwareInterface
{
    use AttributeAwareTrait;
    use OptionAwareTrait;


    public const ATTRIBUTE_CREATE_PERMISSION = 'create_permission';
    public const ATTRIBUTE_EDIT_PERMISSION = 'edit_permission';
    public const ATTRIBUTE_DELETE_PERMISSION = 'delete_permission';

    public const FLASH_PREVIOUS_ATTRIBUTES = 'previousAttributes';
    public const FLASH_VALIDATION_ERROR = 'validationErrorMap';

    /**
     * @return mixed|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws BeanException
     */
    protected function initView()
    {
        $view = new BaseView();
        $this->setView($view);
        $view->setBeanConverter(new ViewBeanConverter());
        $layout = new DashboardLayout();
        $navigation = new MainNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $layout->setNavigation($navigation);
        $this->getView()->setLayout($layout);
        $this->getView()->set('Current_Person_ID', $this->getUserBean()->Person_ID);
        $this->getView()->set('Current_User_Username', $this->getUserBean()->User_Username);
        $this->getView()->set('Current_User_Displayname', $this->getUserBean()->User_Displayname);
        $this->getView()->set('Current_Person_Firstname', $this->getUserBean()->Person_Firstname);
        $this->getView()->set('Current_Person_Lastname', $this->getUserBean()->Person_Lastname);
    }

    /**
     * @return mixed|void
     */
    protected function initModel()
    {
        $this->getModel()->setDbAdapter(
            $this->getControllerRequest()->getServerRequest()->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE)
        );
        $this->getModel()->setUserBean($this->getUserBean());
        $this->getModel()->setTranslator($this->getTranslator());
        $this->getModel()->setLogger($this->getLogger());
        $view = $this->getView();
        if ($view instanceof BeanConverterAwareInterface && $view->hasBeanConverter()) {
            $converter = $view->getBeanConverter();
        } else {
            $converter = new ViewBeanConverter();
        }
        $converter->setTimezone($this->getModel()->getConfig('admin.timezone'));
        $this->getModel()->setBeanConverter($converter);
        $this->getModel()->initialize();
        $this->getModel()->initializeDependencies();
        $layout = $this->getView()->getLayout();
        if ($layout instanceof DashboardLayout) {
            if ($layout->hasNavigation()) {
                $layout->getNavigation()->key = $this->getModel()->getConfig('asset.key');
            }
        }
        $this->getView()->set('language', $this->getTranslator()->getLocale());
        $this->getView()->set('title', $this->getModel()->getConfig('admin.title'));
        $this->getView()->set('author', $this->getModel()->getConfig('admin.author'));
        $this->getView()->set('favicon', $this->getModel()->getConfig('admin.favicon'));
        $this->getView()->set('description', $this->getModel()->getConfig('admin.description'));
        $this->getView()->set('charset', $this->getModel()->getConfig('admin.charset'));
    }


    /**
     * @param Throwable $exception
     * @return mixed|void
     */
    public function error(Throwable $exception)
    {
        $this->handleErrorLog($exception);
        $this->handleErrorMessage($exception);
        $this->handleErrorTransaction();
    }

    /**
     * @return mixed|void
     * @throws BeanException
     */
    public function unauthorized()
    {
        $this->getView()->append(
            new Alert(
                $this->translate('unauthorized.heading'),
                $this->translate('unauthorized.text')
            )
        );
    }

    /**
     * @param Throwable $exception
     * @return mixed|void
     * @throws BeanException
     */
    public function notfound(Throwable $exception)
    {
        if ($this->hasView()) {
            $alert = new Alert(
                $this->translate('notfound.heading'),
                $this->translate('notfound.text')
            );
            $alert->addParagraph($exception->getMessage());
            $this->getView()->append($alert);
        } else {
            parent::notfound($exception);
        }
    }

    /**
     * @return mixed|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    public function finalize()
    {
        parent::finalize();
        $this->initializePermissionErrors();
        $this->initializeProfiler();
        $this->injectHideModal();
        $this->injectNavigation();
    }

    /**
     * @param string $id
     * @param int $index
     * @return mixed|void
     */
    protected function handleNavigationState(string $id, int $index)
    {
        $this->getSession()->set($id, $index);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getNavigationState(string $id): int
    {
        return (int)$this->getSession()->get($id, 1);
    }

    /**
     * @param ValidationHelper $validationHelper
     * @return mixed|void
     */
    protected function handleValidationError(ValidationHelper $validationHelper)
    {
        $this->getFlashMessanger()->flash(
            self::FLASH_PREVIOUS_ATTRIBUTES,
            $this->getControllerRequest()->getAttributes()
        );
        $this->getFlashMessanger()->flash(
            self::FLASH_VALIDATION_ERROR,
            $validationHelper->getErrorFieldMap()
        );
    }

    /**
     * @return bool
     * @throws AttributeNotFoundException
     */
    protected function handleSubmitSecurity(): bool
    {
        if ($this->getControllerRequest()->hasAttribute('submit_token')) {
            return $this->validateToken(
                'submit_token',
                $this->getControllerRequest()->getAttribute('submit_token') ?? ''
            );
        } else {
            return false;
        }
    }

    /**
     * @return TranslatorInterface
     */
    protected function getTranslator(): TranslatorInterface
    {
        return $this->getControllerRequest()
            ->getServerRequest()
            ->getAttribute(TranslatorMiddleware::TRANSLATOR_ATTRIBUTE);
    }

    /**
     * @return CsrfGuardInterface
     */
    protected function getGuard(): CsrfGuardInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
    }

    /**
     * @return FlashMessagesInterface
     */
    public function getFlashMessanger(): FlashMessagesInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
    }

    /**
     * @return LazySession
     */
    protected function getSession(): LazySession
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
    }

    /**
     * @return UserBean
     */
    protected function getUserBean(): UserBean
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(UserInterface::class) ?? new UserBean();
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(LoggingMiddleware::LOGGER_ATTRIBUTE);
    }


    /**
     * @param string $code
     * @return string
     */
    protected function translate(string $code)
    {
        return $this->getTranslator()->translate($code, 'admin');
    }


    /**
     * @param string $name
     * @return string
     */
    protected function generateToken(string $name): string
    {
        if (!$this->getSession()->has($name)) {
            return $this->getGuard()->generateToken($name);
        } else {
            return $this->getSession()->get($name);
        }
    }


    /**
     * @param string $name
     * @param $token
     * @return bool
     */
    protected function validateToken(string $name, $token): bool
    {
        $result = $this->getGuard()->validateToken($token, $name);
        $this->generateToken($name);
        return $result;
    }

    /**
     * @return array
     */
    protected function getValidationErrorMap(): array
    {
        return $this->getFlashMessanger()->getFlash(self::FLASH_VALIDATION_ERROR, []);
    }

    /**
     * @return array
     */
    protected function getPreviousAttributes(): array
    {
        return $this->getFlashMessanger()->getFlash(self::FLASH_PREVIOUS_ATTRIBUTES, []);
    }

    /**
     * @param string $permission
     * @return bool
     */
    protected function checkPermission(string $permission): bool
    {
        return $this->getUserBean()->hasPermission($permission);
    }


    /**
     * @param string $create
     * @param string $edit
     * @param string $delete
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    public function setPermissions(string $create, string $edit, string $delete)
    {
        $this->setAttribute(self::ATTRIBUTE_CREATE_PERMISSION, $create);
        $this->setAttribute(self::ATTRIBUTE_EDIT_PERMISSION, $edit);
        $this->setAttribute(self::ATTRIBUTE_DELETE_PERMISSION, $delete);
        if ($this->checkPermission($create)) {
            $this->getModel()->addOption(BaseModel::OPTION_CREATE_ALLOWED);
        }
        if ($this->checkPermission($edit)) {
            $this->getModel()->addOption(BaseModel::OPTION_EDIT_ALLOWED);
        }
        if ($this->checkPermission($delete)) {
            $this->getModel()->addOption(BaseModel::OPTION_DELETE_ALLOWED);
        }
    }

    /**
     *
     */
    protected function initializePermissionErrors()
    {
        $validationHelper = new ValidationHelper();
        $validationHelper->addErrorFieldMap($this->getValidationErrorMap());
        if (count($validationHelper->getErrorList('Permission'))) {
            $alert = new Alert();
            $alert->setHeading($validationHelper->getSummary('PermissionDenied'));
            foreach ($validationHelper->getErrorList('Permission') as $item) {
                $alert->addParagraph($item);
            }
            if ($this->hasView()) {
                $this->getView()->prepend($alert);
            }
            $this->getControllerResponse()->setStatusCode(ControllerResponse::STATUS_PERMISSION_DENIED);
        }

        if (count($validationHelper->getErrorList('General'))) {
            $alert = new Alert();
            foreach ($validationHelper->getErrorList('General') as $item) {
                $alert->addParagraph($item);
            }
            if ($this->hasView()) {
                $this->getView()->prepend($alert);
            }
            $this->getControllerResponse()->setStatusCode(ControllerResponse::STATUS_PERMISSION_DENIED);
        }
    }

    /**
     *
     */
    protected function initializeProfiler()
    {
        $profiler = $this->getModel()->getDbAdpater()->getProfiler();
        if ($profiler instanceof ProfilerInterface) {
            $profiles = $profiler->getProfiles();
            $alert = new Alert();
            $alert->setHeading('Debug');
            $alert->setStyle(Alert::STYLE_WARNING);
            $alert->setHeading(
                'Abfragen: '
                . count($profiles)
                . '<br>'
                . array_sum(array_column($profiles, 'elapse'))
                . ' ms'
            );
            foreach ($profiles as $profile) {
                $alert->addParagraph($profile['sql'] . "<br>{$profile['elapse']} ms");
            }
            $this->getView()->prepend($alert);
        }
    }

    /**
     *
     */
    protected function injectHideModal()
    {
        if ($this->getControllerRequest()->isAjax() && $this->getControllerRequest()->hasSubmit()) {
            $validationHelper = new ValidationHelper();
            $validationHelper->addErrorFieldMap($this->getValidationErrorMap());
            if (
                $this->getModel()->getValidationHelper()->isValid()
                && $validationHelper->isValid()
            ) {
                $this->getControllerResponse()->getInjector()->addHtml(
                    '<script>$("#ajax-modal").modal("hide")</script>',
                    'body',
                    ControllerResponseInjector::MODE_APPEND
                );
            }
        }
    }

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function injectNavigation()
    {
        if (
            $this->hasView() && $this->getView()->hasLayout()
            && !$this->getControllerRequest()->hasEditLocale()
        ) {
            $layout = $this->getView()->getLayout();
            if ($layout instanceof DashboardLayout) {
                $bean = $this->getView();
                if ($bean instanceof BeanConverterAwareInterface && $bean->hasBeanConverter()) {
                    $bean = $bean->getBeanConverter()->convert($this->getView());
                }
                $layout->getSubNavigation()->setId('subnavigation');
                if ($this->getControllerRequest()->isAjax()) {
                    $this->getControllerResponse()->getInjector()->addHtml(
                        $layout->getSubNavigation()->render($bean, true),
                        '#subnavigation',
                        'replace'
                    );
                }
                $layout->getNavigation()->setId('navigation');
                if ($this->getControllerRequest()->isAjax()) {
                    $this->getControllerResponse()->getInjector()->addHtml(
                        $layout->getNavigation()->render($bean, true),
                        '#navigation',
                        'replace'
                    );
                }
            }
        }
    }

    /**
     * @param Throwable $throwable
     */
    protected function handleErrorLog(Throwable $throwable)
    {
        $this->getLogger()->error("Error: ", ['exception' => $throwable]);
    }

    /**
     * @param Throwable $throwable
     */
    protected function handleErrorMessage(Throwable $throwable)
    {
        $alert = new Alert('');
        $alert->setHeading("Es ist ein Fehler aufgetreten.");
        if ($this->getControllerResponse()->getStatusCode() == 404) {
            $alert->setStyle(Alert::STYLE_DARK);
        } else {
            $alert->setStyle(Alert::STYLE_DANGER);
        }
        $alert->addParagraph($throwable->getMessage());
        $alert->addParagraph("{$throwable->getFile()}:{$throwable->getLine()}");
        $alert->addParagraph('Trace');
        $trace = explode(PHP_EOL, $throwable->getTraceAsString());
        $trace = array_slice($trace, 0, 5);
        $alert->addParagraph(implode('<br>', $trace));
        if ($this->hasView()) {
            $this->getView()->append($alert);
        } else {
            $this->getControllerResponse()->setBody($throwable->getMessage());
            $this->getControllerResponse()->removeOption(ControllerResponse::OPTION_RENDER_RESPONSE);
        }
    }

    /**
     *
     */
    protected function handleErrorTransaction()
    {
        $adapter = $this->getControllerRequest()
            ->getServerRequest()
            ->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE);
        if ($adapter instanceof AdapterInterface) {
            if (
                $adapter->getDriver()->getConnection()->inTransaction()
                && $adapter->getDriver()->getConnection()->isConnected()
            ) {
                $adapter->getDriver()->getConnection()->rollback();
            }
        }
    }
}
