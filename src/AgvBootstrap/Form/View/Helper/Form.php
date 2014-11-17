<?php

namespace AgvBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\Form as ZendForm;
use Zend\Form\FormInterface;

/**
 * Description of Form
 *
 * @author alberto
 */
class Form extends ZendForm
{
    /**
     * Form type Horizontal
     * @var string
     */

    const FORM_TYPE_HORIZONTAL = 'horizontal';

    /**
     * Form type Vertical
     * @var string
     */
    const FORM_TYPE_VERTICAL = 'vertical';

    /**
     * Form type Inline
     * @var string
     */
    const FORM_TYPE_INLINE = 'inline';

    /**
     * Attributes valid for this tag (form)
     *
     * @var array
     */
    protected $validTagAttributes = array(
        'accept-charset' => true,
        'action' => true,
        'autocomplete' => true,
        'enctype' => true,
        'method' => true,
        'name' => true,
        'novalidate' => true,
        'target' => true,
        'role' => true,
        'class' => true,
    );
    protected $validTypes = array(
        'inline' => true,
        'horizontal' => true,
    );
    protected $type = null;

    /**
     * Generate an opening form tag
     *
     * @param  FormInterface $form
     * @return string
     */
    public function openTag(FormInterface $form = null)
    {
        $attributes = array(
            'action' => '',
            'method' => 'get',
            'role' => 'form',
            'class' => '',
        );

        if ($form instanceof FormInterface) {
            $formAttributes = $form->getAttributes();
            if (!array_key_exists('id', $formAttributes) && array_key_exists('name',
                            $formAttributes)) {
                $formAttributes['id'] = $formAttributes['name'];
            }
            $attributes = array_merge($attributes, $formAttributes);
        }

        if (!empty($this->type) && !empty($this->validTypes[$this->type])) {
            if (isset($attributes['class'])) {
                $attributes['class'] = $attributes['class'] . ' ';
            }

            $attributes['class'] .= 'form-' . $this->type;
        }

        $tag = sprintf('<form %s>', $this->createAttributesString($attributes));

        return $tag;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
