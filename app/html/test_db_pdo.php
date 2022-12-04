<?php

$DBuser = 'root';
$DBpass = 'nghia@tv';
$pdo = null;

try{
    $database = 'mysql:host=localhost';
    $pdo = new PDO($database, $DBuser, $DBpass);
    echo "Success => Looking good, php connect to mysql. OK";    
} catch(PDOException $e) {
    echo "php connect to mysql failed with:\n $e";
}

$pdo = null;

// $client = new MongoDB\Client('mongodb://root:nghiatv@localhost:27017/?authMechanism=DEFAULT');  
// try{ 
//     $dbs = $client->listDatabases(); 
//     echo '<pre>';
//     print_r($dbs);
//     echo '</pre>';
//     echo "Success => Looking good, php connect to mongoDB. OK";    
//     $client = null;
//     // Or Nothing if you just wanna check for errors 
// }
// catch(Exception $e){
//     echo "Unable to connect to Database at the moment, sorry ! ";
//     exit();
// }

echo  nl2br ("\n");
try {
    // connect to OVHcloud Public Cloud Databases for MongoDB (cluster in version 4.4, MongoDB PHP Extension in 1.8.1)
    $m = new MongoDB\Driver\Manager('mongodb://root:nghiatv@localhost:27017/?authMechanism=DEFAULT');
    // $m = new MongoDB\Client('mongodb+srv://root:nghiatv@localhost:27017/?authMechanism=DEFAULT');
    echo "Success => Connection to database Mongodb successfully";
    // display the content of the driver, for diagnosis purpose
    var_dump($m);
}
catch (Throwable $e) {
    // catch throwables when the connection is not a success
    echo "SORRY => Captured Throwable for connection : " . $e->getMessage() . PHP_EOL;
}