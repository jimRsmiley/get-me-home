<?php

/**
 * Description of RouteHtmlHelper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_View_Helper_StopHtmlHelper extends Zend_View_Helper_Abstract
{

    public function stopHtmlHelper( GMH_Septa_Stop $stop )
    {
$html = <<< heredoc
<div class="ui-grid-a gmh_object">
    
    <span class="ui-block-a gmh_objectlabel">Stop ID:</span>
    <span class="ui-block-b">{$stop->getStopId()}</span>
        
    <span class="ui-block-a gmh_objectlabel">Stop Name:</span>
    <span class="ui-block-b">{$stop->getStopName()}</span>
    
</div>    
heredoc;
        
        return $html;
    }
}
