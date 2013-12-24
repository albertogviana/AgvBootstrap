<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormElement as ZendFormElement;
use Zend\Form\Element;
use Zend\Form\ElementInterface;

class FormElement extends ZendFormElement
{

    /**
     * Render an element
     * @param  ElementInterface $element
     * @param  null|string $formType
     * @param  array $displayOptions
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }

        //TODO - captcha
//        if ($element instanceof Element\Captcha) {
//            $helper = $renderer->plugin('form_captcha');
//            return $helper($element);
//        }
//
//        //CSRF
//        if ($element instanceof Element\Csrf) {
//            $helper = $renderer->plugin('form_hidden_twb');
//            return $helper($element, $formType, $displayOptions);
//        }
        //TODO - collection
        if ($element instanceof Element\Collection) {
            $helper = $renderer->plugin('form_collection');
            return $helper($element);
        }

        $type = $element->getAttribute('type');

//        //Multi Checkbox
//        if ('multi_checkbox' == $type && is_array($element->getValueOptions())) {
//            $helper = $renderer->plugin('form_multi_checkbox_twb');
//            return $helper($element, $formType, $displayOptions);
//        }

        //Select
//        if ('select' == $type && is_array($element->getValueOptions())) {
//            $helper = $renderer->plugin('form_select_twb');
//            return $helper($element, $formType, $displayOptions);
//        }

        //Textarea
//        if ('textarea' == $type) {
//            $helper = $renderer->plugin('form_textarea_twb');
//            return $helper($element, $formType, $displayOptions);
//        }

        //Input
        $helper = $renderer->plugin('form_input');
        return $helper($element);
    }

    /**
     * Invoke helper as function
     * Proxies to {@link render()}.
     * @param  ElementInterface|null $element
     * @param  null|string $formType
     * @param  array $displayOptions
     * @return string|FormElementTwb
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }
        return $this->render($element);
    }

}