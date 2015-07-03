<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controleCliente
 *
 * @author Cristiano
 */
include_once '../config/functions.php';
sec_session_start();

$operacao = filter_input(INPUT_GET,'operacao', FILTER_SANITIZE_URL);

if(isset($operacao)){
    include_once '../config/database.class.php';
    include_once '../model/tipoAlocacaoDAO.class.php';
    include_once '../model/TipoAlocacao.class.php';
    
    switch($operacao){
        // Caso seja salvar dados
        case 'salvar':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                $error = array();
                $desTipAloc = filter_input(INPUT_POST,'desTipAloc', FILTER_SANITIZE_STRING);
                $ativo = filter_input(INPUT_POST,'ativo', FILTER_SANITIZE_STRING);
                
                $objTipAloc = new TipoAlocacao($db);
                $objTipAloc->desAloc = $desTipAloc;
                $objTipAloc->ativo = $ativo;
                
                $objTpAlocDAO = new tipoAlocacaoDAO($db);
                if($objTpAlocDAO->insereTpAlocacao($objTipAloc)){
                    $error[] = "<div class=\"alert alert-success alert-dismissable\">";
                    $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $error[] = "O tipo de aloca&ccedil;&atilde;o foi criado com sucesso.";
                    $error[] = "</div>";
                    $retorno = implode('', $error);
                    $_SESSION['Mensagem'] = $retorno;

                    if($_SESSION['perfil'] == 'A'){
                        header('location:../view/administrador/criar_tipoAlocacao.php');
                    }else if($_SESSION['perfil'] == 'P'){
                        header('location:../view/pmo/criar_tipoAlocacao.php');
                    }
                }else{
                    $error[] = "<div class=\"alert alert-danger alert-dismissable\">";
                    $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $error[] = "N&atilde;o foi poss&iacute;vel criar o tipo de aloca&ccedil;&atilde;o.";
                    $error[] = "</div>";
                    $retorno = implode('', $error);
                    $_SESSION['Mensagem'] = $retorno;

                    if($_SESSION['perfil'] == 'A'){
                        header('location:../view/administrador/criar_tipoAlocacao.php');
                    }else if($_SESSION['perfil'] == 'P'){
                        header('location:../view/pmo/criar_tipoAlocacao.php');
                    }
                }             
            }
        break;
            
        case 'update':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                $error = array();
                
                $idTipAloc = filter_input(INPUT_GET, 'idTipAloc', FILTER_SANITIZE_NUMBER_INT);
                $desTipAloc = filter_input(INPUT_POST,'desTipAloc', FILTER_SANITIZE_STRING);
                $ativo = filter_input(INPUT_POST,'ativo', FILTER_SANITIZE_STRING);
               
                $objTipAloc = new TipoAlocacao($db);
                $objTpAlocDAO = new tipoAlocacaoDAO($db);
                
                $objTipAloc->idTipAloc = $idTipAloc;
                $objTipAloc->desAloc = $desTipAloc;
                $objTipAloc->ativo = $ativo;
                if($objTpAlocDAO->atualizaTpAlocacao($objTipAloc)){
                    $error[] = "<div class=\"alert alert-success alert-dismissable\">";
                    $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $error[] = "O tipo de aloca&ccedil;&atilde;o foi atualizado com sucesso.";
                    $error[] = "</div>";
                    $retorno = implode('', $error);
                    $_SESSION['Mensagem'] = $retorno;
                    
                    if($_SESSION['perfil'] == 'A'){
                        header('location:../view/administrador/criar_tipoAlocacao.php');
                    }else if($_SESSION['perfil'] == 'P'){
                        header('location:../view/pmo/criar_tipoAlocacao.php');
                    }
                }else{
                    $error[] = "<div class=\"alert alert-danger alert-dismissable\">";
                    $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $error[] = "Não foi possivel atualizar o tipo de aloca&ccedil;&atilde;o.";
                    $error[] = "</div>";
                    $retorno = implode('', $error);
                    $_SESSION['Mensagem'] = $retorno;

                    if($_SESSION['perfil'] == 'A'){
                        header('location:../view/administrador/criar_tipoAlocacao.php');
                    }else if($_SESSION['perfil'] == 'P'){
                        header('location:../view/pmo/criar_tipoAlocacao.php');
                    }
                }
            }
        }
    }
