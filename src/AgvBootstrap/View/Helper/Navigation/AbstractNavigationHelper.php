<?php

namespace AgvBootstrap\View\Helper\Navigation;

use Zend\Navigation\Page\AbstractPage;

/**
 * Description of AbstractNavigationHelper
 *
 * @author alberto
 */
abstract class AbstractNavigationHelper extends AbstractHelper
{

    const ALIGN_RIGHT = 'right';
    const ALIGN_LEFT = 'left';

    /**
     * Render the brand header
     * @param  Zend\Navigation\Page\AbstractPage $brand
     * @return string
     */
    protected function renderNavHeader(AbstractPage $brand = null)
    {
        $html = '';
        $html .= PHP_EOL . '<div class="navbar-header">';
        $html .= PHP_EOL . '<button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse" type="button">';
        $html .= PHP_EOL . '<span class="icon-bar"></span>';
        $html .= PHP_EOL . '<span class="icon-bar"></span>';
        $html .= PHP_EOL . '<span class="icon-bar"></span>';
        $html .= PHP_EOL . '</button>';

        if ($brand instanceof AbstractPage) {
            $view = $this->getView();
            $brandName = $view->escapeHtml($brand->getLabel());
            $html .= PHP_EOL . '<a class="navbar-brand" href="' . $brand->get('Route') . '">' . $brandName . '</a>';
        }

        $html .= PHP_EOL . '</div>';

        return $html;
    }

    /**
     * Decorate container
     * @param  string $content
     * @param  array  $options
     * @return string
     */
    protected function decorateContainer($content, array $options = array())
    {
        //Align option
        if (array_key_exists('align', $options)) {
            $align = $options['align'];
        } else {
            $align = null;
        }
        //ulClass option
        if (array_key_exists('ulClass', $options)) {
            $ulClass = $options['ulClass'];
        } else {
            $ulClass = '';
        }
        if ($align == self::ALIGN_LEFT) {
            $this->addWord('pull-left', $ulClass);
        } elseif ($align == self::ALIGN_RIGHT) {
            $this->addWord('pull-right', $ulClass);
        }
        $html = "<ul class='{$ulClass}'>";
        $html .= PHP_EOL . $content;
        $html .= PHP_EOL . '</ul>';

        return $html;
    }

    /**
     * Decorante link
     * @param  string                             $content
     * @param  \Zend\Navigation\Page\AbstractPage $page
     * @return string
     */
    protected function decorateLink($content, AbstractPage $page)
    {
        return $this->decorateDropdownLink($content, $page);
    }

    /**
     * Decorate dropdown
     * @param  string                             $content
     * @param  \Zend\Navigation\Page\AbstractPage $page
     * @return string
     */
    protected function decorateDropdown($content, AbstractPage $page)
    {
        $attribs = array(
            'id' => $page->getId(),
            'class' => 'dropdown' . ($page->isActive(true) ? ' active' : ''),
        );

        $html = PHP_EOL . '<li ' . $this->htmlAttribs($attribs) . ' >'
            . PHP_EOL . $content
            . PHP_EOL . '</li>';

        return $html;
    }

}
