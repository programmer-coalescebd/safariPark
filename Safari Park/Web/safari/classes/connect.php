<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 6:06 PM
 */
(strpos($_SERVER["REQUEST_URI"], "classes") !== false) ? exit('Direct access not allowed') : '';

class connect
{
    function __construct($host, $database, $username, $password)
    {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function sql($sql, $start, $results, $insert_id)
    {
        try {
            $conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database . ";charset=utf8", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $statement = $conn->prepare($sql);

            if ($results != 0) {
                $statement->bindParam(':start', $start, PDO::PARAM_INT);
                $statement->bindParam(':results', $results, PDO::PARAM_INT);
            }

            $statement->execute();

            if ($insert_id == 1) {
                $decision = $conn->lastInsertId();
            } else {
                $decision = $statement->fetchAll();
            }
        } catch (PDOException $e) {
            $decision = $e->getMessage();
        }

        return $decision;
    }

}