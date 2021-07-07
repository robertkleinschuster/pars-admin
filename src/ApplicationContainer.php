<?php

namespace Pars\Admin;


use Pars\Core\Application\AbstractApplicationContainer;

/**
 * Class ApplicationContainer
 * @package Pars\Admin
 */
class ApplicationContainer extends AbstractApplicationContainer
{
    public function getApplication()
    {
        return $this->get(Application::class);
    }

}
