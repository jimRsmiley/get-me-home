<?php

/**
 * Description of CalendarMapper
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Application_Model_CalendarMapper {

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
            $this->setDbTable('Application_Model_DbTable_Calendar');
        }
        return $this->_dbTable;
    }
    
    /**
     * fetch all calendar records, optionally specified with the Zend select
     * object
     * @param Zend_Db_Select $select
     * @return array
     */
    public function fetchAll( Zend_Db_Select $select = null ) {
       
        $rowSet = $this->getDbTable()->fetchAll();

        $calendars   = array();
        foreach ($rowSet as $row) {
            $calendar = new GMH_Septa_Calendar( $row->toArray() );
            $calendars[] = $calendar;
        }
        
        return $calendars;
    }
    
    public function getCalendar() {
        return new GMH_AppCalendar( $this->fetchAll() );
    }
}

?>
