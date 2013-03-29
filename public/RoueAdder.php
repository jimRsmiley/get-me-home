<?php
/**

 */
class RouteAdder {
    
    // the location of the gtfs
    protected $septaDataDir = "../data/septa_gtfs-spring13/google_bus";

    protected $dbAdapter = null;
    
    protected $dbname = null;
    
    function __construct() {
            $this->dbname = APPLICATION_PATH . "/../data/db/septa_data.db";

            $this->dbAdapter = Zend_Db::factory(
                                    'PDO_SQLITE', 
                                    array( 
                                        "dbname" => $this->dbname
                                    ));
    }
    
    public function getAdapter() {
        
        if( $this->dbAdapter == null ) {

            $this->dbAdapter = Zend_Db::factory(
                                    'PDO_SQLITE', 
                                    array( 
                                        "dbname" => $this->dbname
                                    ));
        }
        
        return $this->dbAdapter;
    }
    
    function addStops( $file ) {
        
        $tableName = $this->getSingleName( basename( $file, ".txt" ) );

        $handle = fopen($file, "r") or die("Couldn't get handle");
        
        if ($handle) {
            
            $header = fgets( $handle, 4096 );
            
            $fields = str_getcsv($header);
            
            if( !$this->tableExists($tableName) ) { 
                print "Creating table $tableName from file $file\n";
                $sql = $this->getCreateTableSql($tableName, $fields);
                $this->dbAdapter->getConnection()->exec($sql);
            }
            
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                
                $elements = str_getcsv($buffer);                
                $data = array();
                for( $i = 0; $i < count( $elements ); $i++ ) {
                    $data[$fields[$i]] = $elements[$i];
                }
                $sql = $this->getInsertRowSql( $tableName, $data );
                $this->dbAdapter->getConnection()->exec($sql);
            }
            fclose($handle);
        }
    } 
    
    function addRoute( $routeShortName, $file ) {
        
        // get the base name
        $tableName = basename( $file, ".txt" );
        
        $tableName = $this->getSingleName($tableName);

        $handle = fopen($file, "r") or die("Couldn't get handle");
        
        if ($handle) {
            
            $header = fgets( $handle, 4096 );
            
            $fields = str_getcsv($header);
            
            if( !$this->tableExists($tableName) ) { 
                print "Creating table $tableName from file $file\n";
                $sql = $this->getCreateTableSql($tableName, $fields);
                $this->dbAdapter->getConnection()->exec($sql);
            }
            
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                
                $elements = str_getcsv($buffer);                
                $data = array();
                for( $i = 0; $i < count( $elements ); $i++ ) {
                    $data[$fields[$i]] = $elements[$i];
                }
                
                if( array_key_exists('route_short_name', $data)
                        && $data['route_short_name'] == $routeShortName ) {
                    $sql = $this->getInsertRowSql( $tableName, $data );

                    $this->dbAdapter->getConnection()->exec($sql);
                }
            }
            fclose($handle);
        }
    } 
    
    function addTrips( $routeId, $file ) {
        $tableName = basename( $file, ".txt" );
        
        $tableName = $this->getSingleName($tableName);
        
        $handle = fopen($file, "r") or die("Couldn't get handle");
        
        if ($handle) {
            
            $header = fgets( $handle, 4096 );
            
            $fields = str_getcsv($header);
            
            if( !$this->tableExists($tableName) ) {
                print "Creating table $tableName from file $file\n";
                $sql = $this->getCreateTableSql($tableName, $fields);
                $this->dbAdapter->getConnection()->exec($sql);
            }
            
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                
                $elements = str_getcsv($buffer);                
                $data = array();
                for( $i = 0; $i < count( $elements ); $i++ ) {
                    $data[$fields[$i]] = $elements[$i];
                }
                
                if( array_key_exists('route_id', $data)
                        && $data['route_id'] == $routeId ) {
                    $sql = $this->getInsertRowSql( $tableName, $data );

                    $this->dbAdapter->getConnection()->exec($sql);
                }
            }
            fclose($handle);
        }
    }
    
    /**
     * given a string, removes the trailing 's' if there is one
     * @param string $tableName
     * @return string
     */
    function getSingleName( $tableName ) {
        // get rid of the plural s
        if( preg_match( "/.*s$/", $tableName ) ) {
            $tableName = substr($tableName, 0, strlen($tableName)-1);
        }
        
        return $tableName;
    }
    
    function addStopTimes( $tripIds, $file ) {
        $tableName = basename( $file, ".txt" );
        
        $tableName = $this->getSingleName($tableName);
        
        $handle = fopen($file, "r") or die("Couldn't get handle");
        
        if ($handle) {
            
            $header = fgets( $handle, 4096 );
            
            $fields = str_getcsv($header);
            
            if( !$this->tableExists($tableName) ) {
                print "Creating table $tableName from file $file\n";
                $sql = $this->getCreateTableSql($tableName, $fields);
                $this->dbAdapter->getConnection()->exec($sql);
            }
            
            $counter = 0;
            $ticker = 10000;
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                
                $elements = str_getcsv($buffer);                
                $data = array();
                for( $i = 0; $i < count( $elements ); $i++ ) {
                    $data[$fields[$i]] = $elements[$i];
                }
                
                $testTripId = $data['trip_id'];
                
                if( $counter % $ticker == 0 ) {
                    print "processed " . $counter . " stop times\n";
                }
                $counter++;
                
                if( in_array( $testTripId, $tripIds ) ) {
                    $sql = $this->getInsertRowSql( $tableName, $data );
                    $this->dbAdapter->getConnection()->exec($sql);
                }
            }
            fclose($handle);
        }
    } 
    
    function getRouteId($routeShortName) {
        $db = $this->getAdapter();
        
        $select = $db->select();
        $select->from("route");
        $select->where( "route_short_name = ?", $routeShortName);

        $stmt = $select->query();
        $resultSet = $stmt->fetchAll();
        
        if( count($resultSet) == 0 ) {
            throw new Exception("unable to find route id for short name $routeShortName" );
        }
        else if( count($resultSet) > 1 ) {
            throw new Exception("found multiple route ids for short name $routeShortName" );
        }
        
        $row = $resultSet[0];
        
        return $row['route_id'];
    }
    
    function getTripIds($routeId) {
        $db = $this->getAdapter();
        
        $select = $db->select();
        $select->from("trip");
        $select->where( "route_id = ?", $routeId);

        $stmt = $select->query();
        $resultSet = $stmt->fetchAll();
        
        if( count($resultSet) == 0 ) {
            throw new Exception("unable to find trip ids for route_id $routeId" );
        }
        
        $tripIds = array();
        
        foreach( $resultSet as $row ) {
            array_push( $tripIds,$row['trip_id'] );
        }
        
        return $tripIds;
    }
    
    public function tableExists( $tableName ) {
        
        $db = $this->getAdapter();
        
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name = '$tableName'";
        $result = $db->fetchAll($sql);
        
        return ( count( $result ) > 0 );
    }
    
    public static function getInsertRowSql( $tableName, $data ) {
        
        $fields = array_keys( $data );
        $fieldStr = implode( ",", $fields );

        foreach( $data as $key => $value ) {
            $data[$key] = self::prepStrForSql( $data[$key] );
        }

        $dataStr = implode( ",", $data );
        $sql = "INSERT INTO $tableName ($fieldStr) VALUES ($dataStr)";
        
        return $sql;
    }
    
    /**
     * return a string something like "CREATE TABLE tablename (field1,field2..)"
     * @param type $tableName
     * @param type $fields
     * @return string
     */
    public static function getCreateTableSql( $tableName, $fields ) {
        
        $sql = "CREATE TABLE $tableName (";
        
        foreach( $fields as $field ) {
            $sql .= "$field,";
        }
        
        // get rid of that last comma
        $sql = substr( $sql, 0, strlen($sql) -1 );
        
        $sql .= ")";
        
        return $sql;
    }
    
    static function prepStrForSql($name, $string = true) {
        //$ret = mysql_real_escape_string( stripslashes($name) );
        $ret = stripslashes($name);
        return $string ? '"' . $ret . '"' : $ret;
    }
    
    public function execSql( $sql ) {
        try {
            
            $this->dbAdapter->getConnection()->exec($schemaSql);
//            chmod($dbFile, 0666);
 
            if ('testing' != APPLICATION_ENV) {
                echo PHP_EOL;
                echo 'Database Created';
                echo PHP_EOL;
            }
        } 
        catch (Exception $e) {
            echo 'AN ERROR HAS OCCURED:' . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
            return false;
        }
    }
    
    function deleteDbFile() {
        if ( file_exists( $this->dbname ) ) {
            
            if( !unlink($this->dbname) ) {
                print "ERROR: could not delete file {$this->dbname}\n";
                exit;
            }
        }
        else {
            print "no previous database to delete\n";
        }
    }
    
    /**
     * create an associate array of data from the file using the headers as
     * array keys
     * @param string $file
     */
    function getData( $file ) {
        $data = array();

        $handle = @fopen($file, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                echo $buffer;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
    }
    
    /**
     * return a list of all files in a given directory
     * @param type $dir
     * @return array an array of files
     */
    public static function getFileNames( $dir ) {
        
        if( !file_exists( $dir ) ) {
            throw new Exception ( "$dir does not exist" );
        }
        
        $files = array();
        
        if ($handle = opendir($dir) ) {
            while (false !== ($entry  = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    array_push( $files, $dir . "/" . $entry );
                }
            }
            closedir($handle);
        }
        
        return $files;
    }
    
    /**
     *  given the full filename, add the entire contents to a table with the
     * singular name of the filename without it's suffix
     * @param string $file
     */
    function addTableFromFile( $file ) {
        $file = $this->septaDataDir . DIRECTORY_SEPARATOR . $file;
        
        // get the base name
        $tableName = basename( $file, ".txt" );
        
        $tableName = $this->getSingleName($tableName);

        $handle = fopen($file, "r") or die("Couldn't get handle");
        
        if ($handle) {
            
            $header = fgets( $handle, 4096 );
            
            $fields = str_getcsv($header);
            
            if( !$this->tableExists($tableName) ) { 
                print "Creating table $tableName from file $file\n";
                $sql = $this->getCreateTableSql($tableName, $fields);
                $this->dbAdapter->getConnection()->exec($sql);
            }
            
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                
                $elements = str_getcsv($buffer);                
                $data = array();
                for( $i = 0; $i < count( $elements ); $i++ ) {
                    $data[$fields[$i]] = $elements[$i];
                }
                
                $sql = $this->getInsertRowSql( $tableName, $data );

                $this->dbAdapter->getConnection()->exec($sql);
            }
            fclose($handle);
        }
    } 
    
}



// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap();

$gtfsDir = APPLICATION_PATH . "/../data/septa_gtfs-spring13/google_bus";
$stopsFile  = "$gtfsDir/stops.txt";
$calendarFile = "$gtfsDir/calendar.txt";
$routesFile = "$gtfsDir/routes.txt";
        
        
$routeAdder = new RouteAdder();

if( false ) {
    $routeAdder->deleteDbFile();
    $routeAdder->addStops($stopsFile);
    $routeAdder->addTableFromFile($calendarFile);
}

//addRoute("K",$gtfsDir);
addRoute("75",$gtfsDir);
//addRoute("J",$gtfsDir);
addRoute("89",$gtfsDir);



print "end()\n";

function addRoute($routeShortName, $gtfsDir) {
    
    $routeAdder = new RouteAdder();
    
    // add teh route to the database
    $routeAdder->addRoute($routeShortName, "$gtfsDir/routes.txt");
    
    // add the trips for that route into the database
    $routeId = $routeAdder->getRouteId($routeShortName);
    $routeAdder->addTrips($routeId,"$gtfsDir/trips.txt");
    
    // add the stop times for that route to the database
    $tripIds = $routeAdder->getTripIds($routeId);
    $routeAdder->addStopTimes($tripIds, "$gtfsDir/stop_times.txt" );
    
}
?>
 