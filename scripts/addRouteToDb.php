<?php
class InitSeptaDb {
    
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
    
    function loadData() {
   
        $files = $this->getFileNames(APPLICATION_PATH . $dir);
        
        foreach( $files as $file ) {
            print "processing $file\n";
            $this->createTableFromFile( $file);
        }
    }
    
    function createTableFromFile( $file ) {
        $tableName = basename( $file, ".txt" );
        print "Creating table $tableName from file $file\n";

        $handle = fopen($file, "r") or die("Couldn't get handle");
        
        if ($handle) {
            
            $header = fgets( $handle, 4096 );
            
            $fields = str_getcsv($header);
            
            $sql = $this->getCreateTableSql($tableName, $fields);
            $this->dbAdapter->getConnection()->exec($sql);
            
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
    
    public static function getInsertRowSql( $tableName, $data ) {
        
        $fields = array_keys( $data );
        $fieldStr = implode( ",", $fields );

        foreach( $data as $key => $value ) {
            $data[$key] = self::prepStrForSql( $data[$key] );
        }

        $dataStr = implode( ",", $data );
        $sql = "INSERT INTO $tableName ($fieldStr) VALUES ($dataStr)";
        
        print "$sql\n";
        
        return $sql;
    }
    
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
        $ret = mysql_real_escape_string( stripslashes($name) );
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
            print "deleting file " . $this->dbname . "\n";
            
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
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    array_push( $files, $dir . "/" . $entry );
                }
            }
            closedir($handle);
        }
        
        return $files;
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

$septaDataDir = "../scripts/google_bus";

$dbInit = new InitSeptaDb();

$dbInit->deleteDbFile();

$septaFiles = $dbInit->getFileNames($septaDataDir);

foreach( $septaFiles as $file ) {
    $dbInit->createTableFromFile($file);
}
?>
