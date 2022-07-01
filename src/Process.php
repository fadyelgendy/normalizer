<?php

require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/Session.php");
require_once(__DIR__ . "/Normalizer.php");

$db = new Database();
$pdo = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // get form data
    $form_data = $_POST;

    if (empty($form_data['title'])) {
        $session = new Session("title", "title is required");
        header("Location: ../index.php");
    }

    if (empty($form_data['description'])) {
        $session = new Session("description", "description is required");
        header("Location: ../index.php");
    }

    if (!empty($form_data['title']) && !empty($form_data['description'])) {
        if (isset($form_data['normalize']) && $form_data["normalize"]) {
            $normalizer = new Normalizer();
            $normalizer->handleFormData($form_data);
        } else {
            $db->insert("offers", $form_data);
        }
    }
}


// Normalize
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['target']) && $_GET['target'] == "normalize") {
    $normalizer = new Normalizer();
    $normalizer->handle();
}

// Load Test Data
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['target']) && $_GET['target'] == "load-data") {
    $db->insert("offers", [
        "title" => "AssisiOAssisifAssisifAssisirAssisiiAssisi AssisisAssisieAssisirAssisivAssisiiAssisizAssisiiAssisi AssisidAssisiiAssisi AssisitAssisirAssisiaAssisisAssisilAssisioAssisicAssisioAssisi AssisiaAssisi AssisiCAssisieAssisirAssisiiAssisigAssisinAssisioAssisilAssisiaAssisi AssisicAssisioAssisinAssisi AssisiEAssisirAssisinAssisieAssisisAssisitAssisioAssisi
        ", 
        "description" => ""
    ]);

    $db->insert("offers", [
        "title" => "This title is good enough. Corsico.Still is good, Corsico.", 
        "description" => "FormigineGFormigineoFormigineoFormigined FormiginejFormigineoFormigineb."
    ]);

    $db->insert("offers", [
        "title" => "FormigineGFormigineoFormigineoFormigined FormiginejFormigineoFormigineb.", 
        "description" => "FormigineGFormigineoFormigineoFormigined FormiginejFormigineoFormigineb."
    ]);

    $session = new Session("success", "Test Data Loaded Succcessfully");
    header("Location: ../index.php");
}
