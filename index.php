<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    require __DIR__ . "/classes/$class.php"; // zorgt dat je de class die je nodig hebt kan gebruiken
});


$components = explode("/", $_SERVER["REQUEST_URI"]); // haalt alle delen van de link uit elkaar

if($components[1]!="orders"){
    http_response_code(404);
    echo "page " . $components[1] . " not found";
    exit;
}

$id = $components[2]??null; //als er geen id is gegeven is deze null

$remove = $components[3]??null; // als er remove achter het id staat is het null

if($remove!=null){ // check of er een typfout is gemaakt
    if($remove!="delete"){
        http_response_code(404);
        echo "page " . $remove . " not found";
        exit();
    }
}

if($id=="deleteAll"){
    $remove="removeAll";
}


$database = new Database(); // maak een nieuw database object

$gateway = new PizzaGateway($database); // pass het database object naar de gateway

$controller = new PizzaController($gateway); // pass de gateway naar de controller

$controller->checkRequestKind($_SERVER["REQUEST_METHOD"], $id, $remove); // kijk wat voor request het is