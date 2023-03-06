<?php

// Identifiants de la base de données
$host = "localhost";
$dbname = "blitz";
$username = "root";
$password = "";

// Initialisation d'une connexion à la base de données en PDO
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Fonction qui permet d'exécuter des requêtes SQL
function dbQuery($sql) {
	global $db;
	$query = $db->prepare("$sql");
    $query->execute();

	return $query;
}

// Fetch simple du résultat
function dbFetch($result) {
	return $result->fetch(PDO::FETCH_ASSOC);
}

// Fetch All
function dbFetchAll($result) {
	return $result->fetchAll();
}

// Nombre de lignes
function dbRowCount($result) {
    return $result->rowCount();
}