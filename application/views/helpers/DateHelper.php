<?php

/**
 * Description of DateHelper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_View_Helper_DateHelper  extends Zend_View_Helper_Abstract {
    
    public $view;
 
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
    
    public function dateHelper($yyyymmdd) {
        
        if( preg_match( "/(\d\d\d\d)(\d\d)(\d\d)/", $yyyymmdd, $matches ) ) {
            $year = $matches[1];
            $month = $this->dropLeadingZero( $matches[2] );
            $day = $this->dropLeadingZero( $matches[3] );
            
            return $month . "/" . $day . "/" . $year;
        }
    }
    
    /**
     * return a string that is stripped of the leading 9, or the string itself
     * @param type $string
     * @return type
     */
    public function dropLeadingZero( $string ) {
        
        if( preg_match( "/0(\d)/", $string, $matches ) ) {
            return $matches[1];
        }
        else {
            return $string;
        }
    }
}

?>
