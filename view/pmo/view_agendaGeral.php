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
$f = $agendaDAO->getFirstDay($month, $year);
$firthday = $agendaDAO->getFirstDay($month, $year);
$n = $agendaDAO->getNumberDay($month, $year);

echo $agendaDAO->renderizaBotoesMensal();
echo $agendaDAO->renderizaPaginacaoMensal($day, $month, $year, $month_ant, $year_ant, $month_prox, $year_prox);
echo '</br>';
    //Cria a tabela
    echo "<table class='table table-responsive table-bordered'>";
    
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
                    while($f <= $n){
                        echo "<td class='text-center destaque'><strong>".$f."-".$agendaDAO->getNameMon($month);
                        echo "</strong></td> ";
                        $f++;
                    }
        echo "</tr>";
        
        //Preenche a segunda rowspan com os dias por extenso
        echo "<tr>";
            while($firthday <= $n){
                $dayExtensive = $agendaDAO->getDayExtensive($month, $firthday, $year);
                    echo "<td class='text-center destaque'><strong>".$dayExtensive;
                    echo "</strong></td>";   
                $firthday++;
            }
        echo "</tr>";

    //Instancia os objetos
    $usuarioDAO = new usuarioDAO($db);
    $stmtUsu = $usuarioDAO->searchCol();
    
    //Preenche a primeira coluna com o nome dos colaboradores
    //Variavel que defini se a linha vai ter background ou nao
    $preenchimento = "0";
    echo "<tr>";        
        while($usuario = $stmtUsu->fetch(PDO::FETCH_OBJ)){
            if($preenchimento == "1"){
                echo "<th rowspan='2' class='text-center destaque colaboradores'>";
            }else{
                echo "<th rowspan='2' class='text-center colaboradores'>";
            }
            
            echo $usuario->nome;
            echo "</th>";
            
            $alocacaoDAO = new alocacaoDAO($db);
            $objAlocacao = new Alocacao($db);
            $objClienteDAO = new clienteDAO($db);
            $objCliente = new Cliente($db);
            $objTipoAlocacao = new TipoAlocacao($db);
            $objTipoAlocDAO = new tipoAlocacaoDAO($db);
            
            //Preenche a primeira rowspan com as alocações da manhã
            $f = 1;
            while($f <= $n){
                $dataAloc = date('Y-m-d', mktime(0,0,0,$month,$f,$year));
                if($stmtClient = $alocacaoDAO->searchMorning($dataAloc, $usuario->idUsuario)){
                    if($stmtClient->rowcount() == 1){
                        while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                            $objCliente->idCliente = $objAlocacao->idCliente;
                            $objClienteDAO->readOne($objCliente);
                            $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                            if($objAlocacao->bloqueado == 'S'){
                                echo $agendaDAO->renderizaAlocacaoBloqueadaMensal($objAlocacao);
                            }else if($objAlocacao->idTipAloc == '3'){
                                echo $agendaDAO->renderizaAlocacaoFolgaMensal($objAlocacao, $objCliente, $objTipoAlocacao);
                            }else if($objAlocacao->idTipAloc == '4'){
                                echo $agendaDAO->renderizaAlocacaoFeriadoMensal($objAlocacao, $objCliente, $objTipoAlocacao);
                            }else if($objAlocacao->idTipAloc == '5'){
                                echo $agendaDAO->renderizaAlocacaoFeriasMensal($objAlocacao, $objCliente, $objTipoAlocacao);
                            }else{
                                echo $agendaDAO->renderizaAlocacaoMensal($preenchimento, $objAlocacao, $objCliente, $objTipoAlocacao);
                            }
                        }
                    }else{
                        if($preenchimento == "0"){
                            echo '<td class="text-center">';
                        }else{
                            echo '<td class="text-center destaque">';
                        }
                        while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                            $objCliente->idCliente = $objAlocacao->idCliente;
                            $objClienteDAO->readOne($objCliente);
                            $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                            echo $agendaDAO->renderizaAlocacaoMultMensal($objAlocacao, $objCliente, $objTipoAlocacao);
                        }
                    }
                }else{
                    echo $agendaDAO->renderizaSemAlocacaoMensal($f, $month, $year, $dataAloc, $preenchimento, $usuario);
                }
                $f++;
            }
    echo "</tr>";
    echo "<tr>";
            //Preenche a segunda rowspan com as alocações da tarde
            $f = 1;
            while($f <= $n){
            $dataAloc = date('Y-m-d', mktime(0,0,0,$month,$f,$year));
            if($stmtClient = $alocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario)){
                if($stmtClient->rowcount() == 1){
                    while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        $objCliente->idCliente = $objAlocacao->idCliente;
                        $objClienteDAO->readOne($objCliente);
                        $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                        if($objAlocacao->bloqueado == 'S'){
                            echo $agendaDAO->renderizaAlocacaoBloqueadaMensal($objAlocacao);
                        }else if($objAlocacao->idTipAloc == '3'){
                            echo $agendaDAO->renderizaAlocacaoFolgaMensal($objAlocacao, $objCliente, $objTipoAlocacao);
                        }else if($objAlocacao->idTipAloc == '4'){
                            echo $agendaDAO->renderizaAlocacaoFeriadoMensal($objAlocacao, $objCliente, $objTipoAlocacao);
                        }else if($objAlocacao->idTipAloc == '5'){
                            echo $agendaDAO->renderizaAlocacaoFeriasMensal($objAlocacao, $objCliente, $objTipoAlocacao);
                        }else{
                            echo $agendaDAO->renderizaAlocacaoMensal($preenchimento, $objAlocacao, $objCliente, $objTipoAlocacao);
                        }
                    }
                }else{
                    if($preenchimento == "0"){
                        echo '<td class="text-center">';
                    }else{
                        echo '<td class="text-center destaque">';
                    }
                    while($objAlocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        $objCliente->idCliente = $objAlocacao->idCliente;
                        $objClienteDAO->readOne($objCliente);
                        $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);
                        echo $agendaDAO->renderizaAlocacaoMultMensal($objAlocacao, $objCliente, $objTipoAlocacao);
                    }
                }
            }else{
                echo $agendaDAO->renderizaSemAlocacaoMensal($f, $month, $year, $dataAloc, $preenchimento, $usuario);
            }
            $f++;
            }
    echo "</tr>";
            if($preenchimento == "0"){
                $preenchimento = "1";
            }else{
                $preenchimento = "0";
            }
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
        placement: 'left'
    });
});
</script>

<?php
include_once "footer.php";
?>