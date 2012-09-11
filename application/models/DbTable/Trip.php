<?php

class Application_Model_DbTable_Trip extends Zend_Db_Table_Abstract
{
    protected $_name = 'trip';
    
    protected $_primary = 'trip_id';

    protected $_referenceMap = array( 
            array( 
                "columns" =>  array( "route_id"),
                "refTableClass" => "Application_Model_DbTable_Route",
                "refColumns" => array( "route_id" )
            ) 
        );
    
    protected $_dependentTables = array( 'StopTime' );
}