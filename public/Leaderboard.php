<?php

/*
 * The Leaderboard API Class
 * This is the main class that provides all the functionality for the Leaderboard API
 *
 */

class Leaderboard
{
    // Setting up all the initial variables
    private $DB;
    private $mysql_servername = "35.183.183.180";
    private $mysql_username = 'canadadrives';
    private $mysql_db_name = 'canadadrives';
    private $mysql_table_name = 'players';
    private $mysql_password = 'canadadrives1234';

    function __construct()
    {
        $this->setupDB();
    }

    function setupDB()
    {
        try {
            // Setting up the database connection for use across the class
            $this->DB = new mysqli($this->mysql_servername, $this->mysql_username, $this->mysql_password);;
            if ($this->DB->connect_error) {
                throw new Exception('Connection failed:' . $this->DB->connect_error);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    // The listAllPlayers function lists all players from highest scoring to lowest.
    function listAllPlayers()
    {
        try {
            $db = $this->DB;
            $currentTable = "$this->mysql_db_name.$this->mysql_table_name";
            $query = "SELECT player_id, player_name, score FROM $currentTable ORDER BY score DESC";
            $resultRows = mysqli_query($db, $query);
            foreach ($resultRows as $resultRow) {
                $resultJSON[] = $resultRow;
            }
            return json_encode($resultJSON, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $error['error'] = "Error during list all query";
            return json_encode($error, JSON_PRETTY_PRINT);
        }
    }

    // Increases the players score by one point...
    function increasePlayerScore($playerId)
    {
        try {
            $db = $this->DB;
            $currentTable = "$this->mysql_db_name.$this->mysql_table_name";
            $query = "UPDATE $currentTable 
                      SET score = score +1 
                      WHERE player_id = $playerId ";
            $result = mysqli_query($db, $query);

            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $error['error'] = "Error during Increase score query";
            return json_encode($error, JSON_PRETTY_PRINT);
        }
    }

    // Decreases the players score by a point
    function decreasePlayerScore($playerId)
    {
        try {
            $db = $this->DB;
            $currentTable = "$this->mysql_db_name.$this->mysql_table_name";
            $query = "UPDATE $currentTable 
                      SET score = score -1 
                      WHERE player_id = $playerId ";
            $result = mysqli_query($db, $query);

            return json_encode($result, JSON_PRETTY_PRINT);
        } // TODO: handle the exception here...
        catch (Exception $e) {
            $error['error'] = "Error during score decrease query";
            return json_encode($error, JSON_PRETTY_PRINT);
        }
    }

    // Add a new player to the system...
    function addNewPlayer($userInfoArray)
    {
        try {
            $db = $this->DB;
            $currentTable = "$this->mysql_db_name.$this->mysql_table_name";
            // Obtaining all values from the passed in Player info Array...
            if (array_key_exists("playerName", $userInfoArray)) $playerName = $userInfoArray["playerName"];
            if (array_key_exists("playerAddress", $userInfoArray)) $playerAddress = $userInfoArray["playerAddress"];
            if (array_key_exists("playerAge", $userInfoArray)) $playerAge = $userInfoArray["playerAge"];

            // Query that does the actual insert into the table...
            $query = "INSERT INTO $currentTable 
                      (player_name, age, address)
                      VALUES 
                      ('$playerName', $playerAge, '$playerAddress')";
            $result = mysqli_query($db, $query);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $error['error'] = "Error during new player addition query";
            return json_encode($error, JSON_PRETTY_PRINT);
        }
    }

    // Delete a player from the system.
    function deletePlayer($playerId)
    {
        try {
            $db = $this->DB;
            $currentTable = "$this->mysql_db_name.$this->mysql_table_name";
            $query = "DELETE FROM $currentTable 
                      WHERE player_id = $playerId ";
            $result = mysqli_query($db, $query);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $error['error'] = "Error during deletion query";
            return json_encode($error, JSON_PRETTY_PRINT);
        }
    }

    // Get all the specific player detials by userId
    function getPlayerDetails($userId)
    {
        try {
            $db = $this->DB;
            $currentTable = "$this->mysql_db_name.$this->mysql_table_name";
            $query = "SELECT player_id, player_name, age, score, address FROM $currentTable WHERE player_id = $userId";
            $resultRows = mysqli_query($db, $query);
            if (isset($player[0])) {
                $player = $player[0];
            }
            else return json_encode("Player not found", JSON_PRETTY_PRINT);

            return json_encode($player, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $error['error'] = "Error during getting player details query";
            return json_encode($error, JSON_PRETTY_PRINT);
        }
    }

}