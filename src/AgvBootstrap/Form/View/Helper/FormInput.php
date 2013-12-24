<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormInput as ZendFormInput;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

/**
 * Description of FormInput
 *
 * @author alberto
 */
class FormInput extends ZendFormInput
{

    /**
     * Attributes valid for the input tag
     *
     * @var array
     */
    protected $validTagAttributes = array(
        'name' => true,
        'accept' => true,
        'alt' => true,
        'autocomplete' => true,
        'autofocus' => true,
        'checked' => true,
        'dirname' => true,
        'disabled' => true,
        'form' => true,
        'formaction' => true,
        'formenctype' => true,
        'formmethod' => true,
        'formnovalidate' => true,
        'formtarget' => true,
        'height' => true,
        'list' => true,
        'max' => true,
        'maxlength' => true,
        'min' => true,
        'multiple' => true,
        'pattern' => true,
        'placeholder' => true,
        'readonly' => true,
        'required' => true,
        'size' => true,
        'src' => true,
        'step' => true,
        'type' => true,
        'value' => true,
        'width' => true,
        'gridSize' => true,
    );

    /**
     * Render a form <input> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\DomainException
     * @return string
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

        $size = $element->getAttribute('size');
        if (empty($size)) {
            return sprintf(
                '<input %s%s', $this->createAttributesString($attributes),
                $this->getInlineClosingBracket()
            );
        }

        return sprintf(
            //col-lg-%s col-md-%s col-sm-%s 
            '<div class="col-xs-%s">
                    <input %s%s
                </div>', $size, //, $size, $size, $size,
            $this->createAttributesString($attributes),
            $this->getInlineClosingBracket()
        );
    }

}
