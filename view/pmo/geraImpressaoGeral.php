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

    include_once '../../model/agendaDAO.class.php';
    include_once '../../model/usuarioDAO.class.php';
    include_once '../../model/clienteDAO.class.php';
    include_once '../../config/database.class.php';
    include_once '../../model/alocacaoDAO.class.php';
    include_once '../../model/Cliente.class.php';
    include_once '../../model/Usuario.class.php';
    include_once '../../model/TipoAlocacao.class.php';
    include_once '../../model/tipoAlocacaoDAO.class.php';

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
    <style>
    .left-margin{
        margin:0 .5em 0 0;
    }
 
    .right-button-margin{
        margin: 0 0 1em 0;
        overflow: hidden;
    }
    
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed,
figure, figcaption, footer, header, hgroup,
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
  margin: 0;
  padding: 0;
  border: 0;
  font-size: 100%;
  vertical-align: baseline;
  font-family: Calibri;
  font-size: 10px;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

.colaboradores{
    font-weight: bold;
}


.destaque{
    color: #ffffff;
    background-color: #9a9aff;
}

</style>
    
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
    <script src="../../js/jquery-1.11.2.min.js"></script>
    <script src="../../js/jquery-migrate-1.2.1.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
</head>
<body>';

//Insere a primeira linha Colaborador
$html.= '<table class="table table-bordered table-responsive">
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
        $alocacaoDAO = new alocacaoDAO($db);
        $alocacao = new Alocacao($db);
        $clienteDAO = new clienteDAO($db);
        $cliente = new Cliente($db);
        $tipoAlocacao = new TipoAlocacao($db);
        $tipAlocDAO = new tipoAlocacaoDAO($db);
        
//Preenche a primeira coluna com o nome dos colaboradores
//Variavel que defini se a linha vai ter background ou nao
$preenchimento = "1";
$conta = 0;
//Preenche a primeira coluna com o nome dos colaboradores
while($usuario = $stmtUsu->fetch(PDO::FETCH_OBJ)){
    $html.= '<tr>';
    if($preenchimento == "0"){
        $html.='<th rowspan="2" class="text-center destaque colaboradores">'.$usuario->nome.'</th>';
    }else{
        $html.='<th rowspan="2" class="text-center colaboradores">'.$usuario->nome.'</th>';
    }
  
//Preenche a primeira rowspan com as alocações da manhã
    $f = 0;
    while($f <= 6){
        if(($semana == 0) && ($diasSem[$f] > 7)){
            $dataAloc = $year_ant.'-'.$month_ant.'-'.$diasSem[$f];
        }else if(($semana == 4) && ($diasSem[$f] < 7)){
            $dataAloc = $year_prox.'-'.$month_prox.'-'.$diasSem[$f];
        }else{
            $dataAloc = $year.'-'.$month.'-'.$diasSem[$f];
        }
        if($stmtClient = $alocacaoDAO->searchMorning($dataAloc, $usuario->idUsuario)){
            while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                $cliente->idCliente = $alocacao->idCliente;
                $clienteDAO->readOne($cliente);
                $tipoAlocacao->desAloc = $tipAlocDAO->readName($alocacao->idTipAloc);
                if(($preenchimento == "0") && ($alocacao->idCliente == '152')){
                    $html.='<td class="text-center destaque">'.$tipoAlocacao->desAloc.'</td>';
                }else if(($preenchimento == "0") && ($alocacao->idCliente != '152')){
                    $html.='<td class="text-center destaque">'.$cliente->nomeFantasia.' - '.$tipoAlocacao->desAloc.'</td>';
                }else if(($preenchimento == "1") && ($alocacao->idCliente == '152')){
                    $html.='<td class="text-center">'.$tipoAlocacao->desAloc.'</td>';
                }else if(($preenchimento == "1") && ($alocacao->idCliente != '152')){
                    $html.='<td class="text-center">'.$cliente->nomeFantasia.' - '.$tipoAlocacao->desAloc.'</td>';
                }
            }
        }else{
            if(($semana == 0) && ($diasSem[$f] > 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_ant, $diasSem[$f], $year_ant));
            }else if(($semana == 4) && ($diasSem[$f] < 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_prox, $diasSem[$f], $year_prox));
            }else{
                $dayOfWeek = date('l', mktime(0,0,0,$month, $diasSem[$f], $year));
            }

            //Imprime sabado e domingo em destaque
            if(($dayOfWeek == 'Saturday') || ($dayOfWeek == 'Sunday')){
                $html.='<td class="text-center destaque">&nbsp;</td>';
            }else if($preenchimento == "0"){
                $html.='<td class="text-center destaque">&nbsp;</td>';
            }else{
                $html.='<td class="text-center">&nbsp;</td>';
            }
        }
        $f++;
    }
$html.='</tr><tr>';
    //Preenche a segunda rowspan com as alocações da tarde
    $x = 0;
    while($x <= 6){
        if(($semana == 0) && ($diasSem[$x] > 7)){
            $dataAloc = $year_ant.'-'.$month_ant.'-'.$diasSem[$x];
        }else if(($semana == 4) && ($diasSem[$x] < 7)){
            $dataAloc = $year_prox.'-'.$month_prox.'-'.$diasSem[$x];
        }else{
            $dataAloc = $year.'-'.$month.'-'.$diasSem[$x];
        }
        if($stmtClient = $alocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario)){
            while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
            $cliente->idCliente = $alocacao->idCliente;
            $clienteDAO->readOne($cliente);
            $tipoAlocacao->desAloc = $tipAlocDAO->readName($alocacao->idTipAloc);
                if(($preenchimento == "0") && ($alocacao->idCliente == '152')){
                    $html.='<td class="text-center destaque">'.$tipoAlocacao->desAloc.'</td>';
                }else if(($preenchimento == "0") && ($alocacao->idCliente != '152')){
                    $html.='<td class="text-center destaque">'.$cliente->nomeFantasia.' - '.$tipoAlocacao->desAloc.'</td>';
                }else if(($preenchimento == "1") && ($alocacao->idCliente == '152')){
                    $html.='<td class="text-center">'.$tipoAlocacao->desAloc.'</td>';
                }else if(($preenchimento == "1") && ($alocacao->idCliente != '152')){
                    $html.='<td class="text-center">'.$cliente->nomeFantasia.' - '.$tipoAlocacao->desAloc.'</td>';
                }
            }
        }else{
            if(($semana == 0) && ($diasSem[$x] > 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_ant, $diasSem[$x], $year_ant));
            }else if(($semana == 4) && ($diasSem[$x] < 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_prox, $diasSem[$x], $year_prox));
            }else{
                $dayOfWeek = date('l', mktime(0,0,0,$month, $diasSem[$x], $year));
            }
            //Imprime sabado e domingo em destaque
            if(($dayOfWeek == 'Saturday') || ($dayOfWeek == 'Sunday')){
                $html.='<td class="text-center destaque">&nbsp;</td>';
            }else if($preenchimento == "0"){
                $html.='<td class="text-center destaque">&nbsp;</td>';
            }else{
                $html.='<td class="text-center">&nbsp;</td>';
            }
        }
        $x++;
    } 
    $html.='</tr>';
    
    //Efetua a quebra de página e inicializa a tabela na proxima pagina
    if($conta == 6){
        $html.= '</table><br><table style=\"page-break-after:always;\"></br></table><br>
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
            $preenchimento = "0";
    }
    
    $conta++;
    
    if($preenchimento == "0"){
        $preenchimento = "1";
    }else{
        $preenchimento = "0";
    }
}
$html.= '</table>
</body>
</html>';

require_once('../../dompdf/dompdf_config.inc.php');
date_default_timezone_set('America/Sao_Paulo');


//Instanciamos a class do dompdf para o processo
$dompdf= new DOMPDF();

$dompdf->set_paper('A4', 'landscape');

//Aqui nos damos um LOAD (carregamos) todos os nossos dados e formatacoes para geracao do PDF
$dompdf->load_html($html);

//Aqui nos damos inicio ao processo de exportacao (renderizar)
$dompdf->render();

$dompdf->stream('agenda_'.date('d/m/Y').'.pdf');

?>