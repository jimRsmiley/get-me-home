<?php

class StopController extends Zend_Controller_Action
{
    public function byTripIdAction() {
        $trip_id = $this->getRequest()->getParam("tripId");
        
        $mapper = new Application_Model_StopMapper();
        
        $this->view->stops = $mapper->fetchByTripId($trip_id);
    }
}

