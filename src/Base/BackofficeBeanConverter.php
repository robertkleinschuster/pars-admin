<?php

namespace Pars\Admin\Base;

use Niceshops\Bean\Converter\AbstractBeanConverter;
use Niceshops\Bean\Type\Base\AbstractBaseBean;
use Niceshops\Bean\Type\Base\BeanInterface;

/**
 * Class BackofficeBeanConverter
 * @package Pars\Admin\Base
 */
class BackofficeBeanConverter extends AbstractBeanConverter
{
    public function convertValueFromBean(BeanInterface $bean, string $name, $value)
    {
        if ($value === null) {
            return null;
        }
        switch ($bean->type($name)) {
            case AbstractBaseBean::DATA_TYPE_FLOAT:
            case AbstractBaseBean::DATA_TYPE_INT:
            case AbstractBaseBean::DATA_TYPE_STRING:
                return strval($value);
            case AbstractBaseBean::DATA_TYPE_BOOL:
                if ($value) {
                    return 'true';
                } else {
                    return 'false';
                }
            case AbstractBaseBean::DATA_TYPE_ARRAY:
                return json_encode($value);
            case AbstractBaseBean::DATA_TYPE_DATE:
            case AbstractBaseBean::DATA_TYPE_DATETIME_PHP:
                if ($value instanceof \DateTime) {
                    return $value->format('Y-m-d H:i:s');
                }
        }
        return print_r($value, true);
    }

    public function convertValueToBean(BeanInterface $bean, string $name, $value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        switch ($bean->type($name)) {
            case AbstractBaseBean::DATA_TYPE_STRING:
                return strval($value);
            case AbstractBaseBean::DATA_TYPE_BOOL:
                if ($value === 'true') {
                    return true;
                } elseif ($value === 'false') {
                    return false;
                }
                break;
            case AbstractBaseBean::DATA_TYPE_INT:
                return intval($value);
            case AbstractBaseBean::DATA_TYPE_FLOAT:
                return boolval($value);
            case AbstractBaseBean::DATA_TYPE_ARRAY:
                return json_decode($value);
            case AbstractBaseBean::DATA_TYPE_DATE:
            case AbstractBaseBean::DATA_TYPE_DATETIME_PHP:
                return \DateTime::createFromFormat('Y-m-d H:i:s', $value);
        }
        return $value;
    }
}
