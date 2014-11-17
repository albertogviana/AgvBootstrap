<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class FormActions extends AbstractHelper
{
    /**
     * Renders the form-actions div tag
     * @param  array  $content Either a string or an array of elements
     * @return string
     */
    public function render($content)
    {
        $html = $this->openTag();

        if (is_array($content)) {
            foreach ($content as $element) {
                $html .= PHP_EOL . $element;
            }
        }

        $html .= PHP_EOL . $this->closeTag();

        return $html;
    }

    /**
     * Returns the form-renderActions open tag
     * @param  null|string $formType
     * @param  array       $displayOptions
     * @return string
     */
    public function openTag()
    {
        return '<div class="btn-group">';
    }

    /**
     * Returns the control group closing tag
     * @param  null|string $formType
     * @return string
     */
    public function closeTag()
    {
        return '</div>';
    }

    /**
     * Invoke helper as function
     * Proxies to {@link render()}.
     * @param  string|array|null     $content        Either a string or an array of elements
     * @param  null|string           $formType
     * @param  array                 $displayOptions
     * @return string|FormActionsTwb
     */
    public function __invoke($content = array())
    {
        if (!$content) {
            return $this;
        }

        return $this->render($content);
    }
}
