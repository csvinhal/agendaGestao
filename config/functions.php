<?php
include_once 'psl-config.php';
include_once 'database.class.php'; 

function sec_session_start() {
    $session_name = 'sec_session_id';   //Seta um nome aleatorio para a sessao
    $secure = SECURE;
    
    //Certificar que aa cookie da sessao nao esta acessivel via javascript.
        $httponly = true;

    // Forca a sessao a usar somente cookies, sem variaveis URL.
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            header("Location: ../error.php?err=Nao foi possivel iniciar uma sessao segura (ini_set)");
            exit();
        }
        //Recebe os atuais parametros do cookies.
        $cookieParams = session_get_cookie_params();
        // Seta os parametros
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        $time = time()+3600;
        session_set_cookie_params($time);
        // Troca o nome da sessao 
        session_name($session_name);
        // Starta a sessao
        session_start();
        // Essa linha regenera a sessao e deleta a antiga. 
        // E também gera uma nova criptografia no banco de dados. 
        session_regenerate_id(true); 
}

//verifica se o usuario ja nao se encontra logado
function login_check() {
    include_once dirname(__FILE__).'/../model/usuarioDAO.class.php';
    $database = new Database();
    $db = $database->getConnection();
    $usuarioDAO = new usuarioDAO($db);
    
    //Verifica se as sessoes estao setadas
    if(isset($_SESSION['user_id'], $_SESSION['usuario'], $_SESSION['login_string'])) {

        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];

        //Pega a string do navegador do usuario
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $usuarioDAO->searchPass($user_id)) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $password = $row['senha'];
            $login_check = hash('sha512', $password.$user_browser);

            if ($login_check === $login_string) {
               //Usuario logado
                return true;
            } else {
                //Nao logado
                return false;
            }
        } else {
            //Nao logado
            return false;
        }
    } else {
        //Nao logado
        return false;
    }
}
?>