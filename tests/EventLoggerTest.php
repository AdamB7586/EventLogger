<?php
namespace Logging\Tests;

use PHPUnit\Framework\TestCase;
use DBAL\Database;
use Configuration\Config;
use Logging\EventLogger;

class EventLoggerTest extends TestCase{
    protected $db;
    protected $config;
    protected $logger;
    
    public function setUp(): void {
        $this->db = new Database($GLOBALS['HOSTNAME'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], $GLOBALS['DATABASE']);
        if(!$this->db->isConnected()){
            $this->markTestSkipped(
                'No local database connection is available'
            );
        }
        else{
            $this->db->query(file_get_contents(dirname(dirname(__FILE__)).'/vendor/adamb/config/database/database_mysql.sql'));
            $this->db->query(file_get_contents(dirname(__FILE__).'/database/database.sql'));
        }
        $this->config = new Config($this->db);
        $this->logger = new EventLogger($this->db, $this->config);
    }
    
    public function tearDown(): void {
        $this->db = null;
        $this->config = null;
        $this->logger = null;
    }
    
    public function testAddEvent(){
        $this->markTestIncomplete();
    }
    
    public function testGetEvent(){
        $this->markTestIncomplete();
    }
    
    public function testCountEvent(){
        $this->markTestIncomplete();
    }
    
    public function testDeleteEvent(){
        $this->markTestIncomplete();
    }
    
    public function testChangeLimit(){
        $this->markTestIncomplete();
    }
}
