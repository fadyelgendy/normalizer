<?php

session_start();

require_once(__DIR__ . "/src/Database.php");

$db = new Database();
$data = $db->select("offers", ["*"]);

include __DIR__ . "/views/base.php";

session_destroy();