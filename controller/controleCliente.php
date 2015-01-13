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
    include_once '../model/clienteDAO.class.php';
    include_once '../config/database.class.php';
    include_once '../validate/Validate.class.php';
    include_once '../model/Cliente.class.php';
    
    switch($operacao){
        // Caso seja salvar dados
        case 'salvar':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                //cria variavel array para armazenar Retorno
                $error = array();
                
                $razaosocial = filter_input(INPUT_POST,'razaosocial', FILTER_SANITIZE_STRING);
                $nomefantasia = filter_input(INPUT_POST,'nomefantasia', FILTER_SANITIZE_STRING);
                $CNPJ = filter_input(INPUT_POST,'CNPJ', FILTER_SANITIZE_NUMBER_INT);
                $cep = filter_input(INPUT_POST,'cep', FILTER_SANITIZE_NUMBER_INT);
                $UF = filter_input(INPUT_POST,'UF', FILTER_SANITIZE_STRING);
                $cidade = filter_input(INPUT_POST,'cidade', FILTER_SANITIZE_STRING);
                $bairro = filter_input(INPUT_POST,'bairro', FILTER_SANITIZE_STRING);
                $logradouro = filter_input(INPUT_POST,'logradouro', FILTER_SANITIZE_STRING);
                $numero = filter_input(INPUT_POST,'numero', FILTER_SANITIZE_NUMBER_INT);
                $observacao = filter_input(INPUT_POST,'observacao', FILTER_SANITIZE_STRING);
                
                if(Validate::validarRazaoSocial($razaosocial) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarNomeFantasia($nomefantasia) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarLogradouro($logradouro) !== false){
                        $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarNumero($numero) !== false){
                        $error[] = $_SESSION['Mensagem'];
                }

                if(count($error) > 0){
                    $retorno = implode('', $error);
                    $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/criar_cliente.php');
                }else{
                    //Instancia o objeto cliente
                    $cliente = new Cliente($db);
                    
                    //Instancia o objeto clienteDAO
                    $clienteDAO = new clienteDAO($db);
                    
                    //seta as propriedados do cliente
                    $cliente->razaoSocial = $razaosocial;
                    $cliente->nomeFantasia = $nomefantasia;
                    $cliente->CNPJ = Validate::removeNaoNumeros($CNPJ);
                    $cliente->CEP = Validate::removeNaoNumeros($cep);
                    $cliente->UF = $UF;
                    $cliente->cidade = $cidade;
                    $cliente->bairro = $bairro;
                    $cliente->logradouro = $logradouro;
                    $cliente->numero = $numero;
                    $cliente->observacao = $observacao;

                    //cria o cliente
                    if($clienteDAO->create($cliente)){   
                        $error[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $error[] = "Cliente foi criado com sucesso.";
                        $error[] = "</div>";
                        $retorno = implode('', $error);
                        $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/criar_cliente.php');
                    }else{
                        $error[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $error[] = "Não foi possivel criar o cliente.";
                        $error[] = "</div>";
                        $retorno = implode('', $error);
                        $_SESSION['Mensagem'] = $retorno;
                        header('location:../view/criar_cliente.php');
                    }
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
                
                $razaosocial = filter_input(INPUT_POST,'razaosocial', FILTER_SANITIZE_STRING);
                $nomefantasia = filter_input(INPUT_POST,'nomefantasia', FILTER_SANITIZE_STRING);
                $CNPJ = filter_input(INPUT_POST,'CNPJ', FILTER_SANITIZE_NUMBER_INT);
                $cep = filter_input(INPUT_POST,'cep', FILTER_SANITIZE_NUMBER_INT);
                $UF = filter_input(INPUT_POST,'UF', FILTER_SANITIZE_STRING);
                $cidade = filter_input(INPUT_POST,'cidade', FILTER_SANITIZE_STRING);
                $bairro = filter_input(INPUT_POST,'bairro', FILTER_SANITIZE_STRING);
                $logradouro = filter_input(INPUT_POST,'logradouro', FILTER_SANITIZE_STRING);
                $numero = filter_input(INPUT_POST,'numero', FILTER_SANITIZE_NUMBER_INT);
                $idCliente = filter_input(INPUT_GET,'idCliente', FILTER_SANITIZE_NUMBER_INT);
                
                if(Validate::validarRazaoSocial($razaosocial) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarNomeFantasia($nomefantasia) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarCNPJ($CNPJ) !== false){
                        $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarCidade($cidade) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarBairro($bairro) !== false){
                    $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarLogradouro($logradouro) !== false){
                        $error[] = $_SESSION['Mensagem'];
                }
                if(Validate::validarNumero($numero) !== false){
                        $error[] = $_SESSION['Mensagem'];
                }
                
                if(count($error) > 0){
                    $retorno = implode('', $error);
                    $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/update_cliente.php?idCliente='.$idCliente);
                }else{                
                    //seta as propriedados do cliente
                    $cliente->razaoSocial = $razaosocial;
                    $cliente->nomeFantasia = $nomefantasia;
                    $cliente->CNPJ = Validate::removeNaoNumeros($CNPJ);
                    $cliente->CEP = Validate::removeNaoNumeros($cep);
                    $cliente->UF = $UF;
                    $cliente->cidade = $cidade;
                    $cliente->bairro = $bairro;
                    $cliente->logradouro = $logradouro;
                    $cliente->numero = $numero;
                    $cliente->idCliente = $idCliente;

                    //cria variavel array para armazenar errororno
                    $error = array();

                    $clienteDAO = new clienteDAO($db);

                    //atualiza o cliente
                    if($clienteDAO->update($cliente)){
                        $error[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $error[] = "Cliente foi atualizado.";
                        $error[] = "</div>";
                        $retorno = implode('', $error);
                        $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/view_cliente.php');
                    }
                    else{
                        $error[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $error[] = "Não foi possível atualizar o cliente.";
                        $error[] = "</div>";
                        $retorno = implode('', $error);
                        $_SESSION['Mensagem'] = $retorno;
                    header('location:../view/view_cliente.php');
                    }
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

