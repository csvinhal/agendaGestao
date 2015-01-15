<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controleAlocacao
 *
 * @author Cristiano
 */

include_once '../config/functions.php';
sec_session_start();

$operacao = filter_input(INPUT_GET,'operacao', FILTER_SANITIZE_URL);

if(isset($operacao)){
    include_once '../model/alocacaoDAO.class.php';
    include_once '../config/database.class.php';
    
    switch($operacao){
        // Caso seja salvar dados
        case 'salvar':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                $idColaborador = filter_input(INPUT_POST,'idColaborador', FILTER_SANITIZE_NUMBER_INT);
                $idCliente = filter_input(INPUT_POST,'idCliente', FILTER_SANITIZE_NUMBER_INT);
                $desAlocacao = filter_input(INPUT_POST,'desAlocacao', FILTER_SANITIZE_STRING);
                $dataAloc = filter_input(INPUT_POST, 'dataAlocacao', FILTER_SANITIZE_STRING);
                $tipAloc = filter_input(INPUT_POST,'alocacao', FILTER_SANITIZE_STRING);
                $periodo = filter_input(INPUT_POST,'periodo', FILTER_SANITIZE_STRING);
                $confirmado = filter_input(INPUT_POST,'confirmado', FILTER_SANITIZE_STRING);
                
                //Instancia o objeto alocacao
                $alocacao = new Alocacao($db);
                
                //seta as propriedados do alocacao
                $alocacao->idColaborador = $idColaborador;
                $alocacao->idCliente = $idCliente;
                $alocacao->desAlocacao = $desAlocacao;
                $alocacao->idTipAloc = $tipAloc;
                $alocacao->periodo = $periodo;
                $alocacao->confirmado = $confirmado;
                $alocacao->idUsuario = $_SESSION['user_id'];
                
                //cria variavel array para armazenar retorno
                $ret = array();
                
                $alocacaoDAO = new alocacaoDAO($db);
                
                //converte a data pt-BR para en
                $alocacao->dataAlocacao = $alocacaoDAO->date_converter($dataAloc);
                
                //Verifica qual o período da alocação e insere de acordo
                if($alocacao->periodo == 'M'){
                    $alocacao->horaInicio = '08:00';
                    $alocacao->horaFim = '12:00';
                    //cria a alocacao
                    if($alocacaoDAO->create($alocacao)){   
                        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "Aloca&ccedil;&atilde;o foi inserida com sucesso.";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }else{
                        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "N&atilde;o foi poss&iacute;vel criar a aloca&ccedil;&atilde;o";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }
                }else if($alocacao->periodo == 'V'){
                    $alocacao->horaInicio = '14:00';
                    $alocacao->horaFim = '18:00';
                    if($alocacaoDAO->create($alocacao)){   
                        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "Aloca&ccedil;&atilde;o foi inserida com sucesso.";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }else{
                        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "N&atilde;o foi poss&iacute;vel criar a aloca&ccedil;&atilde;o";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }
                } else if($alocacao->periodo == 'I'){
                    $i=0;
                    $alocacao->horaInicio = '08:00';
                    $alocacao->horaFim = '12:00';
                    for($i = 0; $i<=1; $i++){
                        //cria o usuario
                        if($alocacaoDAO->create($alocacao)){   
                            $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                            $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                            $ret[] = "Aloca&ccedil;&atilde;o foi inserida com sucesso.";
                            $ret[] = "</div>";
                            $retorno = implode('', $ret);
                            $_SESSION['Mensagem'] = $retorno;
                        }else{
                            $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                            $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                            $ret[] = "N&atilde;o foi poss&iacute;vel criar a aloca&ccedil;&atilde;o";
                            $ret[] = "</div>";
                            $retorno = implode('', $ret);
                            $_SESSION['Mensagem'] = $retorno;
                            header('location:../view/criar_alocacao.php');
                        }
                        //seta novo horario
                        $alocacao->horaInicio = '14:00';
                        $alocacao->horaFim = '18:00';
                    }
                    header('location:../view/criar_alocacao.php');
                }
            }
        break;//Fecha case salvar
        case 'update':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                $idColaborador = filter_input(INPUT_POST,'idColaborador', FILTER_SANITIZE_NUMBER_INT);
                $idCliente = filter_input(INPUT_POST,'idCliente', FILTER_SANITIZE_NUMBER_INT);
                $desAlocacao = filter_input(INPUT_POST,'desAlocacao', FILTER_SANITIZE_STRING);
                $dataAloc = filter_input(INPUT_POST, 'dataAlocacao', FILTER_SANITIZE_STRING);
                $tipAloc = filter_input(INPUT_POST,'idTipAloc', FILTER_SANITIZE_STRING);
                $periodo = filter_input(INPUT_POST,'periodo', FILTER_SANITIZE_STRING);
                $confirmado = filter_input(INPUT_POST,'confirmado', FILTER_SANITIZE_STRING);
                
                //Instancia o objeto alocacao
                $alocacao = new Alocacao($db);
                
                //Instancia o objeto alocacao
                $chave = new Alocacao($db);
                
                //seta as propriedados do alocacao
                $alocacao->idColaborador = $idColaborador;
                $alocacao->idCliente = $idCliente;
                $alocacao->desAlocacao = $desAlocacao;
                $alocacao->idTipAloc = $tipAloc;
                $alocacao->periodo = $periodo;
                $alocacao->confirmado = $confirmado;
                $alocacao->dataAlocacao = $dataAloc;
                $alocacao->idUsuario = $_SESSION['user_id'];
                
                $ret = array();
                
                $alocacaoDAO = new alocacaoDAO($db);
                
                $chave->desAlocacao = $_GET['data'];
                $chave->horaInicio = $_GET['horaIni'];
                $chave->horaFim = $_GET['horaFim'];
                $chave->idColaborador = $_GET['idCol'];
                
                if($alocacao->periodo == 'M'){
                    $alocacao->horaInicio = '08:00';
                    $alocacao->horaFim = '12:00';
                    //cria a alocacao
                    if($alocacaoDAO->update($alocacao, $chave)){   
                        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "Aloca&ccedil;&atilde;o foi alterada com sucesso.";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }else{
                        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "N&atilde;o foi poss&iacute;vel atualizar a aloca&ccedil;&atilde;o.";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }
                }else if($alocacao->periodo == 'V'){
                    $alocacao->horaInicio = '14:00';
                    $alocacao->horaFim = '18:00';
                    if($alocacaoDAO->update($alocacao, $chave)){   
                        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "Aloca&ccedil;&atilde;o foi alterada com sucesso.";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }else{
                        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "N&atilde;o foi poss&iacute;vel atualizar a alocac&ccedil;&atilde;o.";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }
                }
            }
        break;  
    }
}

if($_POST['operacao']){
    include_once '../model/alocacaoDAO.class.php';
    include_once '../config/database.class.php';
    
    switch($_POST['operacao']){
        // Caso seja salvar dados
        case 'deletar':
            //Instancia uma nova conexao
            $database = new Database();
            $db = $database->getConnection();

            //Instancia o objeto alocacao
            $alocacao = new Alocacao($db);

            //Seta os dados da alocacao que sera deletada
            $alocacao->dataAlocacao = $_POST['object_data'];
            $alocacao->idColaborador = $_POST['object_col'];
            $alocacao->horaInicio = $_POST['object_ini'];
            $alocacao->horaFim = $_POST['object_fim'];
            
            //Instancia o objeto alocacaoDAO
            $alocacaoDAO = new alocacaoDAO($db);
            
            //cria variavel array para armazenar retorno
            $ret = array();

            //deleta o alocacao
            if($alocacaoDAO->delete($alocacao)){
                $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $ret[] = "A aloca&ccedil;&atilde;o foi deletada com sucesso!";
                $ret[] = "</div>";
                $retorno = implode('', $ret);
                $_SESSION['Mensagem'] = $retorno;
                
            }else{
                $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $ret[] = "N&atilde;o foi poss&iacute;vel deletar a aloca&ccedil;&atilde;o!";
                $ret[] = "</div>";
                $retorno = implode('', $ret);
                $_SESSION['Mensagem'] = $retorno; 
            }
        break;//Fecha case salvar
    
    }
}
?>