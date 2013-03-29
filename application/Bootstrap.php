<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initTimezone() {
        date_default_timezone_set("America/New_York");
    }
    
    public function _initLayoutCalendarPlugin() {
        Zend_Controller_Front::getInstance()->registerPlugin(
                                    new GMH_Plugins_LayoutCalendarPlugin() );
    }
}

