<?php

namespace AgvBootstrap\View\Helper;

use Zend\View\Helper\AbstractHtmlElement;

/**
 * Description of Alert
 *
 * @author alberto
 */
class Alert extends AbstractHtmlElement
{

    private $class = array(
        'error' => 'danger',
        'info' => 'info',
        'success' => 'success',
        'warning' => 'warning',
    );

    public function __invoke($alerts)
    {
        $html = '';
        foreach ($alerts as $namespace => $messages) {
            if (count($messages)) {
                foreach ($messages as $message) {
                    $html .= sprintf(
                        '<div class="alert alert-%s">
                            <button class="close" data-dismiss="alert" type="button">×</button>
                            %s
                        </div>', $this->getClass($namespace), $message
                    );
                }
            }
        }

        return $html;
    }

    private function getClass($namespace)
    {
        return $this->class[$namespace];
    }

}
