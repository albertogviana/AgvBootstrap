<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormButton as ZendFormButton;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

class FormButton extends ZendFormButton
{
    const HTML_CLASS_ATTRIBUTE = 'btn btn-default';

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @param  null|string           $buttonContent
     * @return string|FormButton
     */
    public function __invoke(ElementInterface $element = null,
                             $buttonContent = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $buttonContent);
    }

    /**
     * Render a form <button> element from the provided $element,
     * using content from $buttonContent or the element's "label" attribute
     *
     * @param  ElementInterface          $element
     * @param  null|string               $buttonContent
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element, $buttonContent = null)
    {
        $openTag = $this->openTag($element);

        if (null === $buttonContent) {
            $buttonContent = $element->getLabel();
            if (null === $buttonContent) {
                throw new Exception\DomainException(sprintf(
                    '%s expects either button content as the second argument, ' .
                    'or that the element provided has a label value; neither found',
                    __METHOD__
                ));
            }

            if (null !== ($translator = $this->getTranslator())) {
                $buttonContent = $translator->translate(
                    $buttonContent, $this->getTranslatorTextDomain()
                );
            }
        }

        $escape = $this->getEscapeHtmlHelper();

        return $openTag . $escape($buttonContent) . $this->closeTag();
    }

    /**
     * Generate an opening button tag
     *
     * @param  null|array|ElementInterface        $attributesOrElement
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     * @return string
     */
    public function openTag($attributesOrElement = null)
    {
        if (null === $attributesOrElement) {
            return '<button>';
        }

        if (is_array($attributesOrElement)) {
            $attributesOrElement = $this->classTagExists($attributesOrElement);
            $attributes = $this->createAttributesString($attributesOrElement);

            return sprintf('<button class="btn btn-default" %s>', $attributes);
        }

        if (!$attributesOrElement instanceof ElementInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an array or Zend\Form\ElementInterface instance; received "%s"',
                __METHOD__,
                (is_object($attributesOrElement) ? get_class($attributesOrElement)
                        : gettype($attributesOrElement))
            ));
        }

        $element = $attributesOrElement;
        $name = $element->getName();
        if (empty($name) && $name !== 0) {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $attributes = $element->getAttributes();
        $attributes['name'] = $name;
        $attributes['type'] = $this->getType($element);
        $attributes['value'] = $element->getValue();

        $attributes = $this->classTagExists($attributes);

        return sprintf(
            '<button %s>',
            $this->createAttributesString($attributes)
        );
    }

    /**
     * Verify if class tag was setted up if not it will set it up
     * @param  array   $attributes
     * @return boolean
     */
    private function classTagExists($attributes)
    {
        if (!isset($attributes['class'])) {
            $attributes['class'] = self::HTML_CLASS_ATTRIBUTE;
        }

        return $attributes;
    }

}
