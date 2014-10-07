<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormDate as ZendFormDate;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

class FormDate extends ZendFormDate
{
    const BOOTSTRAP_COLUMN = 2;
    
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
        $attributes['id'] = str_replace(']', '', (str_replace('[', '-',$attributes['id'])));

        return sprintf(
            //col-lg-%s col-md-%s col-sm-%s 
            '<div class="col-sm-%s">
                    <input %s%s
                </div>', self::BOOTSTRAP_COLUMN, //, $size, $size, $size,
            $this->createAttributesString($attributes),
            $this->getInlineClosingBracket()
        );
    }
    
    /**
     * Determine input type to use
     *
     * @param  ElementInterface $element
     * @return string
     */
    protected function getType(ElementInterface $element)
    {
        return 'text';
    }
}
