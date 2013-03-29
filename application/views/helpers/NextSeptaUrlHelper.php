<?php

/**
 * Description of NextSeptaUrlHelper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_View_Helper_NextSeptaUrlHelper  extends Zend_View_Helper_Abstract {
    
    public $view;
 
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
    
    public function nextSeptaUrlHelper($routeShortName,$directionId,$stationId) {
        return "http://nextsepta.com/buses/"
                .$routeShortName
                . "/"
                . $directionId
                . "/"
                . $stationId;
    }
}

?>
