<?php

/**
 * Description of Calendar
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class GMH_Septa_Calendar extends GMH_BaseObject {
    
    protected $serviceId = null;
    protected $monday = null;
    protected $tuesday = null;
    protected $wednesday = null;
    protected $thursday = null;
    protected $friday = null;
    protected $saturday = null;
    protected $sunday = null;
    protected $startDate = null;
    protected $endDate = null;
            
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
    
	public function getServiceId(){
		return $this->serviceId;
	}

	public function setServiceId($serviceId){
		$this->serviceId = $serviceId;
	}

	public function getMonday(){
		return $this->monday;
	}

	public function setMonday($monday){
		$this->monday = $monday;
	}

	public function getTuesday(){
		return $this->tuesday;
	}

	public function setTuesday($tuesday){
		$this->tuesday = $tuesday;
	}

	public function getWednesday(){
		return $this->wednesday;
	}

	public function setWednesday($wednesday){
		$this->wednesday = $wednesday;
	}

	public function getThursday(){
		return $this->thursday;
	}

	public function setThursday($thursday){
		$this->thursday = $thursday;
	}

	public function getFriday(){
		return $this->friday;
	}

	public function setFriday($friday){
		$this->friday = $friday;
	}

	public function getSaturday(){
		return $this->saturday;
	}

	public function setSaturday($saturday){
		$this->saturday = $saturday;
	}

	public function getSunday(){
		return $this->sunday;
	}

	public function setSunday($sunday){
		$this->sunday = $sunday;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}

	public function getEndDate(){
		return $this->endDate;
	}

	public function setEndDate($endDate){
		$this->endDate = $endDate;
	}

    /**
     * return the calendar type of the object
     * @return GMH_Septa_CalendarType
     * @throws Excpeption if unable to determine calendar type
     */
    public function getCalendarType() {
        if( $this->getSaturday() == 1 ) {
            return GMH_Septa_CalendarType::SATURDAY;
        }
        else if( $this->getSunday() == 1 ) {
            return GMH_Septa_CalendarType::SUNDAY;
        }
        else if( $this->validAllWeekdays() ) {
            return GMH_Septa_CalendarType::WEEKDAY;
        }
        else {
            return GMH_Septa_CalendarType::UNKNOWN;
        }
    }
    
    /**
     * returns true if this calendar is valid for all days of the week
     * @return boolean
     */
    public function validAllWeekdays() {
        
        if( $this->monday != 1 ) {
            return false;
        }
        else if( $this->tuesday != 1 ) {
            return false;
        }
        if( $this->wednesday != 1 ) {
            return false;
        }
        if( $this->thursday != 1 ) {
            return false;
        }
        if( $this->friday != 1 ) {
            return false;
        }
        
        return true;
    }
}
?>
