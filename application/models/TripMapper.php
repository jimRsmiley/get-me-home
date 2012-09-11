<?php

/**
 * Description of TripMapper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_Model_TripMapper {
    
    protected $_dbTable;
    
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
    
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Trip');
        }
        return $this->_dbTable;
    }
    
    public function fetchByRouteId( $route_id ) {
        $select = $this->getDbTable()->select();
        
        $select->where('route_id = ?', $route_id);
        
        $trips = $this->execQuery($select);
        
        return $trips;
    }
    
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $trips   = array();
        foreach ($resultSet as $row) {
            $trip = new GMH_Septa_Trip( $row->toArray() );
            $trips[] = $trip;
        }
        return $trips;
    }
    
    public static function resultSetToTrips( $resultSet ) {
        $trips   = array();
        foreach ($resultSet as $row) {
            $trip = new GMH_Septa_Trip( $row->toArray() );
            $trips[] = $trip;
        }
        return $trips;
    }
    
    /**
     * executes the query and returns an array of route objects
     * @param type $select
     * @param type $getDependentColumns
     */
    public function execQuery( $select = null ) {
        $rowSet = $this->getDbTable()->fetchAll($select);
        
        $trips   = array();
        
        foreach ($rowSet as $row) {
            
            $trip = new GMH_Septa_Trip( $row->toArray() );
            $trips[] = $trip;
        }
        return $trips;
    }
}

?>
