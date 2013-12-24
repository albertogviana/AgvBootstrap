<?php

namespace AgvBootstrap\View\Helper\Navigation;

use AgvBootstrap\View\Helper\Navigation\AbstractNavigationHelper;
use Zend\Navigation\Page\AbstractPage;

/**
 * Description of Navbar
 *
 * @author alberto
 */
class Navbar extends AbstractNavigationHelper
{

    /**
     * Render the navbar HTML
     * @param Zend\Navigation\Navigation|string|null $container
     * @param boolean $staticTop
     * @param boolean $fixedTop
     * @param \Zend\Navigation\Page\AbstractPage $brand
     * @param string $textRight
     * @return string
     */
    public function render($container = null, $staticTop = false,
                           $fixedTop = false, AbstractPage $brand = null,
                           $textRight = null)
    {
        $html = '';
        $class = 'navbar navbar-default';

        if ($staticTop) {
            $class .= ' navbar-static-top';
        }

        if ($fixedTop) {
            $class .= ' navbar-fixed-top';
        }

        $html .= "<div class='{$class}'>";
        $html .= PHP_EOL . '<div class="container">';

        $html .= $this->renderNavHeader($brand);

        if (is_null($container)) {
            $html .= PHP_EOL . '</div>';
            $html .= PHP_EOL . '</div>';
            return $html;
        }

        $html .= PHP_EOL . '<div class="navbar-collapse collapse">';

        //Primary container
        $options = array(
            'align' => null,
            'ulClass' => 'nav navbar-nav',
        );

        $html .= PHP_EOL . $this->renderContainer($container, $options);

        if (!is_null($textRight)) {
            $html .= PHP_EOL . $this->renderNavTextRight($textRight);
        }

        $html .= PHP_EOL . '</div>';


        $html .= PHP_EOL . '</div>';
        $html .= PHP_EOL . '</div>';

        return $html;
    }

}