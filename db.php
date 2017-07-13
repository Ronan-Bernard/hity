<?php

/**
 * Handles DB connection
 * basic, only to remain framework-agnostic
 */
require_once('credentials.php');

try {
    $pdo = new PDO($dbType . ':host=' . $host . ';dbname=' . $db, $user, $pass);
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}