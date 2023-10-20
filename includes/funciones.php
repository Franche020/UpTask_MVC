<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}
// Escapa los arrays
function sArray ($arrayInput) : array { 
    $output = [];
    foreach($arrayInput as $key=>$value){
        $output[$key]= s($value);
    }
    return $output;
}
// Función que revisa que el usuario este autenticado
function isAuth() : void {
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}