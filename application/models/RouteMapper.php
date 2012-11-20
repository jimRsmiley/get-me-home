<?php

/**
 * Description of RouteMapper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_Model_RouteMapper {
    
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
            $this->setDbTable('Application_Model_DbTable_Route');
        }
        return $this->_dbTable;
    }
    
    public function fetchByShortName( $shortName, $getTrips = false ) {
        $select = $this->getDbTable()->select();
        
        $select->where('route_short_name = ?', $shortName);
        
        $routes = $this->execQuery($select, $getTrips );
        
        return $routes;
    }
    
    public function fetchAll() {
        $routes = $this->execQuery();
        
        return $routes;
    }
    
    /**
     * executes the query and returns an array of route objects
     * @param type $select
     * @param type $getDependentColumns
     */
    public function execQuery( $select = null ) {
        $rowSet = $this->getDbTable()->fetchAll($select);
        $routes   = array();
        foreach ($rowSet as $row) {
            $route = new GMH_Septa_Route( $row->toArray() );
            $routes[] = $route;
        }
        return self::orderRoutes($routes);
    }
    
    public static function orderRoutes( $routes ) {
        usort( $routes, "self::sortRouteFunction" );
        return $routes;
    }
    
    public static function sortRouteFunction( $a, $b ) {
        
        $routeShortName1 = $a->getRouteShortName();
        $routeShortName2 = $b->getRouteShortName();
        
        if( is_numeric( $routeShortName1 ) && is_numeric( $routeShortName2 ) ) {
            return ( $routeShortName1+0 ) > ( $routeShortName2+0 );
        }
        else {
            return $routeShortName1 > $routeShortName2;
        }
    }
    
    public static function getRouteFromRow( $row ) {
        $route = new GMH_Septa_Route();
        $route->setRouteId( $row['route_id'] );
        $route->setRouteColor( $row['route_color']);
        $route->setRouteLongName( $row['route_long_name'] );
        $route->setRouteShortName( $row['route_short_name'] );
        $route->setRouteTextColor( $row['route_text_color'] );
        $route->setRouteType( $row['route_type'] );
        $route->setRouteUrl( $row['route_url'] );
        
        return $route;
    }
}

?>
