<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controleUsuario
 *
 * @author Cristiano
 */
include_once '../config/functions.php';
sec_session_start();

if(isset($_GET['operacao'])){
    include_once '../model/usuarioDAO.class.php';
    include_once '../config/database.class.php';
    
    switch($_GET['operacao']){
        // Caso seja salvar dados
        case 'salvar':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                //Instancia o objeto usuario
                $usuario = new Usuario($db);
                
                //seta as propriedados do usuario
                $usuario->nome = $_POST['nome'];
                $usuario->sobrenome = $_POST['sobrenome'];
                $usuario->email = $_POST['email'];
                $usuario->senha = hash('sha512', $_POST['senha']);
                $usuario->idPerfil = $_POST['dlPerfil'];
               
                //cria variavel array para armazenar retorno
                $ret = array();
                
                $usuarioDAO = new usuarioDAO($db);
                
                //cria o usuário
                if($usuarioDAO->create($usuario)){   
                    $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Usuário foi criado com sucesso.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                header('location:../view/criar_usuario.php');
                }else{
                    $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Não foi possivel criar o usuario.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/criar_usuario.php');
                }
            }
        break;//Fecha case salvar
        case 'update':  
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                //Instancia o objeto usuario
                $usuario = new Usuario($db);
                
                //seta as propriedados do usuario
                $usuario->nome = $_POST['nome'];
                $usuario->sobrenome = $_POST['sobrenome'];
                $usuario->email = $_POST['email'];
                $usuario->idPerfil = $_POST['idPapel'];
                $usuario->idUsuario = $_GET['idUsuario'];
               
                //cria variavel array para armazenar retorno
                $ret = array();
                
                $usuarioDAO = new usuarioDAO($db);
                
                //cria o usuário
                if($usuarioDAO->update($usuario)){
                    $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Usuario foi atualizado.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                header('location:../view/view_usuarios.php');
                }
                else{
                    $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Não foi possível atualizar o usuário.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                header('location:../view/view_usuarios.php');
                }
            }
        break;//Fecha case update
        case 'logar':  
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                //Instancia o objeto usuario
                $usuario = new Usuario($db);
                
                //Verifica se foram informados o usuário e a senha
                if(!empty($_POST['login']) || !empty($_POST['senha'])){
                        //seta as propriedados do usuario
                        $uusuario = new Usuario;
                        $usuario->email = $_POST['login'];
                        $usuario->senha = hash('sha512', $_POST['senha']);
                        $usuarioDAO = new usuarioDAO($db);
                        $res = $usuarioDAO->logar($usuario);
                        if($res == true){
                                header('location:../view/index.php');
                        }else{
                                $_SESSION['Mensagem'] = $_SESSION['Mensagem'];;
                                header('Location: ../index.php?error=1');
                        }
                }else{
                        $_SESSION['Mensagem'] = 'Usuário ou senha não foram preenchidos!';
                        header('Location: ../index.php?error=1');
                }
            }
        break;//Fecha case logar
    }
}

if(isset($_POST['operacao'])){
    include_once '../model/usuarioDAO.class.php';
    include_once '../config/database.class.php';
    
    switch($_POST['operacao']){
        // Caso seja salvar dados
        case 'deletar':
            //Instancia uma nova conexao
            $database = new Database();
            $db = $database->getConnection();

            //Instancia o objeto usuario
            $usuario = new Usuario($db);

            //Seta o usuário que será deletado
            $usuario->idUsuario = $_POST['object_id'];
            
            //Instancia o objeto usuarioDAO
            $usuarioDAO = new usuarioDAO($db);
            
            $ret = array();

            //deleta o usuario
            if($usuarioDAO->delete($usuario)){
                $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $ret[] = "O usuário foi deletado com sucesso!";
                $ret[] = "</div>";
                $retorno = implode('', $ret);
                $_SESSION['Mensagem'] = $retorno;
            }else{
                $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $ret[] = "Não foi possível deletar o usuário!";
                $ret[] = "</div>";
                $retorno = implode('', $ret);
                $_SESSION['Mensagem'] = $retorno;
            }
        break;//Fecha case salvar
    
    }
}
?>

