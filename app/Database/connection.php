<?php

function getConnetion()
{
  $connect = [
    "prod" => [
      "dsn" => "mysql:host=localhost;dbname=sistem_atm_sederhana",
      "username" => "root",
      "password" => ""
    ],
    "test" => [
      "dsn" => "mysql:host=localhost;dbname=sistem_atm_sederhana_test",
      "username" => "root",
      "password" => ""
    ]
  ];
  return $connect;
}
