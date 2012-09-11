<?php

class TripController extends Zend_Controller_Action
{
    public function byRouteIdAction()
    {
        $route_id = $this->getRequest()->getParam("routeId");
        
        $mapper = new Application_Model_TripMapper();
        
        $this->view->trips = $mapper->fetchByRouteId($route_id );
    }
}





