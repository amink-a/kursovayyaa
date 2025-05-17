<?php

try{
    $database = new PDO("mysql:host=localhost;dbname=kursach;charset=utf8","root");
}catch(PDOException $error){
    die("Ошибка подключения ". $error);
}

?>