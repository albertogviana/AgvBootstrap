<?php

namespace AgvBootstrap\View\Helper\Navigation;

use Zend\View\Helper\Navigation as ZendNavigation;

class Navigation extends ZendNavigation
{

    protected $defaultPluginMap = array(
        'invokables' => array(
            'agvNavbar' => 'AgvBootstrap\View\Helper\Navigation\AgvNavbar'
        )
    );

    public function getPluginManager()
    {
        if (null === $this->plugins) {
            $this->setPluginManager(new PluginManager(new \Zend\ServiceManager\Config(
                $this->defaultPluginMap
            )));
        }
        return $this->plugins;
    }

}