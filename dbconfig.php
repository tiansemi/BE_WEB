<?php 
	$host = 'localhost';
    // $db   = 'be_web';
    $db   = 'be_web2';
    $user = 'tiansemi';
    $pass = '*ù$ù*ù$ù';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
 ?>