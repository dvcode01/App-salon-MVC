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

function isLast(string $actual, string $proximo): bool{
    if($actual !== $proximo){
        return true;
    }

    return false;
}

// Verificando que el usuario este autenticado
function isAuth(): void{
    if(!isset($_SESSION['login'])){
        header('Location: /'); 
    }
}

// Comprobando si es admin el usuario
function isAdmin(): void{
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}