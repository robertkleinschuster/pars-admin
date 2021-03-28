<?php

namespace Pars\Admin\Config;

use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\BooleanValueFieldAccept;
use Pars\Component\Base\ColorAwareInterface;
use Pars\Component\Base\Field\Icon;

/**
 * Class ConfigOverview
 * @package Pars\Admin\Config
 */
class ConfigOverview extends BaseOverview
{
    /**
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    protected function initialize()
    {
        $this->setSection($this->translate('section.config'));
        $this->setShowDelete(false);
        $this->setShowDeleteBulk(false);
        $this->setShowCreate(false);
        $this->addField('Config_Code', $this->translate('config.code'));
        $this->addField('ConfigType_Code', $this->translate('configtype.code'));
        $this->addField('Config_Value', $this->translate('config.value'));

        $icon = new Icon(Icon::ICON_ALERT_TRIANGLE);
        $icon->addOption(ColorAwareInterface::COLOR_DANGER);
        $icon->setAccept(new ConfigValueInfoFieldAccept());
        $this->append($icon);

        $icon = new Icon(Icon::ICON_LOCK);
        $icon->addOption(ColorAwareInterface::COLOR_SECONDARY);
        $icon->setAccept(new BooleanValueFieldAccept('Config_Locked'));
        $this->append($icon);
        $this->setShowEditFieldAccept(new BooleanValueFieldAccept('Config_Locked', true));
        parent::initialize();
    }

    /**
     * @return string
     */
    protected function getController(): string
    {
        return 'config';
    }

    /**
     * @return string[]
     */
    protected function getDetailIdFields(): array
    {
        return [
            'Config_Code', 'ConfigType_Code'
        ];
    }
}
