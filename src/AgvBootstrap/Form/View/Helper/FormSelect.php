<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormSelect as ZendFormSelect;
use Zend\Form\ElementInterface;
use Zend\Form\Element\Select as SelectElement;
use Zend\Form\Exception;

class FormSelect extends ZendFormSelect
{
    /**
     * Render a form <select> element from the provided $element
     *
     * @param  ElementInterface                   $element
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        if (!$element instanceof SelectElement) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Zend\Form\Element\Select',
                __METHOD__
            ));
        }

        $name   = $element->getName();
        if (empty($name) && $name !== 0) {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $options = $element->getValueOptions();

        if (($emptyOption = $element->getEmptyOption()) !== null) {
            $options = array('' => $emptyOption) + $options;
        }

        $attributes = $element->getAttributes();
        $value      = $this->validateMultiValue($element->getValue(), $attributes);

        $attributes['name'] = $name;
        if (array_key_exists('multiple', $attributes) && $attributes['multiple']) {
            $attributes['name'] .= '[]';
        }
        $this->validTagAttributes = $this->validSelectAttributes;

        unset($attributes['size']);
        $size = $element->getAttribute('size');
        if (empty($size)) {
            return sprintf(
                '<select %s>%s</select>',
                $this->createAttributesString($attributes),
                $this->renderOptions($options, $value)
            );
        }

        if (!isset($attributes['class'])) {
            $attributes['class'] = 'form-control';
        }

        return sprintf(
            '<div class="col-lg-%s col-md-%s col-sm-%s col-xs-%s">
                <select %s>%s</select>
            </div>',
            $size, $size, $size, $size,
            $this->createAttributesString($attributes),
            $this->renderOptions($options, $value)
        );
    }
}
