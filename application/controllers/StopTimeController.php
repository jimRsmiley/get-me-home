<?php

class StopTimeController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $mapper = new Application_Model_StopTimeMapper();
        
        $stopTimes = $mapper->fetch( $tripId="3357773", $stopId="220" );
        
        Zend_Debug::dump( $stopTimes );
        exit;
    }


}

