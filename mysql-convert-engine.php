<?php

/**
 * select @@global.sql_mode;
 * /etc/my.cnf
 * sql_mode=
 */

$dbname = '';
$username = '';
$password = '';

$conn = new mysqli('localhost', $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . $dbname . "' AND ENGINE = 'MyISAM'";

$result = $conn->query($sql);

while($row = $result->fetch_row())
{
    $table = $row[0];
    $sql = "ALTER TABLE `$table` ENGINE=InnoDB";
    $conn->query($sql);
}

$result->free();

$conn->close();

echo "Connected successfully" . PHP_EOL;
