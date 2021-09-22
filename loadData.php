<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

if($_SERVER['REQUEST_METHOD'] === 'GET'){
      $body = file_get_contents('toDoList.txt');
      echo $body;
   }
else
   echo json_encode(array('status' => 'fall'));

?>