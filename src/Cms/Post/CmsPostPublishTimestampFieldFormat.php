<?php

namespace Pars\Admin\Cms\Post;

use Niceshops\Bean\Converter\ConverterBeanDecorator;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class CmsPostPublishTimestampFieldFormat implements FieldFormatInterface
{
    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        if ($bean instanceof ConverterBeanDecorator) {
            $bean = $bean->getBean();
        }
        return $bean->get('CmsPost_PublishTimestamp')->format('d.m.Y H:i:s');
    }
}
