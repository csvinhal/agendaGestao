<?php
include_once "header.php";
if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}
?>

<?php
    include_once dirname(__FILE__).'/../../model/agendaDAO.class.php';
    include_once dirname(__FILE__).'/../../model/usuarioDAO.class.php';
    include_once dirname(__FILE__).'/../../model/clienteDAO.class.php';
    include_once dirname(__FILE__).'/../../config/database.class.php';
    include_once dirname(__FILE__).'/../../model/alocacaoDAO.class.php';
    include_once dirname(__FILE__).'/../../model/Cliente.class.php';
    include_once dirname(__FILE__).'/../../model/TipoAlocacao.class.php';
    include_once dirname(__FILE__).'/../../model/tipoAlocacaoDAO.class.php';
$database = new Database();
$db = $database->getConnection();
//navegaçao entre os meses
if(empty($_GET['data'])){
    $day = date('d');
    $month =ltrim(date('m'),"0");
    $year = date('Y');
    $data = date('d/m/Y', mktime(0,0,0,$month,$day,$year));
}else{
    $data = $_GET['data'];
    $date = explode('/',$_GET['data']);//nova data
    $day = $date[0];
    $month = $date[1];
    $year = $date[2];
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
$agendaDAO = new agendaDAO($db);
$agendaDAO->getActualDay();
$n = $agendaDAO->getNumberDay($month, $year);
$idColaborador = $_SESSION['user_id'];
if(isset($_GET['semana'])){
    $semana = $_GET['semana'];
}else{
    $semana = $agendaDAO->retornaSemanaCalendario($month, $year, $month_ant, $year_ant);
}
if($semana == 0){
    $semanaAnt = 0;
}else{
    $semanaAnt = $semana - 1;
}
$qtdSemanas = $agendaDAO->contaCalendarioSemanal($month, $year, $month_ant, $year_ant) - 1;
if($semana >= $qtdSemanas){
    $semanaProx = $qtdSemanas;
}else{
    $semanaProx = $semana + 1;
}

$calendario = implode(" ", $agendaDAO->retornaCalendarioSemanal($month, $year, $month_ant, $year_ant, $semana));
$diasSem = explode(" ", $calendario);

$html = '<div class="btn-toolbar" role="toolbar">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" onclick="location.href=\'./agendaSemanal.php\'">Calendario Semanal</button>
                    <button type="button" class="btn btn-default" onclick="location.href=\'./agendaConsultor.php\'">Calendario Mensal</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" onclick="location.href=\'../../controller/controleAlocacao.php?operacao=imprimir&data=$data&semana=$semana&idColaborador=$idColaborador\'">Imprimir Agenda</button>
                </div>
            </div>';
echo $html;
echo $agendaDAO->renderizaPaginacao($day, $month, $year, $semana, $month_ant, $year_ant, $month_prox, $year_prox, $semanaAnt, $semanaProx);
echo "</br>";
    //Cria a tabela
    echo "<table class='table table-bordered table-responsive'>";
    
    /* Estrutura da tabela
    *<tr rowspan = '2'>
    * <th></th>
    * <td></td>
    *</tr> 
    * <tr>
    *<td></td>
    *</tr> 
    */
        //Insere a primeira linha Colaborador 
        echo "<tr>";
            echo "<th rowspan='2' class='text-center destaque colaboradores'>";
            echo "Colaborador";
            echo "</th>";
                //Preenche a primeira rowspan com os dias do mês e o nome do mês
                echo $agendaDAO->renderizaDiasSemana($semana, $diasSem, $month, $month_ant, $month_prox); 
        echo "</tr>";
        //Preenche a segunda rowspan com os dias por extenso
        echo "<tr>";
            echo $agendaDAO->renderizaDiaExtenso($semana, $diasSem, $month, $year, $month_ant, $month_prox, $year_ant, $year_prox);
        echo "</tr>";
        
        //Instancia os objetos
        $usuarioDAO = new usuarioDAO($db);
        $stmtUsu = $usuarioDAO->searchConAtivo($idColaborador);
        $objAlocacaoDAO = new alocacaoDAO($db);
        $objClienteDAO = new clienteDAO($db);
        $objTipoAlocDAO = new tipoAlocacaoDAO($db);
        $objAlocacao = new Alocacao($db);
        $objCliente = new Cliente($db);
        $objTipoAlocacao = new TipoAlocacao($db);
    
        //Preenche a primeira coluna com o nome dos colaboradores
        $preenchimento = "0";
        echo "<tr>";        
            while($usuario = $stmtUsu->fetch(PDO::FETCH_OBJ)){
                echo "<th rowspan='2' class='text-center colaboradores'>";
                echo $usuario->nome;
                echo "</th>";
                //Preenche a primeira rowspan com as alocações da manhã
                $f = 0;
                while($f <= 6){
                    $dataAloc = $agendaDAO->retornaDataFormatada($f, $semana, $diasSem, $month, $year, $month_ant, $month_prox, $year_ant, $year_prox);
                    if($stmtClient = $objAlocacaoDAO->searchMorning($dataAloc, $usuario->idUsuario)){
                        if($stmtClient->rowcount() == 1){
                            while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                            $objCliente->idCliente = $objAlocacao->idCliente;
                            $objClienteDAO->readOne($objCliente);
                            $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                                if($objAlocacao->bloqueado == 'S'){
                                    echo $agendaDAO->renderizaAlocacaoBloqueada($objAlocacao, $semana);
                                }else if($objAlocacao->idTipAloc == '3'){
                                    echo $agendaDAO->renderizaAlocacaoFolga($objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                                }else if($objAlocacao->idTipAloc == '4'){
                                    echo $agendaDAO->renderizaAlocacaoFeriado($objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                                }else if($objAlocacao->idTipAloc == '5'){
                                    echo $agendaDAO->renderizaAlocacaoFerias($objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                                }else{
                                    echo $agendaDAO->renderizaAlocacao($preenchimento, $objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                                }
                            }
                        }else{
                            echo '<td class="text-center">';
                            while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                                $objCliente->idCliente = $objAlocacao->idCliente;
                                $objClienteDAO->readOne($objCliente);
                                $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                                echo $agendaDAO->renderizaAlocacaoMult($objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                            }
                            echo '</td>';
                        }
                    }else{
                        echo $agendaDAO->renderizaSemAlocacao($diasSem, $f, $preenchimento, $usuario, $month, $year, $month_ant, $month_prox, $year_ant, $year_prox, $semana, $dataAloc);
                    }
                $f++;
                }
        echo "</tr>";
    
        echo "<tr>";
                //Preenche a segunda rowspan com as alocações da tarde
                $f = 0;
            while($f <= 6){
                $dataAloc = $agendaDAO->retornaDataFormatada($f, $semana, $diasSem, $month, $year, $month_ant, $month_prox, $year_ant, $year_prox);
                $stmtClient = $objAlocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario);
                if($stmtClient = $objAlocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario)){
                    if($stmtClient->rowcount() == 1){
                        while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        $objCliente->idCliente = $objAlocacao->idCliente;
                        $objClienteDAO->readOne($objCliente);
                        $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                            if($objAlocacao->bloqueado == 'S'){
                                echo $agendaDAO->renderizaAlocacaoBloqueada($objAlocacao, $semana);
                            }else if($objAlocacao->idTipAloc == '3'){
                                echo $agendaDAO->renderizaAlocacaoFolga($objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                            }else if($objAlocacao->idTipAloc == '4'){
                                echo $agendaDAO->renderizaAlocacaoFeriado($objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                            }else if($objAlocacao->idTipAloc == '5'){
                                echo $agendaDAO->renderizaAlocacaoFerias($objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                            }else{
                                echo $agendaDAO->renderizaAlocacao($preenchimento, $objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                            }
                        }
                    }else{
                        echo '<td class="text-center">';
                        while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                            $objCliente->idCliente = $objAlocacao->idCliente;
                            $objClienteDAO->readOne($objCliente);
                            $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                            echo $agendaDAO->renderizaAlocacaoMult($objAlocacao, $objCliente, $objTipoAlocacao, $semana);
                        }
                        echo '</td>';
                    }
                }else{
                    echo $agendaDAO->renderizaSemAlocacao($diasSem, $f, $preenchimento, $usuario, $month, $year, $month_ant, $month_prox, $year_ant, $year_prox, $semana, $dataAloc);
                }
            $f++;
            }
        echo "</tr>";
            }
    echo "</table>";
?>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Modal title</h4>
            </div>	
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->

<script>
$('.show-tooltip').each(function(e) {
    var p = $(this).parent();
    if(p.is('td')) {
        /* if your tooltip is on a <td>, transfer <td>'s padding to wrapper */
        $(this).css('padding', p.css('padding'));
        p.css('padding', '0 0');
    }
    $(this).tooltip({
        toggle: 'tooltip',
        placement: 'top'
    });
});
</script>

<?php
    include_once "footer.php";
