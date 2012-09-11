<?php

/**
 * Description of StopMapper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_Model_StopMapper {
    
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
            $this->setDbTable('Application_Model_DbTable_Stop');
        }
        return $this->_dbTable;
    }
    
    public function fetchByStopId( $stopId ) {
        $select = $this->getDbTable()->select();
        
        $select->where('stop_id = ?', $stopId );
        
        $stops = $this->execQuery($select );
        
        return $stops[0];
    }
    
    public function fetchByTripId( $tripId ) {
        
        $db = $this->getDbTable()->getAdapter();
        $select = $db->select();
        
        $select->from('stop_time')
                ->joinUsing('stop', 'stop_id')
                ->joinUsing('trip', 'trip_id')
                ->where( 'trip.trip_id = ?', $tripId )
                ;
        
        $resultSet = $db->fetchAll( $select );
        
        $stops = array();
        foreach( $resultSet as $row ) {
            $stop = self::getStopFromRow($row);
            $stopTime = Application_Model_StopTimeMapper::getStopTimeFromRow($row);
            $stops[] = $stop;
        }
        return $stops;
    }
    
    /**
     * executes the query and returns an array of route objects
     * @param type $select
     * @param type $getDependentColumns
     */
    public function execQuery( $select = null ) {
        $rowSet = $this->getDbTable()->fetchAll($select);
        $stops   = array();
        
        foreach ($rowSet as $row) {
            $stop = new GMH_Septa_Stop( $row->toArray() );
            
            $stops[] = $stop;
        }
        return $stops;
    }
    
    public static function getStopFromRow( $row ) {
        $stop = new GMH_Septa_Stop();
        $stop->setLocationType($row['location_type'] );
        $stop->setParentStation($row['parent_station'] );
        $stop->setStopId($row['stop_id']);
        $stop->setStopLat($row['stop_lat']);
        $stop->setStopLon($row['stop_lon']);
        $stop->setStopName($row['stop_name']);
        $stop->setZoneId($row['zone_id']);
        
        return $stop;
    }
    

}

?>
