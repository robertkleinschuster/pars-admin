<?php

namespace Pars\Admin\Cms\Post;

use Niceshops\Bean\Converter\ConverterBeanDecorator;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\Field\Span;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class CmsPostPublishTimestampFieldFormat implements FieldFormatInterface
{
    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        if ($bean instanceof ConverterBeanDecorator) {
            $bean = $bean->getBean();
        }
        $date = $bean->get('CmsPost_PublishTimestamp');
        if ($field instanceof Span) {
            $now = new \DateTime();
            if ($date < $now) {
                $field->setColor(Span::COLOR_SUCCESS);
            }
            if ($date > $now) {
                $field->setColor(Span::COLOR_DANGER);
            }
        }
        return $date->format('d.m.Y H:i:s');
    }
}
