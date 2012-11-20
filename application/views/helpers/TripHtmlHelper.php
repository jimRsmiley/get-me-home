<?php

/**
 * Description of RouteHtmlHelper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_View_Helper_TripHtmlHelper extends Zend_View_Helper_Abstract
{
    
    public $view;
 
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
    
    public function tripHtmlHelper( GMH_Septa_Trip $trip )
    {
$html = <<< heredoc
<div class="ui-grid-a gmh_object">
    
    <span class="ui-block-a gmh_objectlabel">Trip ID:</span>
    <span class="ui-block-b">{$trip->getTripId()}</span>
        
    <!--<span class="ui-block-a gmh_objectlabel">Trip Direction:</span>
    <span class="ui-block-b">{$trip->getDirectionId()}</span>
        -->
    <span class="ui-block-a gmh_objectlabel">Heading To:</span>
    <span  class="ui-block-b">{$trip->getTripHeadSign()}</span>
        
    <span>
        <a href="/stop/by-trip-id?tripId={$trip->getTripId()}">Get Stops</a>
    </span>
        
</div>    
heredoc;
        
        return $html;
    }
}
