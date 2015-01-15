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

$operacao = filter_input(INPUT_GET,'operacao', FILTER_SANITIZE_STRING);
if(isset($operacao)){
    include_once '../model/usuarioDAO.class.php';
    include_once '../config/database.class.php';
    include_once '../validate/Validate.class.php';
    
    switch($operacao){
        // Caso seja salvar dados
        case 'salvar':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                //cria variavel array para armazenar retorno
                $error = array();
                
                $nome = filter_input(INPUT_POST,'nome', FILTER_SANITIZE_STRING);
                $sobrenome = filter_input(INPUT_POST,'sobrenome', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
                $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
                $conSenha = filter_input(INPUT_POST, 'conSenha', FILTER_SANITIZE_STRING);
                $papel = filter_input(INPUT_POST,'dlPapel', FILTER_SANITIZE_STRING);
                $ativo = filter_input(INPUT_POST,'ativo', FILTER_SANITIZE_NUMBER_INT);
                
                if(Validate::validarNome($nome) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validateEmail($email) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarSenha($senha) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::confirmaSenha($senha, $conSenha) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                
                if(count($error) > 0){
                    $retorno = implode('', $error);
                    $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/criar_usuario.php');
                }else{
                    //Instancia o objeto usuario
                    $usuario = new Usuario($db);

                    //Instancia o objeto usuarioDAO
                    $usuarioDAO = new usuarioDAO($db);
                
                    //seta as propriedados do usuario
                    $usuario->nome = $nome;
                    $usuario->sobrenome = $sobrenome;
                    $usuario->email = $email;
                    if($ativo == '1'){
                        $usuario->ativo = TRUE;
                    }else{
                        $usuario->ativo = FALSE;
                    }
                    
                    //Seta usuario salta com um valor randomico
                    $usuario->salt = $usuarioDAO->createSalt();
                    $usuario->senha = hash('sha512', $senha.$usuario->salt);
                    $usuario->idPapel = $papel;

                    //cria o usuario
                    if($usuarioDAO->create($usuario)){
                        $error[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $error[] = "Usu&aacute;rio foi criado com sucesso.";
                        $error[] = "</div>";
                        $retorno = implode('', $error);
                        $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/criar_usuario.php');
                    }else{
                        $error[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $error[] = "N&atilde;o foi poss&iacute;vel criar o usu&aacute;rio.";
                        $error[] = "</div>";
                        $retorno = implode('', $error);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_usuario.php');
                    }
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
                
                $nome = filter_input(INPUT_POST,'nome', FILTER_SANITIZE_STRING);
                $sobrenome = filter_input(INPUT_POST,'sobrenome', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
                $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
                $conSenha = filter_input(INPUT_POST, 'conSenha', FILTER_SANITIZE_STRING);
                $idPapel = filter_input(INPUT_POST,'idPapel', FILTER_SANITIZE_STRING);
                $idUsuario = filter_input(INPUT_GET,'idUsuario', FILTER_SANITIZE_NUMBER_INT);
                $ativo = filter_input(INPUT_POST,'ativo', FILTER_SANITIZE_NUMBER_INT);
                
                if(Validate::validarNome($nome) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarSobreNome($sobrenome) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validateEmail($email) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::confirmaSenha($senha, $conSenha) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                
                if(count($error) > 0){
                    $retorno = implode('', $error);
                    $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/update_usuario.php?idUsuario='.$idUsuario);
                }else{
                    //seta as propriedados do usuario
                    $usuario->nome = $nome;
                    $usuario->sobrenome = $sobrenome;
                    $usuario->email = $email;
                    $usuario->idPapel = $idPapel;
                    $usuario->idUsuario = $idUsuario;
                    
                    if($ativo == '1'){
                        $usuario->ativo = TRUE;
                    }else{
                        $usuario->ativo = FALSE;
                    }
                    
                    //cria variavel array para armazenar retorno
                    $error = array();

                    $usuarioDAO = new usuarioDAO($db);
                        
                    if(Validate::verificaVazio($senha) === true){
                        if($usuarioDAO->update($usuario)){
                            $error[] = "<div class=\"alert alert-success alert-dismissable\">";
                            $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                            $error[] = "Usu&aacute;rio foi atualizado com sucesso.";
                            $error[] = "</div>";
                            $retorno = implode('', $error);
                            $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/view_usuarios.php');
                        }else{
                            $error[] = "<div class=\"alert alert-danger alert-dismissable\">";
                            $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                            $error[] = "N&atilde;o foi poss&iacute;vel atualizar o usu&aacute;rio.";
                            $error[] = "</div>";
                            $retorno = implode('', $error);
                            $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/update_usuario.php?idUsuario='.$idUsuario);
                        }
                    }else{
                        $usuarioDAO->readSalt($usuario);
                        $usuario->senha = hash('sha512', $senha.$usuario->salt);
                        if($usuarioDAO->fullUpdate($usuario)){
                            $error[] = "<div class=\"alert alert-success alert-dismissable\">";
                            $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                            $error[] = "Usu&aacute;rio foi atualizado com sucesso.";
                            $error[] = "</div>";
                            $retorno = implode('', $error);
                            $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/view_usuarios.php');
                        }else{
                            $error[] = "<div class=\"alert alert-danger alert-dismissable\">";
                            $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                            $error[] = "N&atilde;o foi poss&iacute;vel atualizar o usu&aacute;rio.";
                            $error[] = "</div>";
                            $retorno = implode('', $error);
                            $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/update_usuario.php?idUsuario='.$idUsuario);
                        }  
                    }
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
                
                //Verifica se foram informados o usuario e a senha
                if(!empty($_POST['login']) || !empty($_POST['senha'])){
                        //seta as propriedados do usuario
                        $uusuario = new Usuario;
                        $usuario->email = filter_input(INPUT_POST,'login', FILTER_SANITIZE_EMAIL);
                        $usuario->senha = filter_input(INPUT_POST,'senha', FILTER_SANITIZE_STRING);
                        $usuarioDAO = new usuarioDAO($db);
                        $res = $usuarioDAO->logar($usuario);
                        if($res === true){
                            header('Location:../view/index.php');
                        }else{
                            $_SESSION['Mensagem'] = $_SESSION['Mensagem'];
                            header('Location: ../index.php');
                        }
                }else{
                        $_SESSION['Mensagem'] = 'Usu&aacute;rio ou senha n&atilde;o foram preenchidos';
                        header('Location: ../index.php');
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
            
            $error = array();

            //deleta o usuario
            if($usuarioDAO->delete($usuario)){
                $error[] = "<div class=\"alert alert-success alert-dismissable\">";
                $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $error[] = "O usu&aacute;rio foi deletado com sucesso!";
                $error[] = "</div>";
                $retorno = implode('', $error);
                $_SESSION['Mensagem'] = $retorno;
            }else{
                $error[] = "<div class=\"alert alert-danger alert-dismissable\">";
                $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $error[] = "N&atilde;o foi poss&iacute;vel deletar o usu&aacute;rio!";
                $error[] = "</div>";
                $retorno = implode('', $error);
                $_SESSION['Mensagem'] = $retorno;
            }
        break;//Fecha case salvar
    
    }
}
?>