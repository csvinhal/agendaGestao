<?php
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}

include_once '../../config/database.class.php';
include_once '../../model/usuarioDAO.class.php';
$database = new Database();
$db = $database->getConnection();
?>

<form action='view_agendaParticular.php' method='post'>
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group" role="group">
        <?php
            /*
             * Lista os colaboradores
             */
            $usuarioDAO = new usuarioDAO($db);
            $stmt = $usuarioDAO->searchCol();
            //Acrescenta select drop-down
            echo "<select class='form-control' id='idColaborador' name='idColaborador' required>";
            echo "<option value=\"\">Selecione...</option>";
            while ($row_usu = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row_usu);
                echo "<option value='$idUsuario'>$nome</option>";
            }              
            echo "</select>";
        ?>
        </div>
        <div class="btn-group" role="group">
            <div class="form-group has-feedback">
                <input type="text" id="dataAlocacao" name="dataAlocacao" class="form-control" placeholder="Data" autocomplete="off" required>
                </input>
            </div>
        </div>
        <div class="btn-group" role="group">
            <button type="submit" class="btn btn-default">Mostrar Agenda</button>
        </div>
    </div>
</form>

<?php
    if(isset($_POST['idColaborador'], $_POST['dataAlocacao']) && (empty($_POST['idColaborador']) || empty($_POST['dataAlocacao']))){
        echo "<div class=\"alert alert-danger alert-dismissable\">";
        echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        echo "O colaborador e a data devem ser informados!";
        echo "</div>";
    }else if(isset($_POST['idColaborador'], $_POST['dataAlocacao'])){
        $idColaborador = $_POST['idColaborador'];
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
        if($_POST['dataAlocacao']){
            $data = $_POST['dataAlocacao'];
            $date = explode('/',$_POST['dataAlocacao']);//nova data
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
        $semana = $agendaDAO->retornaSemanaDia($month, $year, $month_ant, $year_ant, $day);
        $calendario = implode(" ", $agendaDAO->retornaCalendarioSemanal($month, $year, $month_ant, $year_ant, $semana));
        $diasSem = explode(" ", $calendario);
        
        echo "<div class=\"btn-toolbar\" role=\"toolbar\" aria-label=\"\">";
            echo "<div class=\"btn-group\" role=\"group\" aria-label=\"...\">";
                echo "<button type=\"button\" class=\"btn btn-default\" onclick=\"location.href='../../controller/controleAlocacao.php?operacao=imprimir&idColaborador=$idColaborador&data=$data&semana=$semana'\">Imprimir Agenda</button>";
            echo "</div>";
            echo "<div class=\"btn-group\" role=\"group\" aria-label=\"...\">";
                echo "<button type=\"button\" class=\"btn btn-default\" onclick=\"location.href='../../controller/controleAlocacao.php?operacao=enviar&idColaborador=$idColaborador&data=$data&semana=$semana&dia=$diasSem[0]&mes=$month'\">Enviar Agenda por E-mail</button>";
            echo "</div>";
        echo "</div>";
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
                            $f = 0;
                            while($f <= 6){
                                if(($semana == 0) && ($diasSem[$f] > 7)){
                                    echo "<td class='text-center destaque'><strong>".$diasSem[$f]."-".$agendaDAO->getNameMon($month_ant);
                                    echo "</strong></td> ";
                                }else if(($semana == 4) && ($diasSem[$f] < 7)){
                                    echo "<td class='text-center destaque'><strong>".$diasSem[$f]."-".$agendaDAO->getNameMon($month_prox);
                                    echo "</strong></td> ";
                                }else{
                                    echo "<td class='text-center destaque'><strong>".$diasSem[$f]."-".$agendaDAO->getNameMon($month);
                                    echo "</strong></td> ";
                                }
                                $f++;
                            }
                echo "</tr>";
                //Preenche a segunda rowspan com os dias por extenso
                echo "<tr>";
                    $f = 0;
                    while($f <= 6){
                        if(($semana == 0) && ($diasSem[$f] > 7)){
                            $dayExtensive = $agendaDAO->getDayExtensive($month_ant, $diasSem[$f], $year_ant);
                        }else if(($semana == 4) && ($diasSem[$f] < 7)){
                            $dayExtensive = $agendaDAO->getDayExtensive($month_prox, $diasSem[$f], $year_prox);
                        }else{
                            $dayExtensive = $agendaDAO->getDayExtensive($month, $diasSem[$f], $year);
                        }
                            echo "<td class='text-center destaque'><strong>".$dayExtensive;
                            echo "</strong></td>";   
                        $f++;
                    }
                echo "</tr>";

                //Instancia os objetos
                $usuarioDAO = new usuarioDAO($db);
                $stmtUsu = $usuarioDAO->searchConAtivo($idColaborador);
                $alocacaoDAO = new alocacaoDAO($db);
                $alocacao = new Alocacao($db);
                $clienteDAO = new clienteDAO($db);
                $cliente = new Cliente($db);
                $tipoAlocacao = new TipoAlocacao($db);
                $tipAlocDAO = new tipoAlocacaoDAO($db);

                //Preenche a primeira coluna com o nome dos colaboradores
                echo "<tr>";        
                    while($usuario = $stmtUsu->fetch(PDO::FETCH_OBJ)){
                        echo "<th rowspan='2' class='text-center colaboradores'>";
                        echo $usuario->nome;
                        echo "</th>";

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
                            if($stmtClient = $alocacaoDAO->searchMorning($dataAloc, $usuario->idUsuario)){
                                while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                                    $cliente->idCliente = $alocacao->idCliente;
                                    $clienteDAO->readOne($cliente);
                                    $tipoAlocacao->desAloc = $tipAlocDAO->readName($alocacao->idTipAloc);
                                        if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '3')){
                                            echo "<td class=\"text-center folga\">";
                                        }else if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '4')){
                                            echo "<td class=\"text-center feriado\">";
                                        }else if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '5')){
                                            echo "<td class=\"text-center ferias\">";
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
                                        echo " href=\"#\">";
                                            //imprime o cliente e o tipo de alocacao na tabela
                                            if($alocacao->idCliente == '152'){
                                                echo $tipoAlocacao->desAloc;
                                            }else{
                                                echo $cliente->nomeFantasia." - ".$tipoAlocacao->desAloc;
                                            }
                                        echo "</a>";
                                        echo "</td>";
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
                                    echo "<td class='text-center destaque'>&nbsp;</td>";
                                }else{
                                    echo "<td class='text-center'>&nbsp;</td>";
                                }

                            }
                            $f++;
                        }
                echo "</tr>";

                echo "<tr>";
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
                                if($stmtClient = $alocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario)){
                                    while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                                    $cliente->idCliente = $alocacao->idCliente;
                                    $clienteDAO->readOne($cliente);
                                    $tipoAlocacao->desAloc = $tipAlocDAO->readName($alocacao->idTipAloc);
                                        if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '3')){
                                            echo "<td class=\"text-center folga\">";
                                        }else if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '4')){
                                            echo "<td class=\"text-center feriado\">";
                                        }else if(($alocacao->confirmado == 'S') && ($alocacao->idTipAloc == '5')){
                                            echo "<td class=\"text-center ferias\">";
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
                                        echo " href=\"#\">";
                                            //imprime o cliente e o tipo de alocacao na tabela
                                            if($alocacao->idCliente == '152'){
                                                echo $tipoAlocacao->desAloc;
                                            }else{
                                                echo $cliente->nomeFantasia." - ".$tipoAlocacao->desAloc;
                                            }
                                        echo "</td>";
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
                                        echo "<td class='text-center destaque'>&nbsp;</td>";
                                    }else{
                                        echo "<td class='text-center'>&nbsp;</td>";
                                    }
                                }
                                $f++;
                            }
                echo "</tr>";
                    }
            echo "</table>";
    }
?>
<?php
include_once "footer.php";
?>

<script>
$(document).ready(function () {
  $('#dataAlocacao').datepicker({
      format: "dd/mm/yyyy",
      language: "pt-BR"
  });
});
</script>