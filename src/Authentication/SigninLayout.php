<?php

namespace Pars\Admin\Authentication;

use Pars\Component\Base\BackgroundAwareInterface;
use Pars\Component\Base\Layout\BaseLayout;
use Pars\Mvc\View\HtmlElement;

class SigninLayout extends BaseLayout
{
    protected function main(HtmlElement $main)
    {
        parent::main($main);
        $main->addInlineStyle('min-height', 'calc(100% - 56px)');
        $main->addOption('d-flex');
        $main->addOption('align-items-center');
        $main->addOption('flex-column');
        $main->addOption('justify-content-center');
        $main->addOption('text-center');
        $main->addOption(BackgroundAwareInterface::BACKGROUND_DARK);
    }

    protected function components(HtmlElement $components)
    {
        parent::components($components);
        $components->addOption('w-100');
        $components->addOption('px-2');
        $components->addOption('px-sm-5');
    }
}
