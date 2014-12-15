<?php
include_once 'psl-config.php';
 
function sec_session_start() {
    $session_name = 'sec_session_id';   //Seta um nome aleatório para a sessão
    $secure = SECURE;
    // Impedir que o JavaScript acesso o ID da sessão
    $httponly = true;
    // Força a sessão a somente utilizar cookies
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    //Recebe os atuais parametros do cookies.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    
    //Seta o nome da sessão.
    session_name($session_name);
    
    /* Define o limitador de cache para 'private' */
    session_cache_limiter('private');
    $cache_limiter = session_cache_limiter();
    /* Define o limite de tempo do cache em 30 minutos */ 
    session_cache_expire(15); 
    $cache_expire = session_cache_expire();
    
    session_start();            // Inicia a sessão 
    session_regenerate_id();    //Regenera a sessão, deleta a antiga. 
}