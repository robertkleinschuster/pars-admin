<?php


namespace Pars\Admin\Base;


use Laminas\I18n\Translator\TranslatorAwareInterface;
use Pars\Component\Base\Navigation\Navigation;
use Pars\Helper\Path\PathHelperAwareInterface;

class BaseNavigation extends Navigation implements PathHelperAwareInterface, TranslatorAwareInterface
{
   use AdminComponentTrait;
}
