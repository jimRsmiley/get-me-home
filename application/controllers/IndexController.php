<?php

class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {
        
        $searchRouteShortNames = array("K");
        $searchRouteShortNames = array("K","75","89","J");
        
        $stopName = "Arrott Transportation Center";
        $serviceId = GMH_Septa_ServiceId::$WEEKDAY;
        $directionId = "0";
        $fromTime = date("H:i:s", time() );
        $toTime   = date( "H:i:s", time() + 60 * 60 );
        
        $mapper = new Application_Model_RouteStopTimeMapper();

        $response = new GMH_SuperDuperResponseObject();
        $response->setFromTime($fromTime);
        $response->setToTime($toTime);
        $response->setStopName($stopName);
        $response->setDirectionId($directionId);
        
        foreach( $searchRouteShortNames as $routeShortName ) {
            $route = $mapper->fetchStopTimes(
                $routeShortName, $serviceId, $stopName, 
                $directionId, $fromTime, $toTime );
            $response->addRoute( $route );
        }
        
        $this->view->response = $response;
    }
}