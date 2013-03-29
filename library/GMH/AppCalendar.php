<?php

/**
 * Description of Calendar
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class GMH_AppCalendar {
    
    // an array of GMH_Septa_Calendar objects
    protected $calendars;
    
    public function __construct( $calendars ) {
        $this->setCalendars($calendars);
    }
    
    /**
     * set the calendars
     * @param array $calendars
     */
    public function setCalendars( $calendars ) {
        $this->calendars = $calendars;
    }
    
    /**
     * return the date string for the end date of the weekday schedule
     * @return string date
     */
    public function validTill() {
        
        $calendar = $this->getCalendarByType(GMH_Septa_CalendarType::WEEKDAY );
        
        return $calendar->getEndDate();
    }
    
    /**
     * given the calendar type, return the calendar in the array that matches
     * it
     * @param int $calendarType
     * @return GMH_Septa_Calendar
     */
    public function getCalendarByType( $calendarType ) {
        
        //Zend_Debug::dump( $this->calendars );
        //exit;
        foreach( $this->calendars as $calendar ) {
            
            if( $calendarType == $calendar->getCalendarType() ) {
                return $calendar;
            }
        }
        
        return null;
    }
}

?>
