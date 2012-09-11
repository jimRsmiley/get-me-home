<?php

/**
 * Description of TripStopTimeMapper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_Model_SuperDuperMapper {
    
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
    
    /**
     * 
     * NOTE: for some unknown reason, including direction_id in the where clause
     * increases the request time to 30 seconds
     * 
     * @param type $routeId
     * @param type $stopId
     * @param type $directionId
     * @param type $lowerTime
     * @param type $upperTime
     * @return \GMH_Septa_StopTime
     */
    public function fetchStopTimes( $routeShortName, $stopName, $serviceId, $directionId, 
            $lowerTime,$upperTime ) {
        
        $db = $this->getDbTable()->getAdapter();
        $select = $this->getDbTable()->getAdapter()->select();
        
        $select->from(array( 'stop_time' ) )
                ->joinUsing('trip', 'trip_id')
                ->joinUsing('route', 'route_id' )
                ->join
                ->where( 'route.route_short_name = ?', "K" )
                ->where( 'trip.service_id = ?', $serviceId )
                ->where( 'trip.direction_id = ?', $directionId )
                ->where( 'stop_time.stop_id = ?', $stopId )
                ->where( 'time( stop_time.departure_time ) > time(?)', $lowerTime )
                ->where( 'time( stop_time.departure_time ) < time(?)', $upperTime )
                ;

        $resultSet = $db->fetchAll( $select );
        
        $stopTimes = array();
        
        foreach( $resultSet as $row ) {
            
            $stopTime = new GMH_Septa_StopTime();
            $stopTime->setStopId( $row['stop_id'] );
            $stopTime->setTripId( $row['trip_id'] );
            $stopTime->setDepartureTime( $row['departure_time'] );
            $stopTime->setArrivalTime( $row['arrival_time']);
            $stopTime->setStopSequence( $row['stop_sequence'] );

            $stopTimes[] = $stopTime;

        }
        
        return self::orderStopTimesByDepartureTime($stopTimes);
    }
    
    public static function orderStopTimesByDepartureTime( $stopTimeArray ) {
        
        $result = usort( $stopTimeArray, "self::stopTimeComparison" );
        
        return $stopTimeArray;
    }
    
    public static function stopTimeComparison( $a, $b ) {
        
        $time1 = strtotime($a->getDepartureTime());
        $time2 = strtotime($b->getDepartureTime());
        
        return ( $time1 < $time2);
    }
}

?>
