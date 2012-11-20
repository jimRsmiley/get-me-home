<?php

/**
 * Description of SuperDuperResponseObject
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class GMH_SuperDuperResponseObject {
    
    protected $routes;
    protected $fromTime;
    protected $toTime;
    protected $directionId;
    
    // the stop name we're looking for departure times from
    protected $stopName;
    
    /**
     * add a route to the response object, if the array isn't created, create it
     * @param GMH_Septa_Route $route
     */
    public function addRoute( GMH_Septa_Route $route ) {
        
        if( !is_array($this->routes) ) {
            $this->routes = array();
        }
        array_push($this->routes, $route );
    }
    
    /**
     * return the directionId
     * @return string
     */
    public function getDirectionId() {
        return $this->directionId;
    }
    /**
     * returns the stop name
     * @return string
     */
    public function getStopName() {
        return $this->stopName;
    }
    /**
     * set the direction id for the routes that we're looking for
     * @param type $directionId
     */
    public function setDirectionId($directionId) {
        $this->directionId = $directionId;
    }
    
    /**
     * returns the from time for all bus stops
     * @return string the from time
     */
    public function getFromTime() {
        return $this->fromTime;
    }
    
    /**
     * sets the from time to the given string
     * @param type $fromTime
     */
    public function setFromTime( $fromTime ) {
        $this->fromTime = $fromTime;
    }
    
    /**
     * set the stop name for which we're looking for bus departure times
     * @param string $stopName
     */
    public function setStopName( $stopName ) {
        $this->stopName = $stopName;
    }
    
    /**
     * set the time till w're looking for buses to depart
     * @param string $toTime
     */
    public function setToTime( $toTime ) {
        $this->toTime = $toTime;
    }
    
    public function getNumRoutes() {
        return count( $this->routes );
    }
    
    public function getRouteShortNames() {
        
        $routeShortNames = array();
        
        foreach( $this->routes as $route ) {
            $routeShortNames[] = $route->getRouteShortName();
        }
        
        return $routeShortNames;
    }
    
    /**
     * get all the unique stop times from the protected array routes
     * @return array an array of stop times
     */
    public function getStopTimes() {
        
        $stopTimes = array();
        
        foreach( $this->routes as $route ) {
            
            foreach( $route->getTrips() as $trip ) {
                
                foreach( $trip->getStopTimes() as $stopTime ) {
                    
                    if( !in_array($stopTime, $stopTimes) ) {
                        array_push( $stopTimes, $stopTime );
                    }
                }
            }
        }
        
        asort($stopTimes);
        return $stopTimes;
    }
    
    public function getRoutesByStopTime( $stopTime ) {
        
        $stopName = $this->stopName;
        $directionId = $this->directionId;
        
        if( !is_string($stopTime) ) {
            throw new InvalidArgumentException("stopTime must be a string");
        }
        else if( empty( $stopTime ) ) {
            throw new InvalidArgumentException("stopTime may not be null");
        }
        else if( empty( $stopName ) ) {
            throw new InvalidArgumentException("stopName may not be null");
        }
        else if( $directionId == null ) {
            throw new InvalidArgumentException("directionId may not be null");
        }
        
        $routes = array();
        
        foreach( $this->routes as $route ) {
            
            $trip = $route->getTripByStopTime( $stopTime, $directionId );
            
            if( $trip != null ) {
                array_push( $routes, $route );
            }
            
        }
        
        return $routes;
    }
}

?>
