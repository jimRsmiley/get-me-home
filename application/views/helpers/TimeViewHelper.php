<?php

/**
 * Description of TimeViewHelper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_View_Helper_TimeViewHelper {
    
    public function timeViewHelper( $timeDescription ) {
        return date( "g:i A", strtotime( $timeDescription ) );
    }
}

?>
