<?php

namespace Pars\Admin;


use Pars\Core\Application\AbstractApplicationContainer;


class ApplicationContainer extends AbstractApplicationContainer
{
    public function getApplication()
    {
        return $this->get(Application::class);
    }

}
