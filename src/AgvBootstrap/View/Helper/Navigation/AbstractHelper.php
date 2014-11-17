<?php

namespace AgvBootstrap\View\Helper\Navigation;

use Zend\View\Helper\Navigation\AbstractHelper as ZendAbstractHelper;
use Zend\Navigation\Navigation;
use Zend\Navigation\Page\AbstractPage;

abstract class AbstractHelper extends ZendAbstractHelper
{
    /**
     * View helper entry point:
     * Retrieves helper and optionally sets container to operate on
     * @param  \Zend\Navigation\Navigation $container [optional] container to operate on
     * @return TwbNavbar                   fluent interface, returns self
     */
    public function __invoke(Navigation $container = null)
    {
        if (null !== $container) {
            $this->setContainer($container);
        }

        return $this;
    }

    protected function translate($text)
    {
        if ($this->isTranslatorEnabled() && $this->getTranslator() && is_string($text)
            && !empty($text)) {
            $text = $this->getTranslator()->translate($text);
        }

        return $text;
    }

    /**
     * Render the container
     * @param  \Zend\Navigation\Navigation $container
     * @param  array                       $options
     * @return string
     */
    protected function renderContainer(Navigation $container,
                                       array $options = array())
    {
        $pages = $container->getPages();
        $html = '';
        foreach ($pages as $page) {
            if ($page->hasPages()) {
                //Dropdown menu
                $html .= PHP_EOL . $this->renderDropdown($page);
            } else {
                $html .= PHP_EOL . $this->renderItem($page, false);
            }
        }

        return $this->decorateContainer($html, $options);
    }

    /**
     * Decorate the dropdown link
     * @param  \Zend\Navigation\Navigation        $content
     * @param  \Zend\Navigation\Page\AbstractPage $page
     * @return string
     */
    protected function decorateDropdownLink($content, AbstractPage $page)
    {
        $class = '';
        if ($page->isActive(true)) {
            $class = 'class="active"';
        }

        return sprintf('<li %s >%s</li>', $class, $content);
    }

    /**
     * Render a menu item
     * @param  \Zend\Navigation\Page\AbstractPage $page
     * @param  boolean                            $renderInDropdown
     * @return string
     */
    protected function renderItem(AbstractPage $page, $renderInDropdown = false)
    {
        if ($renderInDropdown) {
            $itemHtml = $this->renderDropdownLinks($page);

            return $this->decorateDropdownLink($itemHtml, $page);
        }

        $itemHtml = $this->renderLink($page);

        return $this->decorateLink($itemHtml, $page);
    }

    /**
     * Render a link with tag a
     * @param  \Zend\Navigation\Page\AbstractPage $page
     * @return string
     */
    protected function renderLink(AbstractPage $page)
    {
        return $this->htmlTagA($page);
    }

    /**
     * Render a dropdown
     * @param  \Zend\Navigation\Page\AbstractPage $page
     * @return string
     */
    protected function renderDropdown(AbstractPage $page)
    {
        //Get label and title
        $label = $this->translate($page->getLabel());
        $title = $this->translate($page->getTitle());
        $escaper = $this->view->plugin('escapeHtml');
        //Get attribs
        $class = $page->getClass();
        $this->addWord('dropdown-toggle', $class);
        $attribs = array(
            'title' => $title,
            'class' => $class,
            'data-toggle' => 'dropdown',
            'href' => '#',
        );

        $html = sprintf(
            '<a %s>%s<b class="caret"></b></a>', $this->htmlAttribs($attribs),
            $escaper($label)
        );

        $html .= PHP_EOL . '<ul class="dropdown-menu">';

        $pages = $page->getPages();
        foreach ($pages as $dropdownPage) {
            $html .= PHP_EOL . $this->renderItem($dropdownPage, true);
        }

        $html .= PHP_EOL . '</ul>';

        return $this->decorateDropdown($html, $page);
    }

    /**
     * Render the HTML for a nav-text on right
     * @param  string $text
     * @return string
     */
    protected function renderNavTextRight($text)
    {
        return sprintf('<p class="navbar-text pull-right">%s</p>', $text);
    }

    abstract protected function decorateContainer($content,
                                                  array $options = array());

    abstract protected function decorateLink($content, AbstractPage $page);

    abstract protected function decorateDropdown($content, AbstractPage $page);

    /**
     * Returns an HTML string containing an 'a' element for the given page
     * @param  \Zend\Navigation\Page\AbstractPage $page
     * @param  bool                               $renderIcons
     * @param  bool                               $activeIconInverse
     * @return string
     */
    public function htmlTagA(AbstractPage $page)
    {
        // get label and title for translating
        $label = $this->translate($page->getLabel());
        $title = $this->translate($page->getTitle());
        $escaper = $this->view->plugin('escapeHtml');
        //Get attribs for anchor element
        $attribs = array(
            'id' => $page->getId(),
            'title' => $title,
            'class' => $page->getClass(),
            'href' => $page->getHref(),
            'target' => $page->getTarget(),
        );

        return sprintf(
            '<a %s>%s</a>', $this->htmlAttribs($attribs), $escaper($label)
        );
    }

    /**
     * Render the HTML for dropdown links
     * @param  \Zend\Navigation\Page\AbstractPage $page
     * @return string
     */
    protected function renderDropdownLinks(AbstractPage $page)
    {
        return $this->renderLink($page);
    }

    /**
     * If missing in the text, adds the space separated word to the text
     * @param string $word
     * @param string $text
     */
    protected function addWord($word, &$text)
    {
        $text = trim($text);
        if (!$text) {
            $wordsLower = array();
            $words = array();
        } else {
            $wordsLower = explode(' ', strtolower($text));
            $words = explode(' ', $text);
        }
        if (!in_array(strtolower($word), $wordsLower)) {
            $words[] = $word;
            $text = implode(' ', $words);
        }
    }
}
