<?php

class RouteController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $routeMapper = new Application_Model_RouteMapper();
        
        $routes = $routeMapper->fetchAll();
        
        $this->view->routes = $routes;
        $this->render('routes');
    }

    public function fetchByShortNameAction()
    {
        $shortName = $this->getRequest()->getParam("short-name");
        $shortName = strtoupper( $shortName );
        
        $mapper = new Application_Model_RouteMapper();
        
        $routes = $mapper->fetchByShortName($shortName, $getTrips = true );
        
        Zend_Debug::dump( $routes, "in fetch" );
        exit;
        $this->view->routes = $routes;    
        
        $this->render('routes');
    }


}





