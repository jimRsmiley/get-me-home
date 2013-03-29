<?php

/**
 * Description of DateHelper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_View_Helper_CalendarTypeViewHelper  
    extends Zend_View_Helper_Abstract {
    
    public $view;
 
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
    
    public function calendarTypeViewHelper( $calendarType ) {
        
        if( $calendarType == GMH_Septa_CalendarType::SATURDAY ) {
            return "saturday";
        }
        else if( $calendarType == GMH_Septa_CalendarType::SUNDAY ) {
            return "sunday";
        }
        else if( $calendarType == GMH_Septa_CalendarType::WEEKDAY ) {
            return "weekday";
        }
        else {
            return "unknown";
        }
    }
}

?>
