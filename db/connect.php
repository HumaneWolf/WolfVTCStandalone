<?php

//Config file must be required for this to work.

global $sql;

$sql = new mysqli($mysql['server'], $mysql['user'], $mysql['password'], $mysql['database']);

if ($sql->connect_error) {
  include 'dcerror.php';
  die();
}