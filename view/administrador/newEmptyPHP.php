<?php

    //Navegaçao entre os meses
    if(empty($_GET['data'])){
        $day = date('d');
        $month =ltrim(date('m'),"0");
        $year = date('Y');
    }else{
        $data = explode('/',$_GET['data']);//nova data
        $day = $data[0];
        $month = $data[1];
        $year = $data[2];
    }
    if($month==1){//mês anterior se janeiro mudar valor
        $month_ant = 12;
        $year_ant = $year - 1;
    }else{
        $month_ant = $month - 1;
        $year_ant = $year;
    }
    if($month==12){//proximo mês se dezembro tem que mudar
        $month_prox = 1;
        $year_prox = $year + 1;
    }else{
        $month_prox = $month + 1;
        $year_prox = $year;
    }

        include_once dirname(__FILE__).'/../../model/agendaDAO.class.php';
        include_once dirname(__FILE__).'/../../model/usuarioDAO.class.php';
        include_once dirname(__FILE__).'/../../model/clienteDAO.class.php';
        include_once dirname(__FILE__).'/../../config/database.class.php';
        include_once dirname(__FILE__).'/../../model/alocacaoDAO.class.php';
        include_once dirname(__FILE__).'/../../model/TipoAlocacao.class.php';
        include_once dirname(__FILE__).'/../../model/tipoAlocacaoDAO.class.php';

        $database = new Database();
        $db = $database->getConnection();

        $agendaDAO = new agendaDAO($db);

        if((isset($_GET['semana']) && ($_GET['semana'] != 0))){
            $semana = $_GET['semana'];
        }else{
            $semana = 0;
        }

        $calendario = implode(" ", $agendaDAO->retornaCalendarioSemanal($month, $year, $month_ant, $year_ant, $semana));
        $diasSem = explode(" ", $calendario);

//Imprime HEAD do HTML    
$html='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">
<head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1;" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../css/impressao.css">
    <script src="../js/jquery-1.11.2.min.js"></script>
    <script src="../js/jquery-migrate-1.2.1.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <table class="table table-bordered table-responsive">
        <tr></tr>
        <tr>
            <td class="text-center destaque colaboradores" rowspan="2">
                Colaborador
            </td>';

//Preenche a primeira rowspan com os dias do mês e o nome do mês
$f = 0;
while($f <= 6){
    if(($semana == 0) && ($diasSem[$f] > 7)){
        $html.= '<td class="text-center destaque"><strong>'.$diasSem[$f].'-'.$agendaDAO->getNameMon($month_ant).'</strong></td>';
    }else if(($semana == 4) && ($diasSem[$f] < 7)){
        $html.= '<td class="text-center destaque"><strong>'.$diasSem[$f].'-'.$agendaDAO->getNameMon($month_prox).'</strong></td>';
    }else{
        $html.= '<td class="text-center destaque"><strong>'.$diasSem[$f].'-'.$agendaDAO->getNameMon($month).'</strong></td>';
    }
    $f++;
}
$html.= '</tr><tr>'; 

//Preenche a segunda rowspan com os dias por extenso
$f = 0;
while($f <= 6){
    if(($semana == 0) && ($diasSem[$f] > 7)){
        $dayExtensive = $agendaDAO->getDayExtensive($month_ant, $diasSem[$f], $year_ant);
    }else if(($semana == 4) && ($diasSem[$f] < 7)){
        $dayExtensive = $agendaDAO->getDayExtensive($month_prox, $diasSem[$f], $year_prox);
    }else{
        $dayExtensive = $agendaDAO->getDayExtensive($month, $diasSem[$f], $year);
    }
        $html.='<td class="text-center destaque"><strong>'.$dayExtensive.'</strong></td>';   
    $f++;
}
$html.= '</tr>';    
    //Instancia os objetos
        $usuarioDAO = new usuarioDAO($db);
        $stmtUsu = $usuarioDAO->searchCol();

//Preenche a primeira coluna com o nome dos colaboradores
//Variavel que defini se a linha vai ter background ou nao
$preenchimento = "";
$conta = 0;
//Preenche a primeira coluna com o nome dos colaboradores
while($usuario = $stmtUsu->fetch(PDO::FETCH_OBJ)){
    $html.= '<tr>';
    $html.= '<th rowspan="2" class="text-center '.$preenchimento.' colaboradores">';
    
    $html.= $usuario->nome;
    $html.= "</th>";
    
    $objAlocacaoDAO = new alocacaoDAO($db);
    $objClienteDAO = new clienteDAO($db);
    $objTipoAlocDAO = new tipoAlocacaoDAO($db);
    $objAlocacao = new Alocacao($db);
    $objCliente = new Cliente($db);
    $objTipoAlocacao = new TipoAlocacao($db);

//Preenche a primeira rowspan com as alocações da manhã
    $f = 0;
    while($f <= 6){
        if(($semana == 0) && ($diasSem[$f] > 7)){
            $dataAloc = date('Y-m-d', mktime(0,0,0,$month_ant,$diasSem[$f],$year_ant));
        }else if(($semana == 4) && ($diasSem[$f] < 7)){
            $dataAloc = date('Y-m-d', mktime(0,0,0,$month_prox,$diasSem[$f],$year_prox));
        }else{
            $dataAloc = date('Y-m-d', mktime(0,0,0,$month,$diasSem[$f],$year));
        }
        if($stmtClient = $objAlocacaoDAO->searchMorning($dataAloc, $usuario->idUsuario)){
            if($stmtClient->rowcount() == 1){
                while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                $objCliente->idCliente = $objAlocacao->idCliente;
                $objClienteDAO->readOne($objCliente);
                $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                    if($objAlocacao->confirmado == 'S'){
                        $statusAloc = 'confirmado'; 
                    }else if($objAlocacao->confirmado == 'N'){
                        $statusAloc = 'nconfirmado'; 
                    }
                    
                    if($objAlocacao->bloqueado == 'S'){
                        $bloqueio = 'bloqueado';
                    }else{
                        $bloqueio = '';
                    }
                    
                    $html.= '<td class="text-center '.$bloqueio.' '.
                            $objTipoAlocacao->desAloc.'>';
                    $html.= '<a class="'.$statusAloc.'" >';
                    if($objAlocacao->idCliente == '152'){
                        $html.= $objTipoAlocacao->desAloc;
                    }else{
                        $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                    }
                    $html.= '</a>';
                    $html.= '</td>';
                }
            }else{
                $html.= '<td class="text-center '.$preenchimento.'">';
                while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                    $objCliente->idCliente = $objAlocacao->idCliente;
                    $objClienteDAO->readOne($objCliente);
                    $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                        $html.= '<a class="'.$statusAloc.'" >';
                        if($objAlocacao->idCliente == '152'){
                            $html.= $objTipoAlocacao->desAloc;
                        }else{
                            $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                        }
                        $html.= '</a>';
                }
                $html.= '</td>';
            }
        }else{
            if(($semana == 0) && ($diasSem[$f] > 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_ant, $diasSem[$f], $year_ant));
            }else if(($semana == 4) && ($diasSem[$f] < 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_prox, $diasSem[$f], $year_prox));
            }else{
                $dayOfWeek = date('l', mktime(0,0,0,$month, $diasSem[$f], $year));
            }
            if(($dayOfWeek == 'Saturday') || ($dayOfWeek == 'Sunday')){
                $html.= '<td class="text-center destaque">&nbsp;</td>';
            }else{
                $html.= '<td class="text-center '.$preenchimento.'">';
                //chama o modal
                $html.= '&nbsp;</td>';
            }
        }
    $f++;
    }
$html.='</tr><tr>';
    //Preenche a segunda rowspan com as alocações da tarde
    $f = 0;
    while($f <= 6){
        if(($semana == 0) && ($diasSem[$f] > 7)){
            $dataAloc = date('Y-m-d', mktime(0,0,0,$month_ant,$diasSem[$f],$year_ant));
        }else if(($semana == 4) && ($diasSem[$f] < 7)){
            $dataAloc = date('Y-m-d', mktime(0,0,0,$month_prox,$diasSem[$f],$year_prox));
        }else{
            $dataAloc = date('Y-m-d', mktime(0,0,0,$month,$diasSem[$f],$year));
        }
        $stmtClient = $objAlocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario);
        if($stmtClient = $objAlocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario)){
            if($stmtClient->rowcount() == 1){
                while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                $objCliente->idCliente = $objAlocacao->idCliente;
                $objClienteDAO->readOne($objCliente);
                $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                    if($objAlocacao->confirmado == 'S'){
                        $statusAloc = 'confirmado'; 
                    }else if($objAlocacao->confirmado == 'N'){
                        $statusAloc = 'nconfirmado'; 
                    }
                    
                    if($objAlocacao->bloqueado == 'S'){
                        $bloqueio = 'bloqueado';
                    }else{
                        $bloqueio = '';
                    }
                    
                    $html.= '<td class="text-center '.$bloqueio.' '.
                            $objTipoAlocacao->desAloc.'>';
                    $html.= '<a class="'.$statusAloc.'" >';
                    if($objAlocacao->idCliente == '152'){
                        $html.= $objTipoAlocacao->desAloc;
                    }else{
                        $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                    }
                    $html.= '</a>';
                    $html.= '</td>';
                }
            }else{
                $html.= '<td class="text-center '.$preenchimento.'">';
                while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                    $objCliente->idCliente = $objAlocacao->idCliente;
                    $objClienteDAO->readOne($objCliente);
                    $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                        $html.= '<a class="'.$statusAloc.'" >';
                        if($objAlocacao->idCliente == '152'){
                            $html.= $objTipoAlocacao->desAloc;
                        }else{
                            $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                        }
                        $html.= '</a>';
                }
                $html.= '</td>';
            }
        }else{
           if(($semana == 0) && ($diasSem[$f] > 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_ant, $diasSem[$f], $year_ant));
            }else if(($semana == 4) && ($diasSem[$f] < 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_prox, $diasSem[$f], $year_prox));
            }else{
                $dayOfWeek = date('l', mktime(0,0,0,$month, $diasSem[$f], $year));
            }
            if(($dayOfWeek == 'Saturday') || ($dayOfWeek == 'Sunday')){
                $html.= '<td class="text-center destaque">&nbsp;</td>';
            }else{
                $html.= '<td class="text-center '.$preenchimento.'">';
                //chama o modal
                $html.= '&nbsp;</td>';
            }
        }
    $f++;
    }
    $html.='</tr>';

    //Efetua a quebra de página e inicializa a tabela na proxima pagina
    if($conta == 6){
        $html.= '</table><br><table style=\"page-break-after:always;\"></br></table><br>
            <table class="table table-bordered table-responsive">
            <tr></tr>
            <tr>
            <th class="text-center destaque colaboradores" rowspan="2">
                Colaborador
            </th>';
                //Preenche a primeira rowspan com os dias do mês e o nome do mês
            $f = 0;
                while($f <= 6){
                    if(($semana == 0) && ($diasSem[$f] > 7)){
                        $html.= '<td class="text-center destaque"><strong>'.$diasSem[$f].'-'.$agendaDAO->getNameMon($month_ant).'</strong></td>';
                    }else if(($semana == 4) && ($diasSem[$f] < 7)){
                        $html.= '<td class="text-center destaque"><strong>'.$diasSem[$f].'-'.$agendaDAO->getNameMon($month_prox).'</strong></td>';
                    }else{
                        $html.= '<td class="text-center destaque"><strong>'.$diasSem[$f].'-'.$agendaDAO->getNameMon($month).'</strong></td>';
                    }
                    $f++;
                }

            $html.= '</tr><tr>'; 

            //Preenche a segunda rowspan com os dias por extenso
            $f = 0;
                while($f <= 6){
                    if(($semana == 0) && ($diasSem[$f] > 7)){
                        $dayExtensive = $agendaDAO->getDayExtensive($month_ant, $diasSem[$f], $year_ant);
                    }else if(($semana == 4) && ($diasSem[$f] < 7)){
                        $dayExtensive = $agendaDAO->getDayExtensive($month_prox, $diasSem[$f], $year_prox);
                    }else{
                        $dayExtensive = $agendaDAO->getDayExtensive($month, $diasSem[$f], $year);
                    }
                    $html.='<td class="text-center destaque"><strong>'.$dayExtensive.'</strong></td>';   
                $f++;
                } 
            $html.= '</tr>'; 
            $preenchimento = "";
    }

    $conta++;

    if($preenchimento == "destaque"){
        $preenchimento = "";
    }else{
        $preenchimento = "destaque";
    }
}
$html.= '</table>
</body>
</html>';
$html = mb_convert_encoding($html, 'UTF-8');
require_once(dirname(__FILE__).'/../../dompdf/dompdf_config.inc.php');
$dompdf= new DOMPDF();
$dompdf->set_paper('A4', 'landscape');
//Aqui nos damos um LOAD (carregamos) todos os nossos dados e formatacoes para geracao do PDF
$dompdf->load_html($html);
//Aqui nos damos inicio ao processo de exportacao (renderizar)
$dompdf->render();
$dompdf->stream('agenda_'.time().'.pdf');
