<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$logger'] = Logger::getLogger('LConnectDataAccess');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

/**
 * Description of LConnectDataAccess
 * @author deepak.srikantaiah
 */

class LConnectDataAccess extends CI_Model {
    function __construct() {
       parent::__construct();
    } 

    /*
     * Query records which matches specific WHERE clause
     * TODO - ORDER BY 
     */    
    public function queryWhere($table, $whereCondition) {
        $GLOBALS['$logger']->info("Executing Query for table: ".$table);
        try {
            $this->db->initialize();
            $result = $this->db->get_where($table, $whereCondition);
            $error = $this->db->error();
            if ($error['code']!= 0) {
                $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                throw new Exception($error['message'], $error['code'], NULL);
            }
            $this->db->close();
            return $result;            
        } catch (Exception $e) {
            $GLOBALS['$logger']->info('Exception in dbFramework!!!'.PHP_EOL.$e.PHP_EOL.'Query: '.$table);
            $this->db->close();
            $msg = $e->getMessage();
            $code = $e->getCode();            
            throw new LConnectApplicationException($code, new Exception(), $msg);            
        }
    }
    
    /*
     * Query all records from given table
     * TODO Order By
     --------------------------------------Not using -------------------------------------*/
    public function queryString($queryString) {
        $GLOBALS['$logger']->info("Executing Query: ".$queryString);
        try {
            $this->db->initialize();
            $result = $this->db->get($queryString);
            $error = $this->db->error();
            if ($error['code']!= 0) {
                $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                throw new Exception($error['message'], $error['code'], NULL);
            }
            $this->db->close();
            return $result->result();
        } catch (Exception $e) {
            $GLOBALS['$logger']->info('Exception in dbFramework!!!'.PHP_EOL.$e.PHP_EOL.'Query: '.$table);
            $this->db->close();
            $msg = $e->getMessage();
            $code = $e->getCode();            
            throw new LConnectApplicationException($code, new Exception(), $msg);            
        }
    }
    /* ------------------------------------------------------------------------- */

    /*
     * This function recieves direct query (as is)
     * TODO Order By
     */
    public function query($queryString) {
        $GLOBALS['$logger']->info("Executing Query: ".$queryString);
        try {
            $this->db->initialize();
            $result = $this->db->query($queryString);
            $error = $this->db->error();
            if ($error['code']!= 0) {
                $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                throw new Exception($error['message'], $error['code'], NULL);
            }
            $this->db->close();
            return $result;
        } catch (Exception $e) {
            $GLOBALS['$logger']->info('Exception in dbFramework!!!'.PHP_EOL.$e.PHP_EOL.'Query: '.$queryString);
            $this->db->close();
            $msg = $e->getMessage();
            $code = $e->getCode();            
            throw new LConnectApplicationException($code, new Exception(), $msg);
        }
    }

     /*
     * Function for insert
     * TODO Order By
     */
    public function insert($table, $value) {
        $GLOBALS['$logger']->info("Executing Insert Query for the Table: ".$table);
        try {
            $this->db->initialize();
            $insert = $this->db->insert($table,$value);
            $error = $this->db->error();
            if ($error['code']!= 0) {
                $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                throw new Exception($error['message'], $error['code'], NULL);
            }
            $this->db->close();
            return $insert;
        } catch (Exception $e) {
            $GLOBALS['$logger']->info('Exception in dbFramework!!!'.PHP_EOL.$e.PHP_EOL.'Query: '.$table);
            $this->db->close();
            $msg = $e->getMessage();
            $code = $e->getCode();            
            throw new LConnectApplicationException($code, new Exception(), $msg);
        }
    }

     /*
     * Function for batch insert
     * TODO Order By
     */
     public function insert_batch($table, $values) {
        $GLOBALS['$logger']->info("Executing insert_batch Query for the Table: ".$table);
        try {
            $this->db->initialize();
            $insert = $this->db->insert_batch($table,$values);
            $error = $this->db->error();
            if ($error['code']!= 0) {
                $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                throw new Exception($error['message'], $error['code'], NULL);
            }
            $this->db->close();
            return $insert;
        } catch (Exception $e) {
            $GLOBALS['$logger']->info('Exception in dbFramework!!!'.PHP_EOL.$e.PHP_EOL.'Query: '.$table);
            $this->db->close();
            $msg = $e->getMessage();
            $code = $e->getCode();            
            throw new LConnectApplicationException($code, new Exception(), $msg);
        }
    }

    public function update_batch($table, $values, $conditions) {
        $GLOBALS['$logger']->info("Executing insert_batch Query for the Table: ".$table);
        try {
            $this->db->initialize();
            $update = $this->db->update_batch($table, $values, $conditions);
            $error = $this->db->error();
            if ($error['code']!= 0) {
                $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                throw new Exception($error['message'], $error['code'], NULL);
            }
            $this->db->close();
            return $update;
        } catch (Exception $e) {
            $GLOBALS['$logger']->info('Exception in dbFramework!!!'.PHP_EOL.$e.PHP_EOL.'Query: '.$table);
            $this->db->close();
            $msg = $e->getMessage();
            $code = $e->getCode();            
            throw new LConnectApplicationException($code, new Exception(), $msg);
        }
    }

    /*
     * Function for delete
     * TODO Order By
     */
    public function delete($table, $values) {
        $GLOBALS['$logger']->info("Executing Insert Query for the Table: ".$table);
        try {
            $this->db->initialize();
            $delete = $this->db->delete($table,$values);
            $error = $this->db->error();
            if ($error['code']!= 0) {
                $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                throw new Exception($error['message'], $error['code'], NULL);
            }
            $this->db->close();
            return $delete;
        } catch (Exception $e) {
            $GLOBALS['$logger']->info('Exception in dbFramework!!!'.PHP_EOL.$e.PHP_EOL.'Query: '.$table);
            $this->db->close();
            $msg = $e->getMessage();
            $code = $e->getCode();            
            throw new LConnectApplicationException($code, new Exception(), $msg);
        }
    }

    /*
     * Function for update
     * TODO Order By
     */
    public function update($table, $values, $whereCondition) {
        $GLOBALS['$logger']->info("Executing Update Query for the Table: ".$table);
        try {
            $this->db->initialize();
            $this->db->where($whereCondition);
            $update = $this->db->update($table,$values);
            $error = $this->db->error();
            if ($error['code']!= 0) {
                $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                throw new Exception($error['message'], $error['code'], NULL);
            }
            $this->db->close();
            return $update;
        } catch (Exception $e) {
            $GLOBALS['$logger']->info('Exception in dbFramework!!!'.PHP_EOL.$e.PHP_EOL.'Query: '.$table);
            $this->db->close();
            $msg = $e->getMessage();
            $code = $e->getCode();            
            throw new LConnectApplicationException($code, new Exception(), $msg);
        }
    }

    public function update_set($queryString, $values, $whereCondition) {
        $GLOBALS['$logger']->info("Executing Update Query for the Table: ".$queryString);
        try {
            $this->db->initialize();
            $this->db->set($whereCondition);
            $update = $this->db->update($queryString,$values);
            $error = $this->db->error();
            if ($error['code']!= 0) {
                $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                throw new Exception($error['message'], $error['code'], NULL);
            }
            $this->db->close();
            return $update;
        } catch (Exception $e) {
            $GLOBALS['$logger']->info('Exception in dbFramework!!!'.PHP_EOL.$e.PHP_EOL.'Query: '.$table);
            $this->db->close();
            $msg = $e->getMessage();
            $code = $e->getCode();            
            throw new LConnectApplicationException($code, new Exception(), $msg);
        }
    }

}

?>