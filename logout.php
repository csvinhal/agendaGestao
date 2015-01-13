<?php
include './config/functions.php';
sec_session_start();
// Limpa todas as seções 
$_SESSION = array();
 
//Recebe parametros do cookie 
$params = session_get_cookie_params();
 
// Deleta o cookie atual. 
setcookie(session_name(),
        '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]);
 
//Desrtoi a sessão 
session_destroy();
header('Location: ./index.php');
?>