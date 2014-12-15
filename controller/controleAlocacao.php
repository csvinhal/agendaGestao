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

if(isset($_GET['operacao'])){
    include_once '../model/alocacaoDAO.class.php';
    include_once '../config/database.class.php';
    
    switch($_GET['operacao']){
        // Caso seja salvar dados
        case 'salvar':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                //Instancia o objeto alocacao
                $alocacao = new Alocacao($db);
                
                //seta as propriedados do alocacao
                $alocacao->idColaborador = $_POST['idColaborador'];
                $alocacao->idCliente = $_POST['idCliente'];
                $alocacao->desAlocacao = $_POST['desAlocacao'];
                $alocacao->periodo = $_POST['periodo'];
                $alocacao->confirmado = $_POST['confirmado'];
                $alocacao->idUsuario = '12';
                //cria variavel array para armazenar retorno
                $ret = array();
                
                $alocacaoDAO = new alocacaoDAO($db);
                
                //converte a data pt-BR para en
                $alocacao->dataAlocacao = $alocacaoDAO->date_converter($_POST['dataAlocacao']);
                
                //Verifica qual o período da alocação e insere de acordo
                if($alocacao->periodo = 'M'){
                    $alocacao->horaInicio = '08:00';
                    $alocacao->horaFim = '12:00';
                    //cria o usuário
                    if($alocacaoDAO->create($alocacao)){   
                        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "Alocação foi inserida com sucesso.";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }else{
                        $ret[] = $alocacao->idColaborador;
                        $ret[] = $alocacao->idCliente;
                        $ret[] = $alocacao->desAlocacao;
                        $ret[] = $alocacao->dataAlocacao;
                        $ret[] = $alocacao->periodo;
                        $ret[] = $alocacao->horaInicio;
                        $ret[] = $alocacao->horaFim;
                        $ret[] = $alocacao->confirmado;
                        $ret[] = $alocacao->idUsuario;
                        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "Não foi possivel criar a alocacao.";
                        $ret[] = "</div>";
                        $retorno = implode('<br />', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }
                } else if($alocacao->periodo = 'V'){
                    $alocacao->horaInicio = '13:30';
                    $alocacao->horaFim = '18:00';
                    if($alocacaoDAO->create($alocacao)){   
                        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "Alocação foi inserida com sucesso.";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }else{
                        $ret[] = $alocacao->idColaborador;
                        $ret[] = $alocacao->idCliente;
                        $ret[] = $alocacao->desAlocacao;
                        $ret[] = $alocacao->dataAlocacao;
                        $ret[] = $alocacao->periodo;
                        $ret[] = $alocacao->horaInicio;
                        $ret[] = $alocacao->horaFim;
                        $ret[] = $alocacao->confirmado;
                        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "Não foi possivel criar a alocacao.";
                        $ret[] = "</div>";
                        $retorno = implode('<br />', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_alocacao.php');
                    }
                }else if($alocacao->periodo = 'I'){
                    $i=0;
                    $alocacao->horaInicio = '08:00';
                    $alocacao->horaFim = '12:00';
                    for($i = 0; $i<=1; $i++){
                        //cria o usuário
                        if($alocacaoDAO->create($alocacao)){   
                            $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                            $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                            $ret[] = "Alocação foi inserida com sucesso.";
                            $ret[] = "</div>";
                            $retorno = implode('', $ret);
                            $_SESSION['Mensagem'] = $retorno;
                        }else{
                            $ret[] = $alocacao->idColaborador;
                            $ret[] = $alocacao->idCliente;
                            $ret[] = $alocacao->desAlocacao;
                            $ret[] = $alocacao->dataAlocacao;
                            $ret[] = $alocacao->periodo;
                            $ret[] = $alocacao->horaInicio;
                            $ret[] = $alocacao->horaFim;
                            $ret[] = $alocacao->confirmado;
                            $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                            $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                            $ret[] = "Não foi possivel criar a alocacao.";
                            $ret[] = "</div>";
                            $retorno = implode('<br />', $ret);
                            $_SESSION['Mensagem'] = $retorno;
                            header('location:../view/criar_alocacao.php');
                        }
                        //seta novo horário
                        $alocacao->horaInicio = '13:30';
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
                
                //Instancia o objeto alocacao
                $alocacao = new Alocacao($db);
                
                //seta as propriedados do alocacao
                $alocacao->nome = $_POST['nome'];
                $alocacao->sobrenome = $_POST['sobrenome'];
                $alocacao->email = $_POST['email'];
                $alocacao->idPerfil = $_POST['idPapel'];
                $alocacao->idAlocacao = $_GET['idAlocacao'];
               
                //cria variavel array para armazenar retorno
                $ret = array();
                
                $alocacaoDAO = new alocacaoDAO($db);
                
                //cria o usuário
                if($alocacaoDAO->update($alocacao)){
                    $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Alocacao foi atualizado.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                header('location:../view/view_alocacaos.php');
                }
                else{
                    $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Não foi possível atualizar o usuário.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                header('location:../view/view_alocacaos.php');
                }
            }
        break;//Fecha case salvar
    }
}

if(isset($_POST['operacao'])){
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

            //Seta o usuário que será deletado
            $alocacao->idAlocacao = $_POST['object_id'];
            
            //Instancia o objeto alocacaoDAO
            $alocacaoDAO = new alocacaoDAO($db);

            //deleta o alocacao
            if($alocacaoDAO->delete($alocacao)){
                $_SESSION['Mensagem'] = "O usuário foi deletado com sucesso!.";
            }else{
                $_SESSION['Mensagem'] = "Não foi possível deletar o usuário.";

            }
        break;//Fecha case salvar
    
    }
}
?>

