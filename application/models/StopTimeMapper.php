<?php

/**
 * Description of StopTimeMapper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_Model_StopTimeMapper {
    
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
            $this->setDbTable('Application_Model_DbTable_StopTime');
        }
        return $this->_dbTable;
    }
    

    
    public static function resultSetToStopTimes( $resultSet ) {
        $stopTimes   = array();
        foreach ($resultSet as $row) {
            $stopTime = new GMH_Septa_StopTime( $row->toArray() );
            $stopTimes[] = $stopTime;
        }
        return $stopTimes;
    }
    
    /**
     * executes the query and returns an array of route objects
     * @param type $select
     * @param type $getDependentColumns
     */
    public function execQuery( $select ) {
        $rowSet = $this->getDbTable()->fetchAll($select);
        $stopTimes = array();
        foreach ($rowSet as $row) {
            
            $stopTime = new GMH_Septa_StopTime( $row->toArray() );
            
            
            $stopTimes[] = $stopTime;
        }
        return $stopTimes;
    }
    
    public static function getStopTimeFromRow( $row ) {
        $stopTime = new GMH_Septa_StopTime();
        $stopTime->setArrivalTime($row['arrival_time']);
        $stopTime->setDepartureTime($row['departure_time']);
        $stopTime->setStopId($row['stop_id']);
        $stopTime->setStopSequence($row['stop_sequence'] );
        $stopTime->setTripId($row['trip_id']);
        return $stopTime;
    }
    
    public static function stopTimeComparison( $a, $b ) {
        
        $time1 = strtotime($a->getDepartureTime());
        $time2 = strtotime($b->getDepartureTime());
        
        return ( $time1 < $time2);
    }
}

?>
