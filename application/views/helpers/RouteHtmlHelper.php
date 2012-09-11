<?php

/**
 * Description of RouteHtmlHelper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_View_Helper_RouteHtmlHelper extends Zend_View_Helper_Abstract
{
    public function routeHtmlHelper( GMH_Septa_Route $route )
    {
$html = <<< heredoc
<div class="ui-grid-a gmh_object">
    <span class="ui-block-a gmh_objectlabel">RouteName:</span>
    <span class="ui-block-b">{$route->getRouteShortName()}</span>
    
    <span>
        <a href="/trip/by-route-id?routeId={$route->getRouteId()}">
            get trips
        </a>
    </span>
</div>    
heredoc;
        
        return $html;
    }
}
