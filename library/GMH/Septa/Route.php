<?php

/**
 * Description of Route
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class GMH_Septa_Route extends GMH_BaseObject {
    
    protected $route_id;
    protected $route_short_name;
    protected $route_long_name;
    protected $route_type;
    protected $route_color;
    protected $route_text_color;
    protected $route_url;

    protected $trips;
    
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        } 
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid route property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid route property');
        }
        return $this->$method();
    }
 
    public function getTrips() {
        return $this->trips;
    }
    
    public function getTripById( $tripId ) {
        
        if( !is_array( $this->trips ) ) {
            return null;
        }
        
        foreach( $this->trips as $trip ) {
            
            if( $trip->getTripId() == $tripId ) {
                return $trip;
            }
        }
    }

    public function getTripByStopTime( $stopTimeString, $directionId ) {
        
        if( empty($stopTimeString ) ) {
            throw new InvalidArgumentException("stopTimeString cannot be empty");
        }
        
        // using null because directionId may be 0
        if( $directionId == NULL ) {
            throw new InvalidArgumentException("directionId may not be null");
        }
        
        foreach( $this->trips as $trip ) {
            $testDirectionId = $trip->getDirectionId();
            
            foreach( $trip->getStopTimes() as $stopTime ) {
                
                if( $stopTime->getDepartureTime() == $stopTimeString 
                        && $directionId == $testDirectionId ) {
                    
                    return $trip;
                }
            }
        }
        return null;
    }
    
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $key = $this->to_camel_case( $key );
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    public function getRouteId() {
        return $this->route_id;
    }
    
    public function setRouteId( $id ) {
        $this->route_id = $id;
    }
    
    public function getRouteShortName() {
        return $this->route_short_name;
    }
    
    public function setRouteShortName( $s ) {
        $this->route_short_name = $s;
    }
    
	public function getRouteLongName(){
		return $this->route_long_name;
	}

	public function setRouteLongName($routeLongName){
		$this->route_long_name = $routeLongName;
	}

	public function getRouteType(){
		return $this->route_type;
	}

	public function setRouteType($routeType){
		$this->route_type = $routeType;
	}
    
    public function addTrip( GMH_Septa_Trip $trip ) {
        
        if( !is_array($this->trips) ) {
            $this->trips = array();
        }
        
        array_push( $this->trips, $trip );
    }

	public function getRouteColor(){
		return $this->route_color;
	}

	public function setRouteColor($routeColor){
		$this->route_color = $routeColor;
	}

	public function getRouteTextColor(){
		return $this->route_text_color;
	}

	public function setRouteTextColor($routeTextColor){
		$this->route_text_color = $routeTextColor;
	}

	public function getRouteUrl(){
		return $this->route_url;
	}

	public function setRouteUrl($routeUrl){
		$this->route_url = $routeUrl;
	}
}

?>
