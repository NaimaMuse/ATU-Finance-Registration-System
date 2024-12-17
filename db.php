<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'abaarso_finance_regis';

try{
    $conn =new PDO("mysql:host=$host;dbname=$dbname",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO:: ERRMODE_EXCEPTION );

   
    
    // echo 'connected';
}
catch(PDOexception $e){
    echo 'connection failed ' . $e->getMessage();
}






?>
