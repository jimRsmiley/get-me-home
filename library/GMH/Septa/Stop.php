<?php

/**
 * Description of Stop
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class GMH_Septa_Stop extends GMH_BaseObject  {
	private $stopId = null;
	private $stopName = null;
	private $stopLat = null;
	private $stopLon = null;
	private $locationType = null;
	private $parentStation = null;
	private $zoneId = null;

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
            throw new Exception('Invalid stop property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid stop property');
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
    
	public function getStopId(){
		return $this->stopId;
	}

	public function setStopId($stopId){
		$this->stopId = $stopId;
	}

	public function getStopName(){
		return $this->stopName;
	}

	public function setStopName($stopName){
		$this->stopName = $stopName;
	}

	public function getStopLat(){
		return $this->stopLat;
	}

	public function setStopLat($stopLat){
		$this->stopLat = $stopLat;
	}

	public function getStopLon(){
		return $this->stopLon;
	}

	public function setStopLon($stopLon){
		$this->stopLon = $stopLon;
	}

	public function getLocationType(){
		return $this->locationType;
	}

	public function setLocationType($locationType){
		$this->locationType = $locationType;
	}

	public function getParentStation(){
		return $this->parentStation;
	}

	public function setParentStation($parentStation){
		$this->parentStation = $parentStation;
	}

	public function getZoneId(){
		return $this->zoneId;
	}

	public function setZoneId($zoneId){
		$this->zoneId = $zoneId;
	}
    
    public function setStopTimes( $stopTimes ) {
        $this->stopTimes = $stopTimes;
    }
    
    public function getStopTimes() {
        return $this->stopTimes;
    }
}

?>
