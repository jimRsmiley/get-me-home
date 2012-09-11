<?php

class Application_Model_DbTable_Route extends Zend_Db_Table_Abstract
{

    protected $_name = 'route';
    protected $_primary = 'route_id';

    protected $_dependentTables = array( 'Trip' );
}

