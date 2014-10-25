<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormElementErrors as ZendFormElementErrors;
use Zend\Form\ElementInterface;

/**
 * Description of FormElementErrors
 *
 * @author alberto
 */
class FormElementErrors extends ZendFormElementErrors
{
    /**@+
     * @var string Templates for the open/close/separators for message tags
     */
    protected $messageCloseString     = '</li></ul>';
    protected $messageOpenFormat      = '<ul%s><li>';
    protected $messageSeparatorString = '</li><li>';
    /**@-*/

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()} if an element is passed.
     *
     * @param  ElementInterface         $element
     * @param  array                    $attributes
     * @return string|FormElementErrors
     */
    public function __invoke(ElementInterface $element = null, array $attributes = array())
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $attributes);
    }

    /**
     * Render validation errors for the provided $element
     *
     * @param  ElementInterface          $element
     * @param  array                     $attributes
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element, array $attributes = array())
    {
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        if (!is_array($messages) && !$messages instanceof Traversable) {
            throw new Exception\DomainException(sprintf(
                '%s expects that $element->getMessages() will return an array or Traversable; received "%s"',
                __METHOD__,
                (is_object($messages) ? get_class($messages) : gettype($messages))
            ));
        }

        $attributes['class'] = 'errors';

        // Prepare attributes for opening tag
        $attributes = array_merge($this->attributes, $attributes);
        $attributes = $this->createAttributesString($attributes);
        if (!empty($attributes)) {
            $attributes = ' ' . $attributes;
        }

        // Flatten message array
        $escapeHtml      = $this->getEscapeHtmlHelper();
        $messagesToPrint = array();
        $self = $this;
        array_walk_recursive($messages, function ($item) use (&$messagesToPrint, $escapeHtml, $self) {
            if (null !== ($translator = $self->getTranslator())) {
                $item = $translator->translate($item, $self->getTranslatorTextDomain());
            }
            $messagesToPrint[] = $escapeHtml($item);
        });

        if (empty($messagesToPrint)) {
            return '';
        }

        // Generate markup
        $markup  = sprintf($this->getMessageOpenFormat(), $attributes);
        $markup .= implode($this->getMessageSeparatorString(), $messagesToPrint);
        $markup .= $this->getMessageCloseString();

        return $markup;
    }
}
