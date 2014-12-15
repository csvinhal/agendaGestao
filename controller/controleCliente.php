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

if(isset($_GET['operacao'])){
    include_once '../model/clienteDAO.class.php';
    include_once '../config/database.class.php';
    
    switch($_GET['operacao']){
        // Caso seja salvar dados
        case 'salvar':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                //Instancia o objeto cliente
                $cliente = new Cliente($db);
                
                //seta as propriedados do cliente
                $cliente->razaoSocial = $_POST['razaosocial'];
                $cliente->nomeFantasia = $_POST['nomefantasia'];
                $cliente->CNPJ = $_POST['CNPJ'];
                $cliente->CEP = $_POST['CEP'];
                $cliente->UF = $_POST['UF'];
                $cliente->cidade = $_POST['cidade'];
                $cliente->bairro = $_POST['bairro'];
                $cliente->rua = $_POST['rua'];
                $cliente->numero = $_POST['numero'];
               
                //cria variavel array para armazenar retorno
                $ret = array();
                
                $clienteDAO = new clienteDAO($db);
                
                //cria o cliente
                if($clienteDAO->create($cliente)){   
                    $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Cliente foi criado com sucesso.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                header('location:../view/criar_cliente.php');
                }else{
                    $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Não foi possivel criar o cliente.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/criar_cliente.php');
                }
            }
        break;//Fecha case salvar
        case 'update':  
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                //Instancia o objeto cliente
                $cliente = new Cliente($db);
                
                //seta as propriedados do cliente
                $cliente->razaoSocial = $_POST['razaosocial'];
                $cliente->nomeFantasia = $_POST['nomefantasia'];
                $cliente->CNPJ = $_POST['CNPJ'];
                $cliente->CEP = $_POST['CEP'];
                $cliente->UF = $_POST['UF'];
                $cliente->cidade = $_POST['cidade'];
                $cliente->bairro = $_POST['bairro'];
                $cliente->rua = $_POST['rua'];
                $cliente->numero = $_POST['numero'];
                $cliente->idCliente = $_GET['idCliente'];
               
                //cria variavel array para armazenar retorno
                $ret = array();
                
                $clienteDAO = new clienteDAO($db);
                
                //atualiza o cliente
                if($clienteDAO->update($cliente)){
                    $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Cliente foi atualizado.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                header('location:../view/view_cliente.php');
                }
                else{
                    $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $ret[] = "Não foi possível atualizar o cliente.";
                    $ret[] = "</div>";
                    $retorno = implode('', $ret);
                    $_SESSION['Mensagem'] = $retorno;
                header('location:../view/view_cliente.php');
                }
            }
        break;//Fecha case salvar
    }
}

if(isset($_POST['operacao'])){
    include_once '../model/clienteDAO.class.php';
    include_once '../config/database.class.php';
    
    switch($_POST['operacao']){
        // Caso seja salvar dados
        case 'deletar':
            //Instancia uma nova conexao
            $database = new Database();
            $db = $database->getConnection();

            //Instancia o objeto cliente
            $cliente = new Cliente($db);

            //Seta o cliente que será deletado
            $cliente->idCliente = $_POST['object_id'];
            
            //Instancia o objeto clienteDAO
            $clienteDAO = new clienteDAO($db);

            //deleta o cliente
            if($clienteDAO->delete($cliente)){
                $_SESSION['Mensagem'] = "O cliente foi deletado com sucesso!";
                header('location:../view/view_cliente.php');
            }else{
                $_SESSION['Mensagem'] = "Não foi possível deletar o cliente.";
                header('location:../view/view_cliente.php');
            }
        break;//Fecha case salvar
    
    }
}
?>

