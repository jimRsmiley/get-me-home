<?php

class CalendarController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $mapper = new Application_Model_CalendarMapper();
        
        $calendars = $mapper->fetchAll();
        
        $this->view->calendars = $calendars;
    }


}

