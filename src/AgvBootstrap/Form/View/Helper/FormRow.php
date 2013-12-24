<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormRow as ZendFormRow;
use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\File;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\ElementInterface;
use AgvBootstrap\Form\View\Helper\Form as AgvBootstrapForm;

class FormRow extends ZendFormRow
{

    /**
     * The class that is added to element that have errors
     * @var string
     */
    protected $inputErrorClass = 'has-error';

    /**
     * Form Type such as inline and horizontal
     * @var string
     */
    protected $formType = null;
    
    /**
     * Row class
     * @var string 
     */
    protected $rowClass = 'form-group';
    
    protected $elementClass = 'form-control';

    public function setFormType($formType)
    {
        $this->formType = $formType;
        return $this;
    }

    public function __invoke(ElementInterface $element = null, $formType = null,
                             $labelPosition = null, $renderErrors = null,
                             $partial = null)
    {
        $this->setFormType($formType);

        return parent::__invoke($element, $labelPosition, $renderErrors,
                $partial);
    }

    /**
     * Utility form helper that renders a label (if it exists), an element and errors
     *
     * @param  ElementInterface $element
     * @throws \Zend\Form\Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $escapeHtmlHelper = $this->getEscapeHtmlHelper();
        $labelHelper = $this->getLabelHelper();
        $elementHelper = $this->getElementHelper();
        $elementErrorsHelper = $this->getElementErrorsHelper();

        $label = $element->getLabel();

        if (isset($label) && '' !== $label) {
            // Translate the label
            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label, $this->getTranslatorTextDomain()
                );
            }
        }

        if ($this->partial) {
            $vars = array(
                'element' => $element,
                'label' => $label,
                'labelAttributes' => $this->labelAttributes,
                'labelPosition' => $this->labelPosition,
                'renderErrors' => $this->renderErrors,
            );

            return $this->view->render($this->partial, $vars);
        }

        $elementErrors = '';
        if ($this->renderErrors) {
            $elementErrors = $elementErrorsHelper->render($element);
        }

        if (!$element->hasAttribute('id')) {
            $element->setAttribute('id', $this->getId($element));
        }

        if (isset($label) && '' !== $label) {
            $label = $escapeHtmlHelper($label);
        }

        if ($element instanceof MultiCheckbox || $element instanceof Radio) {
            $rowClass = $this->rowClass;
            $markup = sprintf(
                '<fieldset><legend>%s</legend>%s</fieldset>', $label,
                $elementHelper->render($element)
            );
        } else if ($element instanceof Checkbox) {
            $rowClass = 'checkbox';
            $markup = $labelHelper->openTag()
                . $elementHelper->render($element)
                . $label
                . $labelHelper->closeTag();
        } 
        else if ($element instanceof File) {
            $rowClass = $this->rowClass;
            $label = $labelHelper->openTag($this->getLabelAttributesByElement($element)) 
                . $label
                . $labelHelper->closeTag();
            $markup = $label . $elementHelper->render($element);
        } else if ($element instanceof Hidden) {
            return $elementHelper->render($element);
        } else if ($element instanceof Select) {
            $rowClass = $this->rowClass;

            $label = $labelHelper->openTag($this->getLabelAttributesByElement($element)) 
                . $label 
                . $labelHelper->closeTag();
            if ($this->formType == AgvBootstrapForm::FORM_TYPE_INLINE) {
                $label = '';
            }

            $markup = $label . $elementHelper->render($element);
        } else {
            $rowClass = $this->rowClass;

            $elementClass = $element->getAttribute('class');
            if (!empty($elementClass)) {
                $elementClass .= ' ';
            }
            $elementClass .= $this->elementClass;
            $element->setAttribute('class', $elementClass);

            $label = $labelHelper->openTag($this->getLabelAttributesByElement($element)) 
                . $label 
                . $labelHelper->closeTag();
            $markup = $label . $elementHelper->render($element);
        }

        if ($this->renderErrors) {
            $markup .= $elementErrors;
        }

        $errorClass = $this->getInputErrorClass();

        if (count($element->getMessages()) && !empty($errorClass)) {
            $rowClass .= ' ' . $errorClass;
        }

        //ensure to have space at the end to proper elements spacing in inline variant
        if ($element instanceof Submit || $element instanceof Button) {
            return $markup . ' ';
        }

        return sprintf(
            '<div class="%s">%s</div> ', $rowClass, $markup
        );
    }

    protected function getLabelAttributesByElement($element)
    {
        $labelAttributes = $element->getLabelAttributes();

        if (empty($labelAttributes)) {
            $labelAttributes = array();
        }

        if (!isset($labelAttributes['for'])) {
            $labelAttributes['for'] = $element->getAttribute('id');
        }

        if (isset($labelAttributes['class'])) {
            $labelAttributes['class'] .= ' ';
        } else {
            $labelAttributes['class'] = '';
        }

        if (!isset($labelAttributes['size'])) {
            $size = $element->getOption('size') ? 2 : 0;
        }

        $labelAttributes['class'] .= 'control-label';

        if (!empty($size)) {
            $labelAttributes['class'] .= sprintf(
                '  col-xs-%s',
                $size
            );
        }

        return $labelAttributes;
    }

}
