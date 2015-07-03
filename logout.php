<?php
include './config/functions.php';

sec_session_start();
// Limpa todas as secoes 
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

//Desrtoi a sessao 
session_destroy();
header('Location: ./index.php');
?>