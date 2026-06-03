<?php
    //lets connect
    $host = 'localhost';
    $db = 'database';
    $user = 'user';
    $pass = 'password';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>