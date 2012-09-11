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
