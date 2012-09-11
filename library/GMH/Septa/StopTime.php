<?php

/**
 * Description of StopTime
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class GMH_Septa_StopTime extends GMH_BaseObject {

	private $tripId = null;
	private $arrivalTime = null;
	private $departureTime = null;
	private $stopId = null;
	private $stopSequence = null;

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
            throw new Exception('Invalid stop time property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid stop time property');
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
    
	public function getTripId(){
		return $this->tripId;
	}

	public function setTripId($tripId){
		$this->tripId = $tripId;
	}

	public function getArrivalTime(){
		return $this->arrivalTime;
	}

	public function setArrivalTime($arrivalTime){
		$this->arrivalTime = $arrivalTime;
	}

	public function getDepartureTime(){
		return $this->departureTime;
	}

	public function setDepartureTime($departureTime){
		$this->departureTime = $departureTime;
	}

	public function getStopId(){
		return $this->stopId;
	}

	public function setStopId($stopId){
		$this->stopId = $stopId;
	}

	public function getStopSequence(){
		return $this->stopSequence;
	}

	public function setStopSequence($stopSequence){
		$this->stopSequence = $stopSequence;
	}
}

?>
