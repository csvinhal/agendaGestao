<?php
/*
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
/**
* Description of alocacaoDAO
*
* @author Cristiano
*/
include_once 'Alocacao.class.php';
class alocacaoDAO {

// Cria um atributo chamado conexao para armazenar uma instância da conexão
private $conn;

/* Cria um método construtor para armazenar a instância da conexão na
* No atributo conexao
*/
public function __construct($db){

    // Armazena a instância da conexao no atributo conexao
    $this->conn = $db;

}

function create($alocacao){
    try{
        $stmt = $this->conn->prepare("INSERT INTO alocacao(dataAlocacao, horaInicio, horaFim,
                                    idColaborador, desAlocacao, idTipAloc, confirmado, idUsuario, idCliente, bloqueado)
                                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // Adiciona os dados do alocacao no lugar das interrogações da instrução SQL
        $stmt->bindValue(1,$alocacao->dataAlocacao);
        $stmt->bindValue(2,$alocacao->horaInicio);
        $stmt->bindValue(3,$alocacao->horaFim);
        $stmt->bindValue(4,$alocacao->idColaborador);
        $stmt->bindValue(5,$alocacao->desAlocacao);
        $stmt->bindValue(6,$alocacao->idTipAloc);
        $stmt->bindValue(7,$alocacao->confirmado);
        $stmt->bindValue(8,$alocacao->idUsuario);
        $stmt->bindValue(9,$alocacao->idCliente);
        $stmt->bindValue(10,$alocacao->bloqueado);
        // Executa a instrução SQL
        $stmt->execute();
        $ret = array();
        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "Aloca&ccedil;&atilde;o foi inserida com sucesso.";
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
    }catch(PDOException $e){
        $ret = array();
        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "Ocorreu o seguinte erro: ".$e->getMessage();
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
    }catch(Exception $e){
        $ret = array();
        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "Ocorreu o seguinte erro: ".$e->getCode()." ".$e->getMessage();
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
    }
}
//Atualiza alocacao
function update($alocacao, $chave){
    try{
    $stmt = $this->conn->prepare("UPDATE alocacao SET dataAlocacao = ?, horaInicio = ?,
                                horaFim = ?, idColaborador = ?, desAlocacao = ?, 
                                idTipAloc = ?, confirmado = ?,
                                idUsuario = ?, idCliente = ?, editando = 0, bloqueado = ?
                                WHERE dataAlocacao = ? AND horaInicio = ? AND
                                horaFim = ? AND idColaborador = ?");
    // Adiciona os dados do alocacao no lugar das interrogações da instrução SQL
    $stmt->bindValue(1,$alocacao->dataAlocacao);
    $stmt->bindValue(2,$alocacao->horaInicio);
    $stmt->bindValue(3,$alocacao->horaFim);
    $stmt->bindValue(4,$alocacao->idColaborador);
    $stmt->bindValue(5,$alocacao->desAlocacao);
    $stmt->bindValue(6,$alocacao->idTipAloc);
    $stmt->bindValue(7,$alocacao->confirmado);
    $stmt->bindValue(8,$alocacao->idUsuario);
    $stmt->bindValue(9,$alocacao->idCliente);
    $stmt->bindValue(10,$alocacao->bloqueado);
    $stmt->bindValue(11,$chave->dataAlocacao);
    $stmt->bindValue(12,$chave->horaInicio);
    $stmt->bindValue(13,$chave->horaFim);
    $stmt->bindValue(14,$chave->idColaborador);
    $stmt->execute();
    
    $ret = array();
    $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
    $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
    $ret[] = "Aloca&ccedil;&atilde;o foi alterada com sucesso.";
    $ret[] = "</div>";
    $retorno = implode('', $ret);
    $_SESSION['Mensagem'] = $retorno;
    }catch(PDOException $e){
        $ret = array();
        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "Ocorreu o seguinte erro: ".$e->getMessage();
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
    }catch(Exception $e){
        $ret = array();
        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "Ocorreu o seguinte erro: ".$e->getMessage();
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
    }
}

//Deleta o alocacao
function delete($alocacao){
    try{
        $stmt = $this->conn->prepare("DELETE FROM alocacao WHERE dataAlocacao = ? AND horaInicio = ? AND
                                        horaFim = ? AND idColaborador = ?");
        $stmt->bindValue(1,$alocacao->dataAlocacao);
        $stmt->bindValue(2,$alocacao->horaInicio);
        $stmt->bindValue(3,$alocacao->horaFim);
        $stmt->bindValue(4,$alocacao->idColaborador);
        $stmt->execute();

        $ret = array();
        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "A aloca&ccedil;&atilde;o foi deletada com sucesso!";
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
    }catch(PDOException $e){
        $ret = array();
        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "Ocorreu o seguinte erro: ".$e->getMessage();
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
    }catch(Exception $e){
        $ret = array();
        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "Ocorreu o seguinte erro: ".$e->getMessage();
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
    }
}

//Bloqueia a edicao da alocacao
function bloquearEdicao($data, $horaIni, $horaFim, $idColaborador){
    $stmt = $this->conn->prepare("UPDATE alocacao SET editando = 1
                                WHERE dataAlocacao = ? AND horaInicio = ? AND
                                horaFim = ? AND idColaborador = ?");
    // Adiciona os dados do alocacao no lugar das interrogações da instrução SQL
    $stmt->bindValue(1,$data);
    $stmt->bindValue(2,$horaIni);
    $stmt->bindValue(3,$horaFim);
    $stmt->bindValue(4,$idColaborador);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

//Libera alocacao para ser editada
function liberarEdicao($data, $horaIni, $horaFim, $idColaborador){
    $stmt = $this->conn->prepare("UPDATE alocacao SET editando = 0
                                WHERE dataAlocacao = ? AND horaInicio = ? AND
                                horaFim = ? AND idColaborador = ?");
    // Adiciona os dados do alocacao no lugar das interrogações da instrução SQL
    $stmt->bindValue(1,$data);
    $stmt->bindValue(2,$horaIni);
    $stmt->bindValue(3,$horaFim);
    $stmt->bindValue(4,$idColaborador);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

//Pesquisa se ha ou nao bloqueio por edicao na alocacao
function procurarBloqueio($data, $horaIni, $horaFim, $idColaborador){
    $stmt = $this->conn->prepare("SELECT editando FROM alocacao
                                WHERE dataAlocacao = ?
                                AND horaInicio = ?
                                AND horaFim = ?
                                AND idColaborador = ?
                                AND editando = 1");
    $stmt->bindValue(1,$data);
    $stmt->bindValue(2,$horaIni);
    $stmt->bindValue(3,$horaFim);
    $stmt->bindValue(4,$idColaborador);
    $stmt->execute();
    if($num = $stmt->rowCount() > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $row['editando'];
        }
    }else{
        return false;
    }
}

//Busca as alocacoes gerais com bloqueio na edicao da alocacao
function procurarBloqueadoGeral(){
    $stmt = $this->conn->prepare("SELECT dataAlocacao, horaInicio, horaFim, idColaborador FROM alocacao
                        WHERE editando = 1 ORDER BY dataAlocacao, horaInicio, horaFim, idColaborador");
    $stmt->execute();
    if($num = $stmt->rowCount() > 0){
        return $stmt;
    }else{
        return false;
    }
}

/*
* Procura as alocacoes nao confirmadas no banco de dados
*/
function procurarAlocacaoNConfirmado($idColaborador){
    $stmt = $this->conn->prepare("SELECT dataAlocacao, horaInicio, horaFim, idColaborador FROM alocacao
                                WHERE confirmado = 'N' AND idColaborador = ?
                                ORDER BY dataAlocacao, horaInicio, horaFim, idColaborador");
    $stmt->bindValue(1, $idColaborador);
    $stmt->execute();
    if($num = $stmt->rowCount() > 0){
        return $stmt;
    }else{
        return false;
    }
}

/*
* Procura as alocacoes nao confirmadas no banco de dados
*/
function procurarAlocacaoNConf(){
    $stmt = $this->conn->prepare("SELECT dataAlocacao, horaInicio, horaFim, idColaborador FROM alocacao
                                WHERE confirmado = 'N'
                                ORDER BY dataAlocacao, horaInicio, horaFim, idColaborador");
    $stmt->execute();
    if($num = $stmt->rowCount() > 0){
        return $stmt;
    }else{
        return false;
    }
}

/*
* Procura as alocacoes nao confirmadas no banco de dados por consultor
*/
function procurarAlocacaoNConfirmadoDataIni($idColaborador, $dataInicial){
    $stmt = $this->conn->prepare("SELECT dataAlocacao, horaInicio, horaFim, idColaborador FROM alocacao
                            WHERE confirmado = 'N' AND idColaborador = ? AND dataAlocacao >= ?
                            ORDER BY dataAlocacao, horaInicio, horaFim, idColaborador");
    $stmt->bindValue(1, $idColaborador);
    $stmt->bindValue(2, $dataInicial);
    $stmt->execute();
    if($num = $stmt->rowCount() > 0){
        return $stmt;
    }else{
        return false;
    }
}

/*
* Procura as alocacoes nao confirmadas no banco de dados
*/
function procurarAlocacaoNConfDataIni($dataInicial){
    $stmt = $this->conn->prepare("SELECT dataAlocacao, horaInicio, horaFim, idColaborador FROM alocacao
                            WHERE confirmado = 'N' AND dataAlocacao >= ?
                            ORDER BY dataAlocacao, horaInicio, horaFim, idColaborador");
    $stmt->bindValue(1, $dataInicial);
    $stmt->execute();
    if($num = $stmt->rowCount() > 0){
        return $stmt;
    }else{
        return false;
    }
}

/*
* Procura as alocacoes nao confirmadas no banco de dadospor consultor
*/
function procurarAlocacaoNConfirmadoBetween($idColaborador, $dataInicial, $dataFinal){
    $stmt = $this->conn->prepare("SELECT dataAlocacao, horaInicio, horaFim, idColaborador FROM alocacao
                            WHERE confirmado = 'N' AND idColaborador = ?
                            AND dataAlocacao >= ? AND dataAlocacao <= ?
                            ORDER BY dataAlocacao, horaInicio, horaFim, idColaborador");
    $stmt->bindValue(1, $idColaborador);
    $stmt->bindValue(2, $dataInicial);
    $stmt->bindValue(3, $dataFinal);
    $stmt->execute();
    if($num = $stmt->rowCount() > 0){
        return $stmt;
    }else{
        return false;
    }
}

/*
* Procura as alocacoes nao confirmadas no banco de dados
*/
function procurarAlocacaoNConfBetween($dataInicial, $dataFinal){
    $stmt = $this->conn->prepare("SELECT dataAlocacao, horaInicio, horaFim, idColaborador FROM alocacao
                            WHERE confirmado = 'N'
                            AND dataAlocacao >= ? AND dataAlocacao <= ?
                            ORDER BY dataAlocacao, horaInicio, horaFim, idColaborador");
    $stmt->bindValue(1, $dataInicial);
    $stmt->bindValue(2, $dataFinal);
    $stmt->execute();
    if($num = $stmt->rowCount() > 0){
        return $stmt;
    }else{
        return false;
    }
}

/*
* Seta a alocacao como confirmada
*/
function confirmarAlocacao($objAlocacao){
    $stmt = $this->conn->prepare("UPDATE alocacao SET confirmado = 'S'
                                WHERE dataAlocacao = ? AND horaInicio = ? AND
                                horaFim = ? AND idColaborador = ?");
    $stmt->bindValue(1, $objAlocacao->dataAlocacao);
    $stmt->bindValue(2, $objAlocacao->horaInicio);
    $stmt->bindValue(3, $objAlocacao->horaFim);
    $stmt->bindValue(4, $objAlocacao->idColaborador);
    if($num = $stmt->rowCount($stmt->execute()) > 0){
        return TRUE;
    }else{
        return FALSE;
    }
}

//Soma data
function SomarData($data, $dias, $meses, $ano)
{
    //Converte data no formato EN para PT-BR
    $format = '/^([0-9]{4})\/([0-9]{2})\/([0-9]{2})$/';
    
    if ($data != null && preg_match($format, $data, $partes)) {
        $data = $partes[3].'/'.$partes[2].'/'.$partes[1];
    }
    $data = explode("/", $data);
    $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,
    $data[0] + $dias, $data[2] + $ano) );
    $format = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
    if ($newData != null && preg_match($format, $newData, $partes)) {
        $newData = $partes[3].'/'.$partes[2].'/'.$partes[1];
    }
    return $newData;
}

//Converte data no formato PT-BR para EN
function date_converter($_date = null) {
    $format = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
    if ($_date != null && preg_match($format, $_date, $partes)) {
        return $partes[3].'/'.$partes[2].'/'.$partes[1];
    }
    return false;
}

//Converte data no formato EN para PT-BR
function date_converterBR($_date = null) {
    $format = '/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/';
    if ($_date != null && preg_match($format, $_date, $partes)) {
        return $partes[3].'/'.$partes[2].'/'.$partes[1];
    }
    return false;
}

//busca todos os alocacaos
function search(){
    $stmt = $this->conn->prepare("SELECT * FROM alocacao ORDER BY dataAlocacao");
    $stmt->execute();
    return $stmt;
}

function buscaAlocacaoConsultor($idColaborador, $dataIniAloc, $dataFimAloc){
    $stmt = $this->conn->prepare("SELECT * FROM alocacao WHERE idColaborador = ?
                                    AND dataAlocacao >= ? AND dataAlocacao <= ?
                                    ORDER BY dataAlocacao");
    $stmt->bindValue(1, $idColaborador);
    $stmt->bindValue(2, $dataIniAloc);
    $stmt->bindValue(3, $dataFimAloc);
    $stmt->execute();
    return $stmt;
}

function buscaAlocacaoCliente($idCliente, $idColaborador, $dataIniAloc, $dataFimAloc){
    $stmt = $this->conn->prepare("SELECT idCliente, idColaborador, dataAlocacao, horaInicio, horaFim
                                    FROM alocacao WHERE idCliente = ? AND idColaborador = ?
                                    AND dataAlocacao >= ? AND dataAlocacao <= ?
                                    ORDER BY idCliente, dataAlocacao");
    $stmt->bindValue(1, $idCliente);
    $stmt->bindValue(2, $idColaborador);
    $stmt->bindValue(3, $dataIniAloc);
    $stmt->bindValue(4, $dataFimAloc);
    $stmt->execute();
    return $stmt;
}

function buscaAlocacaoClientesWhereCol($idColaborador, $dataIniAloc, $dataFimAloc){
    $stmt = $this->conn->prepare("SELECT idCliente, idColaborador, dataAlocacao, horaInicio, horaFim
                                    FROM alocacao WHERE idColaborador = ?
                                    AND dataAlocacao >= ? AND dataAlocacao <= ?
                                    ORDER BY idCliente, dataAlocacao");
    $stmt->bindValue(1, $idColaborador);
    $stmt->bindValue(2, $dataIniAloc);
    $stmt->bindValue(3, $dataFimAloc);
    $stmt->execute();
    return $stmt;
}

function buscaAlocacaoClienteWhereCli($idCliente, $dataIniAloc, $dataFimAloc){
    $stmt = $this->conn->prepare("SELECT idCliente, idColaborador, dataAlocacao, horaInicio, horaFim
                                    FROM alocacao WHERE idCliente = ?
                                    AND dataAlocacao >= ? AND dataAlocacao <= ?
                                    ORDER BY idCliente, dataAlocacao");
    $stmt->bindValue(1, $idCliente);
    $stmt->bindValue(2, $dataIniAloc);
    $stmt->bindValue(3, $dataFimAloc);
    $stmt->execute();
    return $stmt;
}

function buscaAlocacaoClientes($dataIniAloc, $dataFimAloc){
    $stmt = $this->conn->prepare("SELECT idCliente, idColaborador, dataAlocacao, horaInicio, horaFim 
                                    FROM alocacao WHERE dataAlocacao >= ? 
                                    AND dataAlocacao <= ?
                                    ORDER BY idCliente, dataAlocacao");
    $stmt->bindValue(1, $dataIniAloc);
    $stmt->bindValue(2, $dataFimAloc);
    $stmt->execute();
    return $stmt;
}

function buscaAlocacao($objAlocacao){
    $stmt = $this->conn->prepare("SELECT * FROM alocacao
                                 WHERE dataAlocacao = ? AND horaInicio = ? AND 
                                 horaFim = ? AND idColaborador = ?
                                 ORDER BY dataAlocacao");
    $stmt->bindValue(1, $objAlocacao->dataAlocacao);
    $stmt->bindValue(2, $objAlocacao->horaInicio);
    $stmt->bindValue(3, $objAlocacao->horaFim);
    $stmt->bindValue(4, $objAlocacao->idColaborador);
    
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $objAlocacao->desAlocacao = $row['desAlocacao'];
    $objAlocacao->idTipAloc = $row['idTipAloc'];
    $objAlocacao->confirmado = $row['confirmado'];
    $objAlocacao->idUsuario = $row['idUsuario'];
    $objAlocacao->idCliente = $row['idCliente'];
    $objAlocacao->editando = $row['editando'];
    $objAlocacao->bloqueado = $row['bloqueado'];
}

//Pesquisa as alocacao no periodo da manha
function searchMorning($dataAlocacao, $param){
    $stmt = $this->conn->prepare("SELECT * FROM alocacao
                                WHERE idColaborador = ?
                                AND dataAlocacao = ?
                                AND horaFim <= '12:00'
                                ORDER BY dataAlocacao, horaInicio");
    $stmt->bindValue(1, $param);
    $stmt->bindValue(2, $dataAlocacao);
    $stmt->execute();
    
    if($stmt->rowCount() > 0){
        return $stmt;
    }else{
        return false;
    }
}

//Pesquisa todas as alocacoes do periodo da tarde
function searchAfternoon($dataAlocacao, $param){
    $stmt = $this->conn->prepare("SELECT * FROM alocacao
                                    WHERE idColaborador = ?
                                    AND dataAlocacao = ?
                                    AND horaInicio >= '13:00'
                                    AND horaFim <= '18:00'
                                    ORDER BY dataAlocacao, horaInicio");
    $stmt->bindValue(1, $param);
    $stmt->bindValue(2, $dataAlocacao);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        return $stmt;
        /*$row = $stmt->fetch(PDO::FETCH_ASSOC);
        $alocacao->idCliente = $row['idCliente'];*/
    }else{
        return false;
    }
}
//Numero de alocacaos cadastrados
public function countAll(){
    $stmt = $this->conn->prepare("SELECT * FROM alocacao");
    $stmt->execute();
    $num = $stmt->rowCount();
    
    return $num;
}

// Gera a variável de impressão de somente um consultar
public function geraHtmlImpressaoParticular(){
    //Navegaçao entre os meses
    if(!empty($_GET['data'])){
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

        include_once dirname(__FILE__).'/../model/agendaDAO.class.php';
        include_once dirname(__FILE__).'/../model/usuarioDAO.class.php';
        include_once dirname(__FILE__).'/../model/clienteDAO.class.php';
        include_once dirname(__FILE__).'/../config/database.class.php';
        include_once dirname(__FILE__).'/../model/alocacaoDAO.class.php';
        include_once dirname(__FILE__).'/../model/Cliente.class.php';
        include_once dirname(__FILE__).'/../model/Usuario.class.php';
        include_once dirname(__FILE__).'/../model/TipoAlocacao.class.php';
        include_once dirname(__FILE__).'/../model/tipoAlocacaoDAO.class.php';

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
    <style>
        .left-margin{margin:0 .5em 0 0;}
        .right-button-margin{margin: 0 0 1em 0;overflow: hidden;}
        
        html, body, div, span, applet, object, iframe,h1, h2, h3, h4, h5, h6, p, blockquote, pre,a, abbr, acronym, address, big, cite, code,del, dfn, em, ins, kbd, q, s, samp,small, strike, strong, sub, sup, tt, var,b, u, i, center,dl, dt, dd, ol, ul, li,fieldset, form, label, legend,table, caption, tbody, tfoot, thead, tr, th, td,article, aside, canvas, details, embed,figure, figcaption, footer, header, hgroup,menu, nav, output, ruby, section, summary,time, mark, audio, video {margin: 0;padding: 0;border: 0;font-size: 100%;vertical-align: baseline;font-family: Calibri;font-size: 11px;}
        table {border-collapse: collapse;border-spacing: 0;font-family: Calibri;font-size: 11px;}
        .colaboradores{font-weight: bold;}
        .destaque{color: #ffffff;background-color: #9a9aff;}
        .bloqueado{color: #ffffff;background-color: #006699;}
        .preenchimento{color: #000;background-color: #a9d4ff;}
        td.ferias{background-color: #ffffcc;}
        td.feriado{background-color: #cccccc;}
        td.folga{background-color: #ccffcc;}
    </style>
    
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
        $usuario = new Usuario($db);
        $usuario->idUsuario = $_GET['idColaborador'];
        $usuarioDAO->readOne($usuario);
        $objAlocacaoDAO = new alocacaoDAO($db);
        $objClienteDAO = new clienteDAO($db);
        $objTipoAlocDAO = new tipoAlocacaoDAO($db);
        $objAlocacao = new Alocacao($db);
        $objCliente = new Cliente($db);
        $objTipoAlocacao = new TipoAlocacao($db);
        
        $preenchimento = "";
            
    //Preenche a primeira coluna com o nome dos colaboradores
    $html.= '<tr><th rowspan="2" class="text-center colaboradores">'.$usuario->nome.'</th>';

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
                            $statusAloc = ''; 
                        }else if($objAlocacao->confirmado == 'N'){
                            $statusAloc = 'style="color:red"'; 
                        }

                        if($objAlocacao->idTipAloc == '3')
                        {
                            $html.= '<td class="text-center folga" '.$statusAloc.'><p>';
                        }else if($objAlocacao->idTipAloc == '4'){
                            $html.= '<td class="text-center feriado" '.$statusAloc.'><p>';
                        }else if ($objAlocacao->idTipAloc == '5'){
                            $html.= '<td class="text-center ferias" '.$statusAloc.'><p>';
                        }else if($objAlocacao->bloqueado == 'S'){
                            $html.= '<td class="text-center bloqueado"><p>';
                        }else{
                            $html.= '<td class="text-center '.$preenchimento.'"><p '.$statusAloc.'>';
                        }

                        if($objAlocacao->idCliente == '152'){
                            $html.= $objTipoAlocacao->desAloc;
                        }else if($objAlocacao->bloqueado == 'S'){
                            $html.="";
                        }else{
                            $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                        }
                        $html.= '</p></td>';
                    }
                }else{
                    $html.= '<td class="text-center '.$preenchimento.'">';
                    while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        $objCliente->idCliente = $objAlocacao->idCliente;
                        $objClienteDAO->readOne($objCliente);
                        $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                        if($objAlocacao->confirmado == 'S'){
                            $statusAloc = ''; 
                        }else if($objAlocacao->confirmado == 'N'){
                            $statusAloc = 'style="color:red"'; 
                        }
                            $html.= '<div><p '.$statusAloc.'>';
                            if($objAlocacao->idCliente == '152'){
                                $html.= $objTipoAlocacao->desAloc;
                            }else{
                                $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                            }
                            $html.= '</p></div>';
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
            if($stmtClient = $objAlocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario)){
                if($stmtClient->rowcount() == 1){
                    while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                    $objCliente->idCliente = $objAlocacao->idCliente;
                    $objClienteDAO->readOne($objCliente);
                    $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                        if($objAlocacao->confirmado == 'S'){
                            $statusAloc = ''; 
                        }else if($objAlocacao->confirmado == 'N'){
                            $statusAloc = 'style="color:red"'; 
                        }

                        if($objAlocacao->idTipAloc == '3')
                        {
                            $html.= '<td class="text-center folga" '.$statusAloc.'><p>';
                        }else if($objAlocacao->idTipAloc == '4'){
                            $html.= '<td class="text-center feriado" '.$statusAloc.'><p>';
                        }else if ($objAlocacao->idTipAloc == '5'){
                            $html.= '<td class="text-center ferias" '.$statusAloc.'><p>';
                        }else if($objAlocacao->bloqueado == 'S'){
                            $html.= '<td class="text-center bloqueado"><p>';
                        }else{
                            $html.= '<td class="text-center '.$preenchimento.'"><p '.$statusAloc.'>';
                        }

                        if($objAlocacao->idCliente == '152'){
                            $html.= $objTipoAlocacao->desAloc;
                        }else if($objAlocacao->bloqueado == 'S'){
                            $html.="";
                        }else{
                            $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                        }
                        $html.= '</p></td>';
                    }
                }else{
                    $html.= '<td class="text-center '.$preenchimento.'">';
                    while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        $objCliente->idCliente = $objAlocacao->idCliente;
                        $objClienteDAO->readOne($objCliente);
                        $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                        if($objAlocacao->confirmado == 'S'){
                            $statusAloc = ''; 
                        }else if($objAlocacao->confirmado == 'N'){
                            $statusAloc = 'style="color:red"'; 
                        }
                            $html.= '<div><p '.$statusAloc.'>';
                            if($objAlocacao->idCliente == '152'){
                                $html.= $objTipoAlocacao->desAloc;
                            }else{
                                $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                            }
                            $html.= '</p></div>';
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
    $html.= '</table>
    </body>
    </html>';
    return $html;    
}

// Gera a variável de impressão
public function geraHtmlParaImpressao(){
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

        include_once dirname(__FILE__).'/../model/agendaDAO.class.php';
        include_once dirname(__FILE__).'/../model/usuarioDAO.class.php';
        include_once dirname(__FILE__).'/../model/clienteDAO.class.php';
        include_once dirname(__FILE__).'/../config/database.class.php';
        include_once dirname(__FILE__).'/../model/alocacaoDAO.class.php';
        include_once dirname(__FILE__).'/../model/TipoAlocacao.class.php';
        include_once dirname(__FILE__).'/../model/tipoAlocacaoDAO.class.php';

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
    <style>
        .left-margin{margin:0 .5em 0 0;}
        .right-button-margin{margin: 0 0 1em 0;overflow: hidden;}
        
        html, body, div, span, applet, object, iframe,h1, h2, h3, h4, h5, h6, p, blockquote, pre,a, abbr, acronym, address, big, cite, code,del, dfn, em, ins, kbd, q, s, samp,small, strike, strong, sub, sup, tt, var,b, u, i, center,dl, dt, dd, ol, ul, li,fieldset, form, label, legend,table, caption, tbody, tfoot, thead, tr, th, td,article, aside, canvas, details, embed,figure, figcaption, footer, header, hgroup,menu, nav, output, ruby, section, summary,time, mark, audio, video {margin: 0;padding: 0;border: 0;font-size: 100%;vertical-align: baseline;font-family: Calibri;font-size: 11px;}
        table {border-collapse: collapse;border-spacing: 0;font-family: Calibri;font-size: 11px;}
        .colaboradores{font-weight: bold;}
        .destaque{color: #ffffff;background-color: #9a9aff;}
        .bloqueado{color: #ffffff;background-color: #006699;}
        .preenchimento{color: #000;background-color: #a9d4ff;}
        td.ferias{background-color: #ffffcc;}
        td.feriado{background-color: #cccccc;}
        td.folga{background-color: #ccffcc;}
    </style>
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
                        $statusAloc = ''; 
                    }else if($objAlocacao->confirmado == 'N'){
                        $statusAloc = 'style="color:red"'; 
                    }
                    
                    if($objAlocacao->idTipAloc == '3')
                    {
                        $html.= '<td class="text-center folga" '.$statusAloc.'><p>';
                    }else if($objAlocacao->idTipAloc == '4'){
                        $html.= '<td class="text-center feriado" '.$statusAloc.'><p>';
                    }else if ($objAlocacao->idTipAloc == '5'){
                        $html.= '<td class="text-center ferias" '.$statusAloc.'><p>';
                    }else if($objAlocacao->bloqueado == 'S'){
                        $html.= '<td class="text-center bloqueado"><p>';
                    }else{
                        $html.= '<td class="text-center '.$preenchimento.'"><p '.$statusAloc.'>';
                    }

                    if($objAlocacao->idCliente == '152'){
                        $html.= $objTipoAlocacao->desAloc;
                    }else if($objAlocacao->bloqueado == 'S'){
                        $html.="";
                    }else{
                        $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                    }
                    $html.= '</p></td>';
                }
            }else{
                $html.= '<td class="text-center '.$preenchimento.'">';
                while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                    $objCliente->idCliente = $objAlocacao->idCliente;
                    $objClienteDAO->readOne($objCliente);
                    $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                    if($objAlocacao->confirmado == 'S'){
                        $statusAloc = ''; 
                    }else if($objAlocacao->confirmado == 'N'){
                        $statusAloc = 'style="color:red"'; 
                    }
                        $html.= '<div><p '.$statusAloc.'>';
                        if($objAlocacao->idCliente == '152'){
                            $html.= $objTipoAlocacao->desAloc;
                        }else{
                            $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                        }
                        $html.= '</p></div>';
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
                        $statusAloc = ''; 
                    }else if($objAlocacao->confirmado == 'N'){
                        $statusAloc = 'style="color:red"'; 
                    }
                    
                    if($objAlocacao->idTipAloc == '3')
                    {
                        $html.= '<td class="text-center folga" '.$statusAloc.'><p>';
                    }else if($objAlocacao->idTipAloc == '4'){
                        $html.= '<td class="text-center feriado" '.$statusAloc.'><p>';
                    }else if ($objAlocacao->idTipAloc == '5'){
                        $html.= '<td class="text-center ferias" '.$statusAloc.'><p>';
                    }else if($objAlocacao->bloqueado == 'S'){
                        $html.= '<td class="text-center bloqueado"><p>';
                    }else{
                        $html.= '<td class="text-center '.$preenchimento.'"><p '.$statusAloc.'>';
                    }
                    
                    if($objAlocacao->idCliente == '152'){
                        $html.= $objTipoAlocacao->desAloc;
                    }else if($objAlocacao->bloqueado == 'S'){
                        $html.="";
                    }else{
                        $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                    }
                    $html.= '</p></td>';
                }
            }else{
                $html.= '<td class="text-center '.$preenchimento.'">';
                while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                    $objCliente->idCliente = $objAlocacao->idCliente;
                    $objClienteDAO->readOne($objCliente);
                    $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                    if($objAlocacao->confirmado == 'S'){
                        $statusAloc = ''; 
                    }else if($objAlocacao->confirmado == 'N'){
                        $statusAloc = 'style="color:red"'; 
                    }
                        $html.= '<div><p '.$statusAloc.'>';
                        if($objAlocacao->idCliente == '152'){
                            $html.= $objTipoAlocacao->desAloc;
                        }else{
                            $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                        }
                        $html.= '</p></div>';
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
            $preenchimento = "destaque";
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
    return $html;
}

//Gera arquivo para envio no e-mail geral
public function geraArquivoPDF(){
    $html = $this->geraHtmlParaImpressao();
	//$html = mb_convert_encoding($html, 'UTF-8');
    require_once(dirname(__FILE__).'/../dompdf/dompdf_config.inc.php');
    
    //Instanciamos a class do dompdf para o processo
    $dompdf= new DOMPDF();

    $dompdf->set_paper('A4', 'landscape');

    //Aqui nos damos um LOAD (carregamos) todos os nossos dados e formatacoes para geracao do PDF
    $dompdf->load_html($html);

    //Aqui nos damos inicio ao processo de exportacao (renderizar)
    $dompdf->render();

    $pdf = $dompdf->output();
    $dir = dirname(__FILE__)."/../PHPMailer/upload/agenda_".time().".pdf";
    if(file_put_contents($dir, $pdf)){
        return $dir;
    }
}

//Gera a impressão do PDF do arquivo de um consultor
public function geraImpressaoPDFParticular(){
    $html = $this->geraHtmlImpressaoParticular();
    require_once(dirname(__FILE__).'/../dompdf/dompdf_config.inc.php');
    try{
        //Instanciamos a class do dompdf para o processo
        $dompdf= new DOMPDF();
        $dompdf->set_paper('A4', 'landscape');
        //Aqui nos damos um LOAD (carregamos) todos os nossos dados e formatacoes para geracao do PDF
        $dompdf->load_html($html);
        //Aqui nos damos inicio ao processo de exportacao (renderizar)
        $dompdf->render();
        $dompdf->stream('agenda_'.time().'.pdf');
    }catch(Exception $e){
        $ret = array();
        $ret[] = "<div class=\"alert alert-danger alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "Ocorreu o seguinte erro: ".$e->getCode()." ".$e->getMessage();
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
    }
}

//Gera arquivo para envio no e-mail de um consultor
public function geraArquivoPDFParticular(){
    $html = $this->geraHtmlImpressaoParticular();
    require_once(dirname(__FILE__).'/../dompdf/dompdf_config.inc.php');
    
    //Instanciamos a class do dompdf para o processo
    $dompdf= new DOMPDF();

    $dompdf->set_paper('A4', 'landscape');

    //Aqui nos damos um LOAD (carregamos) todos os nossos dados e formatacoes para geracao do PDF
    $dompdf->load_html($html);

    //Aqui nos damos inicio ao processo de exportacao (renderizar)
    $dompdf->render();

    $pdf = $dompdf->output();
    $dir = dirname(__FILE__)."/../PHPMailer/upload/agenda_".time().".pdf";
    if(file_put_contents($dir, $pdf)){
        return $dir;
    }
}

//Função para enviar e-mail padrão com a alocação semanal de um consultor
function enviaEmailParticular($idColaborador, $dia, $mes){
    //O diretório do arquivo a ser enviado
    $dir = $this->geraArquivoPDFParticular();
    
    //Informa os dados do e-mail e do servidor smtp para envio
    $usuario = 'no-reply@gestao.com.br';
    $senha = 'noreply!@2014';
    //$Host = '132.245.78.146';
    $Host = 'smtp.office365.com';
	$Port = "587";
    
    //Monta as informações do e-mail
    $img = "<img src='http://agenda.gestao.com.br/PHPMailer/img/indice.png'>";
    
    //Monta os destinatarios
    require_once dirname(__FILE__).'/../config/database.class.php';
    require_once dirname(__FILE__).'/usuarioDAO.class.php';
    require_once dirname(__FILE__).'/Usuario.class.php';
    $database = new Database();
    $db = $database->getConnection();
    $objUsuDAO = new usuarioDAO($db);
    $objUsu = new Usuario($db);
    $objUsu->idUsuario = $idColaborador;
    $stmt = $objUsuDAO->readOne($objUsu);
    $destinatarios = array($objUsu->email, 'pmo@gestao.com.br', 'cristiane@gestao.com.br');
	//$destinatarios = array('pmo@gestao.com.br','cristiano@gestao.com.br');
    $assunto = 'Agenda semana '.$dia.'/'.$mes;
    $mensagem = '<font face="calibri">Foram realizadas alterações em sua agenda da semana.</br>Dessa forma segue, anexo, agenda atualizada.</br></br>Tenha uma excelente semana.';
    $mensagem.= '</br></br><b>Abraços,</br>Equipe PMO</b></font></br></br>';
    $mensagem.= $img;
    $arquivo = $dir;
    
    
    require_once(dirname(__FILE__)."/../PHPMailer/PHPMailerAutoload.php");
    $Subject = $assunto;
    $Message = $mensagem;
    $Username = $usuario;
    $Password = $senha;
    $mail = new PHPMailer();
    $body = $Message;
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = $Host; // SMTP server
    $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth = true; // enable SMTP authentication
    $mail->Port = $Port; // set the SMTP port for the service server
    $mail->Username = $Username; // account username
    $mail->Password = $Password; // account password
    $mail->SMTPSecure = "tls";

    $mail->SetFrom($usuario);
    $mail->Subject = $Subject;
    $mail->MsgHTML($body);
    foreach ($destinatarios as $To){
        $mail->AddAddress($To, "");
    }
        
    $mail->addAttachment($arquivo);
    
    if(!$mail->Send()) {
    $mensagemRetorno = 'Erro ao enviar e-mail: '. print($mail->ErrorInfo);
    } else {
        $ret = array();
        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "E-mail enviado com sucesso.";
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
        unlink($dir);
        return true;
    }    
}

//Gera a impressão do PDF do arquivo
public function geraImpressaoPDF(){
    $html = $this->geraHtmlParaImpressao();
    //$html = mb_convert_encoding($html, 'UTF-8');
    require_once(dirname(__FILE__).'/../dompdf/dompdf_config.inc.php');
    try{
        //Instanciamos a class do dompdf para o processo
        $dompdf= new DOMPDF();
        $dompdf->set_paper('A4', 'landscape');
        //Aqui nos damos um LOAD (carregamos) todos os nossos dados e formatacoes para geracao do PDF
        $dompdf->load_html($html);
        //Aqui nos damos inicio ao processo de exportacao (renderizar)
        $dompdf->render();
        $dompdf->stream('agenda_'.time().'.pdf');
    }catch(Exception $e){
        echo "Ocorreu o seguinte erro: ".$e->getMessage();
    }
}

//Função para enviar e-mail padrão com a alocação semanal
function enviaEmail($dia, $mes){
    //O diretório do arquivo a ser enviado
    $dir = $this->geraArquivoPDF();
    
    //Informa os dados do e-mail e do servidor smtp para envio
    $usuario = 'no-reply@gestao.com.br';
    $senha = 'noreply!@2014';
    //$Host = '132.245.78.146';
	$Host = 'smtp.office365.com';
    $Port = "587";
    
    //Monta as informações do e-mail
    $img = "<img src='http://agenda.gestao.com.br/PHPMailer/img/indice.png'>";
    
    //Monta os destinatarios
    $destinatarios = array('fran@gestao.com.br', 
                            'cristiano@gestao.com.br', 
                            'pmo@gestao.com.br',
                            'jaqueline@gestao.com.br',
                            'mayara@gestao.com.br');
	//$destinatarios = array('cristiano@gestao.com.br');
    require_once dirname(__FILE__).'/../config/database.class.php';
    require_once dirname(__FILE__).'/usuarioDAO.class.php';
    $database = new Database();
    $db = $database->getConnection();
    $objUsuDAO = new usuarioDAO($db);
    $stmt = $objUsuDAO->searchCol();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $destinatarios[].= $row['email'];
    }
    
    $assunto = 'Agenda semana '.$dia.'/'.$mes;
    $mensagem = '<font face="calibri">Segue anexo agenda referente a semana '.$dia.'/'.$mes.'.</br></br>Excelente semana a todos.';
    $mensagem.= '</br></br><b>Abraços,</br>Equipe PMO</b></font></br></br>';
    $mensagem.= $img;
    $arquivo = $dir;
    
    
    require_once(dirname(__FILE__)."/../PHPMailer/PHPMailerAutoload.php");
    $Subject = $assunto;
    $Message = $mensagem;
    $Username = $usuario;
    $Password = $senha;

    $mail = new PHPMailer();
    $body = $Message;
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = $Host; // SMTP server
    $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth = true; // enable SMTP authentication
    $mail->Port = $Port; // set the SMTP port for the service server
    $mail->Username = $Username; // account username
    $mail->Password = $Password; // account password
    $mail->SMTPSecure = "tls";

    $mail->SetFrom($usuario);
    $mail->Subject = $Subject;
    $mail->MsgHTML($body);
    foreach ($destinatarios as $To){
        $mail->AddAddress($To, "");
    }
    $mail->addAttachment($arquivo);
    
    if(!$mail->Send()) {
    $mensagemRetorno = 'Erro ao enviar e-mail: '. print($mail->ErrorInfo);
    } else {
        $ret = array();
        $ret[] = "<div class=\"alert alert-success alert-dismissable\">";
        $ret[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        $ret[] = "E-mail enviado com sucesso.";
        $ret[] = "</div>";
        $retorno = implode('', $ret);
        $_SESSION['Mensagem'] = $retorno;
        unlink($dir);
        return true;
    }    
}

function relatorioConsultorAlocacao($idColaborador, $dataIniAloc, $dataFimAloc){
    $html= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html lang="pt-Br">
        <head>
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1;" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            .left-margin{margin:0 .5em 0 0;}
            .right-button-margin{margin: 0 0 1em 0;overflow: hidden;}

            html, body, div, span, applet, object, iframe,h1, h2, h3, h4, h5, h6, p, blockquote, pre,a, abbr, acronym, address, big, cite, code,del, dfn, em, ins, kbd, q, s, samp,small, strike, strong, sub, sup, tt, var,b, u, i, center,dl, dt, dd, ol, ul, li,fieldset, form, label, legend,table, caption, tbody, tfoot, thead, tr, th, td,article, aside, canvas, details, embed,figure, figcaption, footer, header, hgroup,menu, nav, output, ruby, section, summary,time, mark, audio, video {margin: 0;padding: 0;border: 0;font-size: 100%;vertical-align: baseline;font-family: Calibri;font-size: 11px;}
            body{margin: 2cm 2cm 2cm 2cm}
            table {border-collapse: collapse;border-spacing: 0;font-family: Calibri;font-size: 11px; width:100%;}
            th,td {text-align:center;}
            th{font-weight: bold;}
        </style>
    </head>
    <body>';
    include_once dirname(__FILE__).'/../config/database.class.php';
    include_once dirname(__FILE__).'/../model/clienteDAO.class.php';
    include_once dirname(__FILE__).'/../model/usuarioDAO.class.php';

    $database = new Database();
    $db = $database->getConnection();

    $objAlocacao = new Alocacao($db);
    $objClienteDAO = new clienteDAO($db);
    $objCliente = new cliente($db);
    $objUsuarioDAO = new usuarioDAO($db);
    $objUsuario = new Usuario($db);
    
    $html.= '<div class="container">
            <table>
                <tr>
                    <th>Consultor</th>
                    <th>Data Alocação</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fim</th>
                    <th>Cliente</th>
                </tr>';
    $stmt = $this->buscaAlocacaoConsultor($idColaborador, $dataIniAloc, $dataFimAloc);

    while ($objAlocacao = $stmt->fetch(PDO::FETCH_OBJ)){
        $objAlocacao->dataAlocacao = $this->date_converterBR($objAlocacao->dataAlocacao);
        $html.= '<tr>';
            $objUsuario->idUsuario = $objAlocacao->idColaborador;
            $objUsuarioDAO->readOne($objUsuario);
            $html.= '<td>'.$objUsuario->nome.'</td>';
            $html.= '<td>'.$objAlocacao->dataAlocacao.'</td>';
            $html.= '<td>'.$objAlocacao->horaInicio.'</td>';
            $html.= '<td>'.$objAlocacao->horaFim.'</td>';
            $objCliente->idCliente = $objAlocacao->idCliente;
            $objClienteDAO->readOne($objCliente);
            $html.= '<td>'.$objCliente->nomeFantasia.'</td>';
        $html.= '</tr>';
    }
    $html.= '</table></body></html>';
    
    require_once(dirname(__FILE__).'/../dompdf/dompdf_config.inc.php');
    try{
        //Instanciamos a class do dompdf para o processo
        $dompdf= new DOMPDF();
        $dompdf->set_paper('A4', 'retrait');
        //Aqui n    os damos um LOAD (carregamos) todos os nossos dados e formatacoes para geracao do PDF
        $dompdf->load_html($html);
        //Aqui nos damos inicio ao processo de exportacao (renderizar)
        $dompdf->render();
        $dompdf->stream('consultor_alocacao.pdf');
    }catch(Exception $e){
        echo "Ocorreu o seguinte erro: ".$e->getMessage();
    }
}

function relatorioClienteAlocacao($idCliente, $idColaborador, $dataIniAloc, $dataFimAloc){
    $html= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html lang="pt-Br">
        <head>
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1;" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            .left-margin{margin:0 .5em 0 0;}
            .right-button-margin{margin: 0 0 1em 0;overflow: hidden;}

            html, body, div, span, applet, object, iframe,h1, h2, h3, h4, h5, h6, p, blockquote, pre,a, abbr, acronym, address, big, cite, code,del, dfn, em, ins, kbd, q, s, samp,small, strike, strong, sub, sup, tt, var,b, u, i, center,dl, dt, dd, ol, ul, li,fieldset, form, label, legend,table, caption, tbody, tfoot, thead, tr, th, td,article, aside, canvas, details, embed,figure, figcaption, footer, header, hgroup,menu, nav, output, ruby, section, summary,time, mark, audio, video {margin: 0;padding: 0;border: 0;font-size: 100%;vertical-align: baseline;font-family: Calibri;font-size: 11px;}
            body{margin: 2cm 2cm 2cm 2cm}
            table {border-collapse: collapse;border-spacing: 0;font-family: Calibri;font-size: 11px; width:100%;}
            th,td {text-align:center;}
            th{font-weight: bold;}
        </style>
    </head>
    <body>';
    include_once dirname(__FILE__).'/../config/database.class.php';
    include_once dirname(__FILE__).'/../model/clienteDAO.class.php';
    include_once dirname(__FILE__).'/../model/usuarioDAO.class.php';

    $database = new Database();
    $db = $database->getConnection();

    $objAlocacao = new Alocacao($db);
    $objClienteDAO = new clienteDAO($db);
    $objCliente = new cliente($db);
    $objUsuarioDAO = new usuarioDAO($db);
    $objUsuario = new Usuario($db);
    
    $html.= '<div class="container">
            <table>
                <tr>
                    <th>Cliente</th>
                    <th>Data Alocação</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fim</th>
                    <th>Consultor</th>
                </tr>';
    if(($idCliente == 'T') && ($idColaborador != 'T'))
    {
        $stmt = $this->buscaAlocacaoClientesWhereCol($idColaborador, $dataIniAloc, $dataFimAloc);
    }
    else if(($idCliente != 'T') && ($idColaborador == 'T')){
        $stmt = $this->buscaAlocacaoClienteWhereCli($idCliente, $dataIniAloc, $dataFimAloc);
    }
    else if(($idCliente == 'T') && ($idColaborador == 'T')){
        $stmt = $this->buscaAlocacaoClientes($dataIniAloc, $dataFimAloc);
    }else{
        $stmt = $this->buscaAlocacaoCliente($idCliente, $idColaborador, $dataIniAloc, $dataFimAloc);
    }

    while ($objAlocacao = $stmt->fetch(PDO::FETCH_OBJ)){
        $objAlocacao->dataAlocacao = $this->date_converterBR($objAlocacao->dataAlocacao);
        $html.= '<tr>';
            $objCliente->idCliente = $objAlocacao->idCliente;
            $objClienteDAO->readOne($objCliente);
            $html.= '<td>'.$objCliente->nomeFantasia.'</td>';
            $html.= '<td>'.$objAlocacao->dataAlocacao.'</td>';
            $html.= '<td>'.$objAlocacao->horaInicio.'</td>';
            $html.= '<td>'.$objAlocacao->horaFim.'</td>';
            $objUsuario->idUsuario = $objAlocacao->idColaborador;
            $objUsuarioDAO->readOne($objUsuario);
            $html.= '<td>'.$objUsuario->nome.'</td>';
        $html.= '</tr>';
    }
    $html.= '</table></body></html>';
    
    require_once(dirname(__FILE__).'/../dompdf/dompdf_config.inc.php');
    try{
        //Instanciamos a class do dompdf para o processo
        $dompdf= new DOMPDF();
        $dompdf->set_paper('A4', 'retrait');
        //Aqui n    os damos um LOAD (carregamos) todos os nossos dados e formatacoes para geracao do PDF
        $dompdf->load_html($html);
        //Aqui nos damos inicio ao processo de exportacao (renderizar)
        $dompdf->render();
        $dompdf->stream('cliente_alocacao.pdf');
    }catch(Exception $e){
        echo "Ocorreu o seguinte erro: ".$e->getMessage();
    }
}
} 
?>