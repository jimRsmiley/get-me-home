<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $stop = new GMH_Septa_Stop();
        $stop->setStopId("220");

        $minutesFromNow = 60;
        $fromTime = date('H:i:s', time() );
        $toTime = date('H:i:s', time() + $minutesFromNow * 60 );
        
        $routes = $this->getRoutes();

        foreach( $routes as $route ) {
            $stopTimes = $this->getTimes( $route, 
                                            $stop->getStopId(),
                                            $fromTime,
                                            $toTime );
        }
        
        $stop->setRoutes( $routes );
        
        $stops = array( $stop );
        $this->view->stops = $stops;
    }
    
    public function getTimes( $route, $stopId, $fromTime, $toTime ) {
        
        $mapper = new Application_Model_SuperDuperMapper();
        
        $stopTimes =  $mapper->fetchStopTimes( 
                $route->getRouteId(), 
                $stopId, 
                $service_id = GMH_Septa_ServiceId::$WEEKDAY,
                $direction_id="0",
                $fromTime, 
                $toTime
            );
        
        foreach( $stopTimes as $stopTime ) {
            $stopTime->setRoute( $route );
        }
        
        return $stopTimes;
    }
    
    public function getRoutes() {
        
        $routeK = new GMH_Septa_Route();
        $routeK->setRouteShortName("K");
        $routeK->setRouteId("10726");
        
        
        return array( $routeK );
        
        $route75 = new GMH_Septa_Route();
        $route75->setRouteShortName("75");
        $route75->setRouteId("10707");
        
        $route89 = new GMH_Septa_Route();
        $route89->setRouteShortName("89");
        $route89->setRouteId("10716");
        
        $routeJ = new GMH_Septa_Route();
        $routeJ->setRouteShortName("J");
        $routeJ->setRouteId("10725");
        
        return array( $routeK, $route75, $route89, $routeJ );
    }
}

