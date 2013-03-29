<?php

/**
 * load up GMH_AppCalendar so that it can be used in the layout footer
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class GMH_Plugins_LayoutCalendarPlugin extends Zend_Controller_Plugin_Abstract
{
   public function preDispatch(Zend_Controller_Request_Abstract $request)
   {
        $calendarMapper = new Application_Model_CalendarMapper();
        $calendar = $calendarMapper->getCalendar();

        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();

        $view->calendar = $calendar;
   }
}

?>
