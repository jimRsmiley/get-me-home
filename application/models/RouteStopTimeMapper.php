<?php

/**
 * This class's purpose is to map routes to trips to stop times across all
 * three tables
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_Model_RouteStopTimeMapper {
    
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
    public function fetchStopTimes( $routeShortName,
                                    $serviceId, 
                                    $stopName = null, 
                                    $directionId = null, 
                                    $fromTime = null,
                                    $toTime = null 
            ) 
    {
        if( !is_string( $routeShortName ) ) {
            throw new InvalidArgumentException( "routeShortName must be string" );
        }
        if( empty( $routeShortName ) ) {
            throw new Exception( "route short name cannot be empty" );
        }
        
        if( empty($serviceId) ) {
            throw new Exception("service id may not be empty" );
        }
        
        $db = $this->getDbTable()->getAdapter();
        $select = $this->getDbTable()->getAdapter()->select();
        
        $select->from( 'stop_time' )
                ->joinInner('trip', 'trip.trip_id = stop_time.trip_id' )
                ->joinInner('route', 'route.route_id = trip.route_id' )
                ->joinInner('stop', 'stop.stop_id = stop_time.stop_id' )
                ->where( 'route.route_short_name = ?', $routeShortName )
                ->where( 'trip.service_id = ?', $serviceId );
        
        if( $stopName != null ) {
            $select->where( 'stop.stop_name = ?', $stopName );
        }
        if( $directionId != null ) {
            $select->where( 'trip.direction_id = ?', $directionId );
        }
        
        if( !empty($fromTime) && !empty($toTime) ) {
            
            $fromTime = date( "H:i:s", strtotime($fromTime) );
            $toTime   = date( "H:i:s", strtotime($toTime) );
            
            $select->where( 
                    'time( stop_time.departure_time ) > time(?)', $fromTime 
                    );
            $select->where( 
                    'time( stop_time.departure_time ) < time(?)', $toTime 
                    );
        }
        
        $resultSet = $db->fetchAll( $select );
        
        $response = new GMH_SuperDuperResponseObject();
        $response->setFromTime($fromTime);
        
        $route = null;
        foreach( $resultSet as $row ) {
            
            $stopTime = Application_Model_StopTimeMapper::getStopTimeFromRow($row);
            $trip = Application_Model_TripMapper::getTripFromRow($row);

            if( $route == null ) {
                $route = Application_Model_RouteMapper::getRouteFromRow($row);
            }
            
            if( !$route->getTripById($trip->getTripId() ) ) {
                
                $route->addTrip($trip);
            }
            
            $route->getTripById($trip->getTripId() )->addStopTime($stopTime);
        }
        
        return $route;
    }
    
    public static function orderStopTimesByDepartureTime( $stopTimeArray ) {
        
        $result = usort( 
                    $stopTimeArray, 
                    "Application_Model_StopTimeMapper::stopTimeComparison" 
                );
        
        return $stopTimeArray;
    }
    

}

?>
