<?php

namespace Pars\Admin\Base;

use Laminas\Db\Adapter\Profiler\ProfilerInterface;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfGuardInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Mezzio\Session\LazySession;
use Mezzio\Session\SessionMiddleware;
use Niceshops\Bean\Converter\BeanConverterAwareInterface;
use Niceshops\Bean\Processor\DefaultMetaFieldHandler;
use Niceshops\Bean\Processor\TimestampMetaFieldHandler;
use Niceshops\Core\Attribute\AttributeAwareInterface;
use Niceshops\Core\Attribute\AttributeAwareTrait;
use Niceshops\Core\Option\OptionAwareInterface;
use Niceshops\Core\Option\OptionAwareTrait;
use Pars\Component\Base\Alert\Alert;
use Pars\Component\Base\Layout\DashboardLayout;
use Pars\Component\Base\View\BaseView;
use Pars\Core\Bundles\BundlesMiddleware;
use Pars\Core\Database\DatabaseMiddleware;
use Pars\Core\Logging\LoggingMiddleware;
use Pars\Core\Translation\TranslatorMiddleware;
use Pars\Helper\Validation\ValidationHelper;
use Pars\Model\Authentication\User\UserBean;
use Pars\Mvc\Controller\AbstractController;
use Pars\Mvc\Controller\ControllerResponse;
use Pars\Mvc\View\ViewBeanConverter;
use Psr\Log\LoggerInterface;

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

    /**
     * @param string $create
     * @param string $edit
     * @param string $delete
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
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
     * @return TranslatorInterface
     */
    protected function getTranslator(): TranslatorInterface
    {
        return $this->getControllerRequest()
            ->getServerRequest()
            ->getAttribute(TranslatorMiddleware::TRANSLATOR_ATTRIBUTE);
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
    protected function getNavigationState(string $id): int
    {
        return (int)$this->getSession()->get($id) ?? 0;
    }

    /**
     * @param ValidationHelper $validationHelper
     * @return mixed|void
     */
    protected function handleValidationError(ValidationHelper $validationHelper)
    {
        $this->getFlashMessanger()->flash('previousAttributes', $this->getControllerRequest()->getAttributes());
        $this->getFlashMessanger()->flash('validationErrorMap', $validationHelper->getErrorFieldMap());
    }

    /**
     * @return bool
     */
    protected function handleSubmitSecurity(): bool
    {
        if ($this->getControllerRequest()->hasAttribute('submit_token')) {
            return $this->validateToken('submit_token', $this->getControllerRequest()->getAttribute('submit_token') ?? '');
        } else {
            return false;
        }
    }

    /**
     * @return CsrfGuardInterface
     */
    protected function getGuard(): CsrfGuardInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
    }

    /**
     * @param string $name
     * @param $token
     * @return bool
     */
    protected function validateToken(string $name, $token)
    {
        $result = $this->getGuard()->validateToken($token, $name);
        $this->generateToken($name);
        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function generateToken(string $name): string
    {
        if (!$this->getSession()->get($name, false)) {
            return $this->getGuard()->generateToken($name);
        } else {
            return $this->getSession()->get($name);
        }
    }

    /**
     * @return FlashMessagesInterface
     */
    public function getFlashMessanger(): FlashMessagesInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
    }

    /**
     * @return array
     */
    protected function getValidationErrorMap(): array
    {
        return $this->getFlashMessanger()->getFlash('validationErrorMap', []);
    }

    /**
     * @return array
     */
    protected function getPreviousAttributes(): array
    {
        return $this->getFlashMessanger()->getFlash('previousAttributes', []);
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
     * @param string $permission
     * @return bool
     */
    protected function checkPermission(string $permission): bool
    {
        return $this->getUserBean()->hasPermission($permission);
    }

    /**
     * @return mixed|void
     * @throws \Niceshops\Bean\Type\Base\BeanException
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     */
    protected function initView()
    {
        $view = new BaseView();
        $this->setView($view);
        $view->setBeanConverter(new ViewBeanConverter());
        $layout = new DashboardLayout();
        $layout->setNavigation(new MainNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean()));
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

        $this->getView()->set('language', $this->getTranslator()->getLocale());
        $this->getView()->set('title', $this->getModel()->getConfig('admin.title'));
        $this->getView()->set('author', $this->getModel()->getConfig('admin.author'));
        $this->getView()->set('favicon', $this->getModel()->getConfig('admin.favicon'));
        $this->getView()->set('description', $this->getModel()->getConfig('admin.description'));
        $this->getView()->set('charset', $this->getModel()->getConfig('admin.charset'));
    }


    /**
     * @return mixed|void
     * @throws \Niceshops\Bean\Type\Base\BeanException
     */
    public function finalize()
    {
        parent::finalize();
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

    public function getLogger(): LoggerInterface
    {
        return $this->getControllerRequest()->
        getServerRequest()
            ->getAttribute(LoggingMiddleware::LOGGER_ATTRIBUTE);
    }

    /**
     * @param \Throwable $exception
     * @return mixed|void
     * @throws \Throwable
     */
    public function error(\Throwable $exception)
    {
        $this->getControllerRequest()->
        getServerRequest()
            ->getAttribute(LoggingMiddleware::LOGGER_ATTRIBUTE)->error("Error: ", ['exception' => $exception]);

        $alert = new Alert('');
        $alert->setHeading("Es ist ein Fehler aufgetreten.");
        if ($this->getControllerResponse()->getStatusCode() == 404) {
            $alert->setStyle(Alert::STYLE_DARK);
        } else {
            $alert->setStyle(Alert::STYLE_DANGER);
        }
        $alert->addParagraph($exception->getMessage());
        $alert->addParagraph("{$exception->getFile()}:{$exception->getLine()}");
        $alert->addParagraph('Trace');
        $trace = explode(PHP_EOL, $exception->getTraceAsString());
        $trace = array_slice($trace, 0, 5);
        $alert->addParagraph(implode('<br>', $trace));
        if ($this->hasView()) {
            $this->getView()->append($alert);
        } else {
            $this->getControllerResponse()->setBody($exception->getMessage());
            $this->getControllerResponse()->removeOption(ControllerResponse::OPTION_RENDER_RESPONSE);
        }
    }


    public function unauthorized()
    {
        $this->getView()->append(new Alert($this->translate('unauthorized.heading'), $this->translate('unauthorized.text')));
    }

    /**
     * @param \Throwable $exception
     * @return mixed|void
     * @throws \Niceshops\Bean\Type\Base\BeanException
     */
    public function notfound(\Throwable $exception)
    {
        if ($this->hasView()) {
            $alert = new Alert($this->translate('notfound.heading'), $this->translate('notfound.text'));
            $alert->addParagraph($exception->getMessage());
            $this->getView()->append($alert);
        } else {
            parent::notfound($exception);
        }
    }

    public function clearcacheAction(bool $redirect = true)
    {

        $this->getTranslator()->clearCache('admin', $this->getTranslator()->getLocale());
        $this->getTranslator()->clearCache('default', $this->getTranslator()->getLocale());
        $this->getTranslator()->clearCache('validation', $this->getTranslator()->getLocale());

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/../data/cache/admin-config-cache.php')) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/../data/cache/admin-config-cache.php');
        }
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/../data/cache/frontend-config-cache.php')) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/../data/cache/frontend-config-cache.php');
        }

        $bundlesConfig = $this->getControllerRequest()->getServerRequest()->getAttribute(BundlesMiddleware::class);
        if ($bundlesConfig !== null) {
            foreach (array_column($bundlesConfig, 'output') as $item) {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $item)) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $item);
                }
            }
        }
        if ($redirect) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('index')->setAction('index'));
        }
    }
}
