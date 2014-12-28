<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormSubmit as ZendFormSubmit;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

class FormSubmit extends ZendFormSubmit
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormInput
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * Render
     * @param ElementInterface $element
     * @return string
     * @throws Exception\DomainException
     */
    public function render(ElementInterface $element)
    {
        $name = $element->getName();
        if ($name === null || $name === '') {
            throw new Exception\DomainException(sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__
            ));
        }

        $attributes = $element->getAttributes();
        $attributes['name'] = $name;
        $attributes['type'] = $this->getType($element);
        $attributes['value'] = $element->getValue();

        $cssClass = '';
        if(isset($attributes['class'])) {
            $cssClass = $attributes['class'];
        }

        return sprintf(
                '<input class="btn btn-primary %s" %s%s',
                $cssClass,
                $this->createAttributesString($attributes),
                $this->getInlineClosingBracket()
        );
    }
}
