<?php
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}
?>

<?php

    include_once '../../model/agendaDAO.class.php';
    include_once '../../model/usuarioDAO.class.php';
    include_once '../../model/clienteDAO.class.php';
    include_once '../../config/database.class.php';
    include_once '../../model/alocacaoDAO.class.php';
    include_once '../../model/Cliente.class.php';
    include_once '../../model/TipoAlocacao.class.php';
    include_once '../../model/tipoAlocacaoDAO.class.php';

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
$mesAtual = $agendaDAO->getActualMonth();
$anoAtual = $agendaDAO->getActualYear();
$f = $agendaDAO->getFirstDay($month, $year);
$firthday = $agendaDAO->getFirstDay($month, $year);
$n = $agendaDAO->getNumberDay($month, $year);


echo "<div class=\"btn-toolbar\" role=\"toolbar\" aria-label=\"\">";
    echo "<div class=\"btn-group\" role=\"group\" aria-label=\"...\">";
        echo "<button type=\"button\" class=\"btn btn-default\" onclick=\"location.href='./agendaSemanalGeral.php'\">Calendario Semanal</button>";
        echo "<button type=\"button\" class=\"btn btn-default\" onclick=\"location.href='./agendaGeral.php'\">Calendario Mensal</button>";
    echo "</div>";
echo "</div>";
echo "<div class=\"btn-toolbar\" role=\"toolbar\" aria-label=\"\">";
    echo "<div class=\"btn-group\" role=\"group\" aria-label=\"...\">";
        echo "<ul class='pagination'>";
        echo "<li><a href='?data=$day/$month_ant/$year_ant'><span aria-hidden='true'>&laquo;</span><span class='sr-only'>Anterior</span></a></li>";
        echo "<li class='disabled'><a href='#'>".$agendaDAO->getNameMon($month)."-".$year."</a></li>";
        if(($month_prox > $mesAtual) && ($year_prox >= $anoAtual)){
            echo "<li><a href='?data=$day/$month/$year'><span aria-hidden='true'>&raquo;</span><span class='sr-only'>Próximo</span></a></li>";
        }else{
            echo "<li><a href='?data=$day/$month_prox/$year_prox'><span aria-hidden='true'>&raquo;</span><span class='sr-only'>Próximo</span></a></li>";
        }
        echo "</ul>";
    echo "</div>";
    echo "<div style='text-align: right; margin-right: 30px'>";
        echo "<img src='http://agenda.gestao.com.br/PHPMailer/img/logo_joinville.jpg' alt='Logo Gestão' style='max-width:200px;'>";    
        echo "<img src='http://agenda.gestao.com.br/PHPMailer/img/indice.png' alt='Logo Gestão' style='max-width:200px;'>";
    echo "</div>";
echo "</div>";

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
    $alocacaoDAO = new alocacaoDAO($db);
    $alocacao = new Alocacao($db);
    $clienteDAO = new clienteDAO($db);
    $cliente = new Cliente($db);
    $tipoAlocacao = new TipoAlocacao($db);
    $tipAlocDAO = new tipoAlocacaoDAO($db);
    
    //Preenche a primeira coluna com o nome dos colaboradores
    //Variavel que defini se a linha vai ter background ou nao
    $preenchimento = "1";
    echo "<tr>";        
        while($usuario = $stmtUsu->fetch(PDO::FETCH_OBJ)){
            if($preenchimento == "0"){
                echo "<th rowspan='2' class='text-center destaque colaboradores'>";
            }else{
                echo "<th rowspan='2' class='text-center colaboradores'>";
            }
            echo $usuario->nome;
            echo "</th>";
            
            //Preenche a primeira rowspan com as alocações da manhã
            $f = 1;
            while($f <= $n){
                $dataAloc = date('Y-m-d', mktime(0,0,0,$month,$f,$year));
                if($stmtClient = $alocacaoDAO->searchMorning($dataAloc, $usuario->idUsuario)){
                    while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        $cliente->idCliente = $alocacao->idCliente;
                        $clienteDAO->readOne($cliente);
                        $tipoAlocacao->desAloc = $tipAlocDAO->readName($alocacao->idTipAloc);
                            /*
                             * Imprimi a classe de acordo com o tipo de alocacao
                             * Essa classe e uma atribuicao css de background
                             */
                            if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '3')){
                                echo "<td class=\"text-center folga\">";
                            }else if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '4')){
                                echo "<td class=\"text-center feriado\">";
                            }else if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '5')){
                                echo "<td class=\"text-center ferias\">";
                            }else if($preenchimento == "0"){
                                echo "<td class=\"text-center destaque\">";
                            }else{
                                echo "<td class=\"text-center\">";
                            }
                            //codifica a descrição da alocação para enviar via get
                            $descricao = urlencode($alocacao->desAlocacao);
                            $periodo = 'M';
                            //chama o modal e seta as variaveis via get
                            if($alocacao->confirmado == 'S'){
                                echo "<a class=\"confirmado\""; 
                            }else if($alocacao->confirmado == 'N'){
                                echo "<a class=\"nconfirmado\""; 
                            }
                            echo " data-toggle=\"modal\" href=\"modalAgenda.php?idCliente=$alocacao->idCliente&data=$alocacao->dataAlocacao"
                                    . "&idColaborador=$alocacao->idColaborador&dataAlocacao=$dataAloc&descricao=$descricao"
                                    . "&periodo=$periodo&confirma=$alocacao->confirmado&tipAloc=$alocacao->idTipAloc\" data-target=\"#myModal\">";
                                //imprime o cliente e o tipo de alocacao na tabela
                                echo "<div id=\"colaborador\" class='show-tooltip' title='$alocacao->desAlocacao'>";
                                    if($alocacao->idCliente == '152'){
                                        echo $tipoAlocacao->desAloc;
                                    }else{
                                        echo $cliente->nomeFantasia." - ".$tipoAlocacao->desAloc;
                                    }
                                echo "</div>";
                            echo "</a>";
                            echo "</td>";
                    }
                }else{
                    $dayOfWeek = date('l', mktime(0,0,0,$month, $f, $year));
                    
                    //Imprime sabado e domingo em destaque
                    if(($dayOfWeek == 'Saturday') || ($dayOfWeek == 'Sunday')){
                        echo "<td class='text-center destaque'>&nbsp;</td>";
                    }else if($preenchimento == "0"){
                        echo "<td class='text-center destaque'>&nbsp;</td>";
                    } else{
                        echo "<td class='text-center'>&nbsp;</td>";
                    }
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
                        while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        $cliente->idCliente = $alocacao->idCliente;
                        $clienteDAO->readOne($cliente);
                        $tipoAlocacao->desAloc = $tipAlocDAO->readName($alocacao->idTipAloc);
                            /*
                             * Imprimi a classe de acordo com o tipo de alocacao
                             * Essa classe e uma atribuicao css de background
                             */
                            if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '3')){
                                echo "<td class=\"text-center folga\">";
                            }else if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '4')){
                                echo "<td class=\"text-center feriado\">";
                            }else if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '5')){
                                echo "<td class=\"text-center ferias\">";
                            }else if($preenchimento == "0"){
                                echo "<td class=\"text-center destaque\">";
                            }else{
                                echo "<td class=\"text-center\">";
                            }
                            //codifica a descrição da alocação para enviar via get
                            $descricao = urlencode($alocacao->desAlocacao);
                            $periodo = 'V';
                            //chama o modal e seta as variaveis via get
                            if($alocacao->confirmado == 'S'){
                                echo "<a class=\"confirmado\""; 
                            }else if($alocacao->confirmado == 'N'){
                                echo "<a class=\"nconfirmado\""; 
                            }
                            echo " data-toggle=\"modal\" href=\"modalAgenda.php?idCliente=$alocacao->idCliente&data=$alocacao->dataAlocacao"
                                    . "&idColaborador=$alocacao->idColaborador&dataAlocacao=$dataAloc&descricao=$descricao"
                                    . "&periodo=$periodo&confirma=$alocacao->confirmado&tipAloc=$alocacao->idTipAloc\" data-target=\"#myModal\">";
                                //imprime o cliente e o tipo de alocacao na tabela
                                echo "<div class='show-tooltip' title='$alocacao->desAlocacao'>";
                                    if($alocacao->idCliente == '152'){
                                        echo $tipoAlocacao->desAloc;
                                    }else{
                                        echo $cliente->nomeFantasia." - ".$tipoAlocacao->desAloc;
                                    }
                                echo "</div>";
                            echo "</td>";
                        }
                    }else{
                        $dayOfWeek = date('l', mktime(0,0,0,$month, $f, $year));
                        
                        //Imprime sabado e domingo em destaque
                        if(($dayOfWeek == 'Saturday') || ($dayOfWeek == 'Sunday')){
                            echo "<td class='text-center destaque'>&nbsp;</td>";
                        }else if($preenchimento == "0"){
                            echo "<td class='text-center destaque'>&nbsp;</td>";
                        } else{
                            echo "<td class='text-center'>&nbsp;</td>";
                        }
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