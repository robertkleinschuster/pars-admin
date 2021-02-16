<?php


namespace Pars\Admin\Base;


use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanException;
use Pars\Helper\Path\PathHelper;
use Pars\Helper\Path\PathHelperAwareTrait;
use Pars\Model\Authentication\User\UserBean;

trait CrudComponentTrait
{
    use TranslatorAwareTrait;
    use PathHelperAwareTrait;

    private UserBean $userBean;

    /**
     * MainNavigation constructor.
     * @param PathHelper $pathHelper
     * @param TranslatorInterface $translator
     * @param UserBean $userBean
     * @throws BeanException
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     */
    public function __construct(PathHelper $pathHelper, TranslatorInterface $translator, UserBean $userBean)
    {
        parent::__construct();
        $this->setPathHelper($pathHelper);
        $this->setTranslator($translator);
        $this->userBean = $userBean;
    }

    /**
     * @return UserBean
     */
    public function getUserBean(): UserBean
    {
        return $this->userBean;
    }


    protected function translate(string $code)
    {
        return $this->getTranslator()->translate($code, 'admin');
    }

}
