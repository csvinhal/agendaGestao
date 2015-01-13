<?php
include_once 'psl-config.php';
include_once 'database.class.php';

function sec_session_start() {
    $session_name = 'sec_session_id';   //Seta um nome aleatorio para a sessao
    $secure = SECURE;
    // Impedir que o JavaScript acesso o ID da sessão
    $httponly = true;
    // Forca a sessão a somente utilizar cookies
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Nao foi possivel iniciar uma sessao segura (ini_set)");
        exit();
    }
    //Recebe os atuais parametros do cookies.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    
    //Seta o nome da sessao.
    session_name($session_name);
    
    session_start();            // Inicia a sessao 
    session_regenerate_id();    //Regenera a sessao, deleta a antiga. 
}

//verifica se o usuario ja nao se encontra logado
function login_check() {
    include_once '../model/usuarioDAO.class.php';
    $database = new Database();
    $db = $database->getConnection();
    $usuarioDAO = new usuarioDAO($db);

    //Verifica se as sessoes estao setadas
    if(isset($_SESSION['user_id'], $_SESSION['usuario'], $_SESSION['login_string'])) {
        $_SESSION['teste'] = "DENTRO ISSET";

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
                $_SESSION['teste'] = "Usuario Logado";
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