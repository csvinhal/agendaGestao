<?php
$page_title = "Agenda Geral";
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}
?>

<?php

    include_once '../model/agendaDAO.class.php';
    include_once '../model/usuarioDAO.class.php';
    include_once '../model/clienteDAO.class.php';
    include_once '../config/database.class.php';
    include_once '../model/alocacaoDAO.class.php';
    include_once '../model/Cliente.class.php';
    include_once '../model/TipoAlocacao.class.php';
    include_once '../model/tipoAlocacaoDAO.class.php';

$database = new Database();
$db = $database->getConnection();

//navegaçao entre os meses
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


$agendaDAO = new agendaDAO($db);
$agendaDAO->getActualDay();
$f = $agendaDAO->getFirstDay($month, $year);
$firthday = $agendaDAO->getFirstDay($month, $year);
$n = $agendaDAO->getNumberDay($month, $year);


echo "<div>";
    echo "<ul class='pagination'>";
    echo "<li><a href='?data=$day/$month_ant/$year_ant'><span aria-hidden='true'>&laquo;</span><span class='sr-only'>Anterior</span></a></li>";
    echo "<li class='disabled'><a href='#'>".$agendaDAO->getNameMon($month)."-".$year."</a></li>";
    echo "<li><a href='?data=$day/$month_prox/$year_prox'><span aria-hidden='true'>&raquo;</span><span class='sr-only'>Próximo</span></a></li>";
    echo "</ul>";
echo "</div>";


    //Cria a tabela
    echo "<table class='table table-responsive table-bordered table-responsive'>";
    
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
            echo "<th rowspan='2' class='text-center info'>";
            echo "Colaborador";
            echo "</th>";
                    //Preenche a primeira rowspan com os dias do mês e o nome do mês
                    while($f <= $n){
                        echo "<td class='text-center info'><strong>".$f."-".$agendaDAO->getNameMon($month);
                        echo "</strong></td> ";
                        $f++;
                    }
        echo "</tr>";
        //Preenche a segunda rowspan com os dias por extenso
        echo "<tr>";
            while($firthday <= $n){
                $dayExtensive = $agendaDAO->getDayExtensive($month, $firthday, $year);
                echo "<td class='text-center info'><strong>".$dayExtensive;
                echo "</strong></td>";
                $firthday++;
            }
        echo "</tr>";

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
    echo "<tr>";        
        while($usuario = $stmtUsu->fetch(PDO::FETCH_OBJ)){
            echo "<th rowspan='2' class='text-center'>";
            echo $usuario->nome;
            echo "</th>";
            
            //Preenche a primeira rowspan com as alocações da manhã
            $f = 1;
            while($f <= $n){
                $dataAloc = $year.'-'.$month.'-'.$f;
                if($stmtClient = $alocacaoDAO->searchMorning($dataAloc, $usuario->idUsuario)){
                    while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        $cliente->idCliente = $alocacao->idCliente;
                        $clienteDAO->readOne($cliente);
                        $tipoAlocacao->desAloc = $tipAlocDAO->readName($alocacao->idTipAloc);
                        if($alocacao->confirmado == 'S'){
                            echo "<td class='text-center success'>";
                            //codifica a descrição da alocação para enviar via get
                            $descricao = urlencode($alocacao->desAlocacao);
                            $periodo = 'M';
                            //chama o modal e seta as variaveis via get
                            echo "<a data-toggle=\"modal\" href=\"modalAgenda.php?idCliente=$alocacao->idCliente&data=$alocacao->dataAlocacao"
                                    . "&idColaborador=$alocacao->idColaborador&dataAlocacao=$dataAloc&descricao=$descricao"
                                    . "&periodo=$periodo&confirma=$alocacao->confirmado&tipAloc=$alocacao->idTipAloc\" data-target=\"#myModal\">";
                                //imprime o cliente e o tipo de alocacao na tabela
                                echo "<div id=\"colaborador\" class='show-tooltip' title='$alocacao->desAlocacao'>";
                                        echo $cliente->razaoSocial."(".$tipoAlocacao->desAloc.")";
                                echo "</div>";
                            echo "</a>";
                            echo "</td>";
                        }else{
                            echo "<td class='danger text-center'>";
                            //codifica a descrição da alocação para enviar via get
                            $descricao = urlencode($alocacao->desAlocacao);
                            $periodo = 'V';
                            //chama o modal e seta as variaveis via get
                            echo "<a data-toggle=\"modal\" href=\"modalAgenda.php?idCliente=$alocacao->idCliente&data=$alocacao->dataAlocacao"
                                    . "&idColaborador=$alocacao->idColaborador&dataAlocacao=$dataAloc&descricao=$descricao"
                                    . "&periodo=$periodo&confirma=$alocacao->confirmado&tipAloc=$alocacao->idTipAloc\" data-target=\"#myModal\">";
                                //imprime o cliente e o tipo de alocacao na tabela    
                                echo "<div class='show-tooltip' title='$alocacao->desAlocacao'>";
                                        echo $cliente->razaoSocial."(".$tipoAlocacao->desAloc.")";
                                echo "</div>";
                            echo "</td>";
                        }
                    }
                }else{
                    echo "<td class='text-center'>Sem Compromissos</td>";
                }
                $f++;
            }
    echo "</tr>";
    echo "<tr>";
            //Preenche a segunda rowspan com as alocações da tarde
                $f = 1;
                while($f <= $n){
                    $dataAloc = $year.'-'.$month.'-'.$f;
                    if($stmtClient = $alocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario)){
                        while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        $cliente->idCliente = $alocacao->idCliente;
                        $clienteDAO->readOne($cliente);
                        $tipoAlocacao->desAloc = $tipAlocDAO->readName($alocacao->idTipAloc);
                        if($alocacao->confirmado == 'S'){
                            echo "<td class='text-center success' >";
                            //codifica a descrição da alocação para enviar via get
                            $descricao = urlencode($alocacao->desAlocacao);
                            $periodo = 'V';
                            //chama o modal e seta as variaveis via get
                            echo "<a data-toggle=\"modal\" href=\"modalAgenda.php?idCliente=$alocacao->idCliente&data=$alocacao->dataAlocacao"
                                    . "&idColaborador=$alocacao->idColaborador&dataAlocacao=$dataAloc&descricao=$descricao"
                                    . "&periodo=$periodo&confirma=$alocacao->confirmado&tipAloc=$alocacao->idTipAloc\" data-target=\"#myModal\">";
                                //imprime o cliente e o tipo de alocacao na tabela
                                echo "<div class='show-tooltip' title='$alocacao->desAlocacao'>";
                                    echo $cliente->razaoSocial."(".$tipoAlocacao->desAloc.")";
                                echo "</div>";
                            echo "</td>";
                        }else{
                            echo "<td class='danger text-center'>";
                            //codifica a descrição da alocação para enviar via get
                            $descricao = urlencode($alocacao->desAlocacao);
                            $periodo = 'V';
                            //chama o modal e seta as variaveis via get
                            echo "<a data-toggle=\"modal\" href=\"modalAgenda.php?idCliente=$alocacao->idCliente&data=$alocacao->dataAlocacao"
                                    . "&idColaborador=$alocacao->idColaborador&dataAlocacao=$dataAloc&descricao=$descricao"
                                    . "&periodo=$periodo&confirma=$alocacao->confirmado&tipAloc=$alocacao->idTipAloc\" data-target=\"#myModal\">";
                                //imprime o cliente e o tipo de alocacao na tabela
                                echo "<div class='show-tooltip' title='$alocacao->desAlocacao'>";
                                        echo $cliente->razaoSocial."(".$tipoAlocacao->desAloc.")";
                                echo "</div>";
                            echo "</td>";
                        }
                        }
                    }else{
                        echo "<td class='text-center'>Sem Compromissos</td>";
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
        toggle: 'toolip',
        placement: 'left'
    });
});

//$('#myModal').on('show.bs.modal', function(e) {
//        var descricao = <?php echo $descricao ?>;
//        $('.modal-body #desAlocacao').val(descricao);
//    })

</script>
<?php
include_once "footer.php";
?>