<?php

/**
 * Description of StopTimeHelper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_View_Helper_StopTimeHelper extends Zend_View_Helper_Abstract {
    
    public $view;
 
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
    
    public function stopTimeHelper( $stopTime, $shortNames ) {
        
        $html = "<div>";
        $html .= "<div>". $this->view->timeViewHelper($stopTime)."</div>";
        
        foreach( $shortNames as $shortName ) {
            $html .= "<span>".$shortName."</span>";
        }
        
        $html .= "</div>";
        
        
        return $html;
    }
}

?>
