<?php

class Application_Model_DbTable_StopTime extends Zend_Db_Table_Abstract
{
    protected $_name = 'stop_time';
    
    protected $_primary = 'stop_time_id';
    
    protected $_referenceMap = array( 
            array( 
                "columns" =>  array( "trip_id"),
                "refTableClass" => "Application_Model_DbTable_Trip",
                "refColumns" => array( "trip_id" )
            ) 
        );
}

