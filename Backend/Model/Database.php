<?php
class Database
{
    protected $connection = null;
    public function __construct()
    {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
    	
            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());   
        }			
    }

    public function cud($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params );
            $stmt->close();
            return true;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }

    public function read($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params );
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);				
            $stmt->close();
            return $result;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }

    /**
     * Can range from 1 to 4 parameters
     */
    private function executeStatement($query = "" , $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );
            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }
            if( $params ) {
                $length = count($params);
                if ($length === 2) {$stmt->bind_param($params[0], $params[1]);}
                elseif ($length === 3) {$stmt->bind_param($params[0], $params[1], $params[2]);}
                elseif ($length === 4) {$stmt->bind_param($params[0], $params[1], $params[2], $params[3]);}
                elseif ($length === 5) {$stmt->bind_param($params[0], $params[1], $params[2], $params[3], $params[4]);}

            }
            $stmt->execute();
            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }	
    }
}
?>