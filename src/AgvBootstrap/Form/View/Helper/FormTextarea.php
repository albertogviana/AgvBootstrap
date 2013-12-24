<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormTextarea as ZendFormTextarea;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

class FormTextarea extends ZendFormTextarea
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormTextarea
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * Render a form <textarea> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $name   = $element->getName();
        if (empty($name) && $name !== 0) {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $attributes         = $element->getAttributes();
        $attributes['name'] = $name;
        $content            = (string) $element->getValue();
        $escapeHtml         = $this->getEscapeHtmlHelper();

        $size = $element->getAttribute('size');
        if (empty($size)) {
            return sprintf(
                '<textarea %s>%s</textarea>',
                $this->createAttributesString($attributes),
                $escapeHtml($content)
            );
        }
        
        return sprintf(
            '<div class="col-lg-%s col-md-%s col-sm-%s col-xs-%s">
                    <textarea %s>%s</textarea>
                </div>', $size, $size, $size, $size,
            $this->createAttributesString($attributes), $escapeHtml($content)
        );
    }
}