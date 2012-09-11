<?php

/**
 * Description of Trip
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class GMH_Septa_Trip extends GMH_BaseObject {
	private $routeId = null;
	private $serviceId = null;
	private $tripId = null;
	private $tripHeadsign = null;
	private $blockId = null;
	private $directionId = null;
	private $shapeId = null;
    
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
            throw new Exception('Invalid trip property');
        }
        return $this->$method();
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
    
	public function getRouteId(){
		return $this->routeId;
	}

	public function setRouteId($routeId){
		$this->routeId = $routeId;
	}

	public function getServiceId(){
		return $this->serviceId;
	}

	public function setServiceId($serviceId){
		$this->serviceId = $serviceId;
	}

	public function getTripId(){
		return $this->tripId;
	}

	public function setTripId($tripId){
		$this->tripId = $tripId;
	}

	public function getTripHeadsign(){
		return $this->tripHeadsign;
	}

	public function setTripHeadsign($tripHeadsign){
		$this->tripHeadsign = $tripHeadsign;
	}

	public function getBlockId(){
		return $this->blockId;
	}

	public function setBlockId($blockId){
		$this->blockId = $blockId;
	}

	public function getDirectionId(){
		return $this->directionId;
	}

	public function setDirectionId($directionId){
		$this->directionId = $directionId;
	}

	public function getShapeId(){
		return $this->shapeId;
	}

	public function setShapeId($shapeId){
		$this->shapeId = $shapeId;
	}
    
    public function setStopTimes( $stopTimes ) {
        $this->stopTimes = $stopTimes;
    }
    
    public function getStopTimes() {
        return $this->stopTimes;
    }
}

?>
