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
 date_default_timezone_set('America/Sao_Paulo');
  
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
                $dataIniAloc = filter_input(INPUT_POST,'dataIniAlocacao', FILTER_SANITIZE_STRING);
                $dataFimAloc = filter_input(INPUT_POST,'dataFimAlocacao', FILTER_SANITIZE_STRING);
                $tipAloc = filter_input(INPUT_POST,'alocacao', FILTER_SANITIZE_STRING);
                $periodo = filter_input(INPUT_POST,'periodo', FILTER_SANITIZE_STRING);
                $confirmado = filter_input(INPUT_POST,'confirmado', FILTER_SANITIZE_STRING);
                $semana = filter_input(INPUT_POST,'semana', FILTER_SANITIZE_STRING);
                
                $error = array();
                
                if(empty($dataFimAloc)){
                    $dataFimAloc = $dataIniAloc;
                }
                
                //Converte data no formato PT-BR para EN
                $format = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
                if($dataIniAloc != null && (preg_match($format, $dataIniAloc, $partes))) {
                    $dataAloc = $dataIniAloc;
                    $dataIniAloc = $partes[3].'/'.$partes[2].'/'.$partes[1];
                }
                
                if($dataFimAloc != null && (preg_match($format, $dataFimAloc, $partes))) {
                    $dataFimAloc = $partes[3].'/'.$partes[2].'/'.$partes[1];
                }
                if(strtotime($dataIniAloc) > strtotime($dataFimAloc)){
                    $error[] = "<div class=\"alert alert-danger alert-dismissable\">";
                    $error[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    $error[] = 'A data inicial n&atilde;o pode ser maior que a data final!';
                    $error[] = "</div>";
                }

                if(count($error) > 0){
                    $retorno = implode('', $error);
                    $_SESSION['Mensagem'] = $retorno;
                    
                    if($_SESSION['perfil'] == 'A'){
                        header('location:../view/administrador/criar_alocacao.php');
                    }else if($_SESSION['perfil'] == 'P'){
                        header('location:../view/pmo/criar_alocacao.php');
                    }
                }else{
                    //cria variavel array para armazenar retorno
                    $ret = array();
                      
                    /*
                     * Enquando a data inicial for menor que a data final e inserido a alocacao
                     */
                    while(strtotime($dataIniAloc)<= strtotime($dataFimAloc)){
                        //Instancia o objeto alocacao
                        $alocacao = new Alocacao($db);
                        $alocacaoDAO = new alocacaoDAO($db);
                        /*
                         * Verifica se a data inicial de alocacao esta no formato EN ou PT-BR
                         * Se estiver no formato PT-BR converte para EN
                         */
                        if($alocacaoDAO->date_converter($dataIniAloc) == FALSE){
                            $alocacao->dataAlocacao = $dataIniAloc;
                        }else{
                            $alocacao->dataAlocacao = $alocacaoDAO->date_converter($dataIniAloc);
                        }
                        
                        //seta as propriedados do alocacao
                        $alocacao->idColaborador = $idColaborador;
                        $alocacao->idCliente = $idCliente;
                        $alocacao->desAlocacao = $desAlocacao;
                        $alocacao->idTipAloc = $tipAloc;
                        $alocacao->periodo = $periodo;
                        $alocacao->confirmado = $confirmado;
                        $alocacao->idUsuario = $_SESSION['user_id'];
                        //Verifica qual o período da alocação e insere de acordo
                        if($_POST['bloqueio'] == 1){
                            $alocacao->bloqueado = 'S';
                            $i=0;
                            $alocacao->horaInicio = '08:00';
                            $alocacao->horaFim = '12:00';
                            for($i = 0; $i<=1; $i++){
                                //cria o usuario
                                $alocacaoDAO->create($alocacao);  
                                   //seta novo horario
                                    $alocacao->horaInicio = '14:00';
                                    $alocacao->horaFim = '18:00';
                            } 
                        }else if($alocacao->periodo == 'M'){
                            $alocacao->horaInicio = '08:00';
                            $alocacao->horaFim = '12:00';
                            //cria a alocacao
                            $alocacaoDAO->create($alocacao);
                        }else if($alocacao->periodo == 'V'){
                            $alocacao->horaInicio = '14:00';
                            $alocacao->horaFim = '18:00';
                            $alocacaoDAO->create($alocacao); 
                        }else if($alocacao->periodo == 'I'){
                            $i=0;
                            $alocacao->horaInicio = '08:00';
                            $alocacao->horaFim = '12:00';
                            for($i = 0; $i<=1; $i++){
                                //cria o usuario
                                $alocacaoDAO->create($alocacao);
                                //seta novo horario
                                $alocacao->horaInicio = '14:00';
                                $alocacao->horaFim = '18:00';
                            }
                        }else{
                            $alocacao->horaInicio = $_POST['horaIni'];
                            $alocacao->horaFim = $_POST['horaFim'];
                            $alocacaoDAO->create($alocacao);
                        }
                        $dataIniAloc = $alocacaoDAO->SomarData($dataIniAloc, 1, 0, 0);
                    }
                    /*
                    * Vefica se ha alocacao;
                    * Se ha redireciona para a pagina de alocacao semanal
                    * Se nao redireciona alocacao mensal
                    */
                   if($semana == ""){
                       if($_SESSION['perfil'] == 'A'){
                           header('location:../view/administrador/view_agendaGeral.php?data='.$dataAloc);
                       }else if($_SESSION['perfil'] == 'P'){
                           header('location:../view/pmo/view_agendaGeral.php?data='.$dataAloc);
                       }
                   }else{
                       if($_SESSION['perfil'] == 'A'){
                           header('location:../view/administrador/view_agendaSemanal.php?data='.$dataAloc.'&semana='.$semana);
                       }else if($_SESSION['perfil'] == 'P'){
                           header('location:../view/pmo/view_agendaSemanal.php?data='.$dataAloc.'&semana='.$semana);
                       }
                   }
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
                $semana = filter_input(INPUT_POST,'semana', FILTER_SANITIZE_STRING);
                
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

                $alocacaoDAO = new alocacaoDAO($db);
                if($alocacaoDAO->date_converter($dataAloc) == FALSE){
                    $alocacao->dataAlocacao = $dataAloc;
                    //Converte data no formato EN para PT-BR
                    $format = '/^([0-9]{4})\/([0-9]{2})\/([0-9]{2})$/';
                    if ($dataAloc != null && preg_match($format, $dataAloc, $partes)) {
                            $dataBr = $partes[3].'/'.$partes[2].'/'.$partes[1];
                    }
                }else{
                    $dataBr = $dataAloc;
                    $alocacao->dataAlocacao = $alocacaoDAO->date_converter($dataAloc);
                    $dataAloc = $alocacaoDAO->date_converter($dataAloc);
                }
                
                $chave->dataAlocacao = $_GET['data'];
                $chave->horaInicio = $_GET['horaIni'];
                $chave->horaFim = $_GET['horaFim'];
                $chave->idColaborador = $_GET['idCol'];
                
                if($alocacao->periodo == 'M'){
                    $alocacao->horaInicio = '08:00';
                    $alocacao->horaFim = '12:00';
                    //cria a alocacao
                    $alocacaoDAO->update($alocacao, $chave);
                }else if($alocacao->periodo == 'V'){
                    $alocacao->horaInicio = '14:00';
                    $alocacao->horaFim = '18:00';
                    $alocacaoDAO->update($alocacao, $chave);  
                }else if($_POST['bloqueio'] == 1){
                    $alocacao->horaInicio = $chave->horaInicio;
                    $alocacao->horaFim = $chave->horaFim;
                    $alocacao->bloqueado = 'S';
                    $alocacaoDAO->update($alocacao, $chave);
                }else{
                    $alocacao->horaInicio = $_POST['horaIni'];
                    $alocacao->horaFim = $_POST['horaFim'];
                    $alocacaoDAO->update($alocacao, $chave);
                }
                /*
                * Vefica se ha alocacao;
                * Se ha redireciona para a pagina de alocacao semanal
                * Se nao redireciona alocacao mensal
                */
               if($semana == ""){
                   if($_SESSION['perfil'] == 'A'){
                       header('location:../view/administrador/view_agendaGeral.php?data='.$dataBr);
                   }else if($_SESSION['perfil'] == 'P'){
                       header('location:../view/pmo/view_agendaGeral.php?data='.$dataBr);
                   }
               }else{
                   if($_SESSION['perfil'] == 'A'){
                       header('location:../view/administrador/view_agendaSemanal.php?data='.$dataBr.'&semana='.$semana);
                   }else if($_SESSION['perfil'] == 'P'){
                       header('location:../view/pmo/view_agendaSemanal.php?data='.$dataBr.'&semana='.$semana);
                   }
               }
            }
        break;
        case 'confirmar':
            $database = new Database();
            $db = $database->getConnection();
            /*
             * Verificar se foi marcado algum check box
             */
            if (isset($_POST['alocacao'])){
                /*
                 * Atribui o valor dos checkbox para a variavel $alocacao
                 * Após percorre o array de informacoes e separa as informacoes
                 * Com as informacoes separadas em partes é atribuido seus valores objeto alocacao
                 * Por fim é executado a função para confirmar a alocação
                 */
                $alocacao = $_POST['alocacao'];
                foreach ($alocacao as $a){
                    $partes = explode("|", $a);
                    $objAlocacao = new Alocacao($db);
                    $objAlocDAO = new alocacaoDAO($db);
                    $objAlocacao->dataAlocacao = $objAlocDAO->date_converter($partes[0]);
                    $objAlocacao->horaInicio = $partes[1];
                    $objAlocacao->horaFim = $partes[2];
                    $objAlocacao->idColaborador = $partes[3];
                    
                    
                    if($objAlocDAO->confirmarAlocacao($objAlocacao)){
                        $ret = array();
                        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "As Aloca&ccedil;&otilde;es foram confirmadas com sucesso.";
                        $ret[] = "</div>";  
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        if($_SESSION['perfil'] == 'A'){
                            header('location:../view/administrador/confirma_alocacao.php');
                        }else if($_SESSION['perfil'] == 'P'){
                            header('location:../view/pmo/confirma_alocacao.php');
                        }
                    }else{
                        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
                        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        $ret[] = "N&atilde;o foi poss&iacute;vel confirmas as aloca&ccedil;&otilde;es.";
                        $ret[] = "</div>";
                        $retorno = implode('', $ret);
                        $_SESSION['Mensagem'] = $retorno;
                        if($_SESSION['perfil'] == 'A'){
                            header('location:../view/administrador/confirma_alocacao.php');
                        }else if($_SESSION['perfil'] == 'P'){
                            header('location:../view/pmo/confirma_alocacao.php');
                        }
                    }
                }
            }
        break;
        //Envia e-mail
        case 'enviar':
            //Instancia uma nova conexaos
            $database = new Database();
            $db = $database->getConnection();
            $objAlocDAO = new alocacaoDAO($db);
            $data = filter_input(INPUT_GET, 'data', FILTER_SANITIZE_STRING);
            $semana = filter_input(INPUT_GET,'semana', FILTER_SANITIZE_STRING);
            $idColaborador = filter_input(INPUT_GET,'idColaborador', FILTER_SANITIZE_NUMBER_INT);
            $dia = date('d', mktime(0,0,0,0,filter_input(INPUT_GET,'dia', FILTER_SANITIZE_STRING),0));
            $mes = date('m', mktime(0,0,0,filter_input(INPUT_GET,'mes', FILTER_SANITIZE_NUMBER_INT)+1,0,0));
            if(!empty($idColaborador)){
                if($objAlocDAO->enviaEmailParticular($idColaborador, $dia, $mes)){
                    if($_SESSION['perfil'] == 'A'){
                        header('location:../view/administrador/view_agendaParticular.php');
                    }else if($_SESSION['perfil'] == 'P'){
                        header('location:../view/pmo/view_agendaParticular.php');
                    }
                }
            }else{
                if($objAlocDAO->enviaEmail($dia, $mes)){
                    if($_SESSION['perfil'] == 'A'){
                        header('location:../view/administrador/view_agendaSemanal.php?data='.$data.'&semana='.$semana);
                    }else if($_SESSION['perfil'] == 'P'){
                        header('location:../view/pmo/view_agendaSemanal.php?data='.$data.'&semana='.$semana);
                    }
                }
            }              
                    
        break;//Fim do enviar email
        //Caso for imprimir a alocação
        case 'imprimir':
            //Instancia uma nova conexaos
            $database = new Database();
            $db = $database->getConnection();
            $objAlocDAO = new alocacaoDAO($db);
            if(isset($_GET['idColaborador'])){
                $objAlocDAO->geraImpressaoPDFParticular();
            }else{
                $objAlocDAO->geraImpressaoPDF();
            }
        break;
    }
}

if(isset($_POST['operacao'])){
    include_once '../model/alocacaoDAO.class.php';
    include_once '../config/database.class.php';
    
    switch($_POST['operacao']){
        // Caso seja deletar dados
        case 'deletar':
            //Instancia uma nova conexao
            $database = new Database();
            $db = $database->getConnection();
            
            include_once '../model/Alocacao.class.php';
            //Instancia o objeto alocacao
            $alocacao = new Alocacao($db);
            
            //Instancia o objeto alocacaoDAO
            $alocacaoDAO = new alocacaoDAO($db);
            $dataAloc = $_POST['object_data'];
            if($alocacaoDAO->date_converter($dataAloc) == FALSE){
                $alocacao->dataAlocacao = $dataAloc;
            }else{
                $alocacao->dataAlocacao = $alocacaoDAO->date_converter($dataAloc);
            }
            //Seta os dados da alocacao que sera deletada
            $alocacao->idColaborador = $_POST['object_col'];
            $alocacao->horaInicio = $_POST['object_ini'];
            $alocacao->horaFim = $_POST['object_fim'];
            
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
        break;//Fecha case deletar
        
        // Caso seja liberar dados
        case 'liberar':
            //Instancia uma nova conexao
            $database = new Database();
            $db = $database->getConnection();
            
            include_once '../model/Alocacao.class.php';
            //Instancia o objeto alocacaoDAO
            $alocacaoDAO = new alocacaoDAO($db);
            $dataAloc = $_POST['object_data'];
            if($alocacaoDAO->date_converter($dataAloc) == FALSE){
                $dataAloc = $dataAloc;
            }else{
                $dataAloc = $alocacaoDAO->date_converter($dataAloc);
            }
            //Seta os dados da alocacao que sera deletada
            $data = $dataAloc;
            $idColaborador = $_POST['object_col'];
            $horaIni = $_POST['object_ini'];
            $horaFim= $_POST['object_fim'];
            
            //cria variavel array para armazenar retorno
            $ret = array();

            //Libera a alocacao
            $alocacaoDAO->liberarEdicao($data, $horaIni, $horaFim, $idColaborador);
        break;//Fecha case liberar
    }
}
?>