<?php
namespace Logging;

use DBAL\Database;
use Configuration\Config;

class EventLogger {
    protected $db;
    protected $config;
    
    public $limit = 50;

    /**
     * Constructor
     * @param Database $db
     * @param Config $config
     */
    public function __construct(Database $db, Config $config) {
        $this->db = $db;
        $this->config = $config;
    }
    
    /**
     * Add an event to the database
     * @param inst $userID This is the user ID to assign the event to
     * @param string $event This should be the event description
     * @param array $additional Any additional fields that you may have to add to the event
     * @return boolean If the event has successfully been added will return true else returns false
     */
    public function addEvent($userID, $event, $additional = []) {
        return $this->db->insert($this->config->event_logs_table, array_merge(['user_id' => $userID, 'event' => $event], array_filter($additional)));
    }
    
    /**
     * Gets a list of events matching the criteria
     * @param array $where This should be the fields you are filtering by (leave empty for all events)
     * @return array|false If any events exist they will be returned as an array else will return false
     */
    public function getEvents($where = [], $page = 1) {
        return $this->db->selectAll($this->config->event_logs_table, array_filter($where), '*', [], [max((($page - 1) * $this->getLimit()), 0) => $this->getLimit()]);
    }
    
    /**
     * Gets a list of events a given user has made
     * @param int $userID This should be the user you a getting a list of events for
     * @return array|false If any events exist will return an array else will return false
     */
    public function getUserEvents($userID, $page = 1) {
        return $this->getEvents(['user_id' => $userID], $page);
    }
    
    /**
     * Returns the number of events matching the given parameters
     * @param array $where This should be the fields you are filtering by (leave empty for all events)
     * @return int Will be the number of events
     */
    public function countEvents($where = []) {
        return $this->db->count($this->config->event_logs_table, array_filter($where));
    }
    
    /**
     * Returns the number of user events
     * @param int $userID This should be the user you a getting the number of events for
     * @return int The number of events for that user
     */
    public function countUserEvents($userID){
        return $this->countEvents(['user_id' => $userID]);
    }
    
    /**
     * Delete a specific event
     * @param int $eventID This should be the unique event ID
     * @return boolean If the event has been deleted will return true else return false
     */
    public function deleteEventByID($eventID) {
        if(is_numeric($eventID)){
            return $this->deleteEvents(['id' => $eventID]);
        }
        return false;
    }
    
    /**
     * Deletes events for a given parameter or if left blank deletes all events
     * @param array $where This should be the parameter that you wish to delete the events upon
     * @return boolean If successfully deleted will return true else return false
     */
    public function deleteEvents($where = []){
        if(is_array($where) && !empty($where)){
            return $this->db->delete($this->config->event_logs_table, array_filter($where));
        }
        return false;
    }
    
    /**
     * Sets the limit for the maximum number of events to return
     * @param int $limit This should be the maximum number of results you wish to return
     * @return $this
     */
    public function setLimit($limit) {
        if(is_numeric($limit)){
            $this->limit = max(intval($limit), 1);
        }
        return $this;
    }
    
    /**
     * Returns the current maximum number of results to return
     * @return int
     */
    public function getLimit(){
        return $this->limit;
    }
}
