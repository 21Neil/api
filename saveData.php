<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
   if($_SERVER['CONTENT_TYPE'] === 'application/json'){
      $body = file_get_contents('php://input');
      file_put_contents('toDoList.txt', $body);
      echo json_encode(array('status' => 'success'));
   }}
else
   echo json_encode(array('status' => 'fall'));

?>