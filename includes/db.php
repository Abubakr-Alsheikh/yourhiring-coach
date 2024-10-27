<?php

// Database Configuration
$host = "localhost"; // Replace with your database host
$dbname = "coach_database"; // Replace with your database name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password

// $host = "localhost"; // Replace with your database host
// $dbname = "u856517976_coach_database"; // Replace with your database name
// $username = "u856517976_coach"; // Replace with your database username
// $password = "Y/+@b&?v4"; // Replace with your database password

// Create a connection to the database
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


/**
 * Executes a SQL query and returns the result.
 *
 * @param string $query The SQL query to execute.
 * @param array $params (optional) An array of parameters to bind to the query.
 * @return PDOStatement|bool The result of the query (PDOStatement) or false on failure.
 */
function executeQuery($query, $params = []) {
    global $conn; // Access the global database connection

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        echo "Query failed: " . $e->getMessage();
        die();
        return false;
    }
}


/**
 * Executes a SQL query and returns the result as an associative array.
 *
 * @param string $query The SQL query to execute.
 * @param array $params (optional) An array of parameters to bind to the query.
 * @return array|bool The result of the query as an associative array or false on failure.
 */
function getResults($query, $params = []) {
    $stmt = executeQuery($query, $params);
    if ($stmt) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

?> 