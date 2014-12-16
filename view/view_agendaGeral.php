<?php
$page_title = "Agenda";
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    session_unset();
}
?>

<?php

    include_once '../model/agendaDAO.class.php';
    include_once '../model/usuarioDAO.class.php';
    include_once '../config/database.class.php';
    include_once '../model/alocacaoDAO.class.php';

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
    $mes_ant = 12;
    $year_ant = $year - 1;
}else{
    $mes_ant = $month - 1;
    $year_ant = $year;
}
if($month==12){//proximo mês se dezembro tem que mudar
    $mes_prox = 1;
    $year_prox = $year + 1;
}else{
    $mes_prox = $month + 1;
    $year_prox = $year;
}


$agendaDAO = new agendaDAO($db);
$agendaDAO->getActualDay();
$f = $agendaDAO->getFirstDay($month, $year);
$firthday = $agendaDAO->getFirstDay($month, $year);
$n = $agendaDAO->getNumberDay($month, $year);

    //Cria a tabela
    echo "<table class='table table-hover table-responsive table-bordered'>";
    
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
            echo "<th rowspan='2'>";
            echo "Colaborador";
            echo "</th>";
                    //Preenche a primeira rowspan com os dias do mês e o nome do mês
                    while($f <= $n){
                        echo "<td>".$f."-".$agendaDAO->getNameMon($month);
                        echo "</td> ";
                        $f++;
                    }
        echo "</tr>";
        //Preenche a segunda rowspan com os dias por extenso
        echo "<tr>";
            while($firthday <= $n){
                $dayExtensive = $agendaDAO->getDayExtensive($month, $firthday, $year);
                echo "<td>".$dayExtensive;
                echo "</td> ";
                $firthday++;
            }
        echo "</tr>";

    //Instancia os objetos
    $usuarioDAO = new usuarioDAO($db);
    $stmtUsu = $usuarioDAO->searchCol();
    $alocacaoDAO = new alocacaoDAO($db);
    $alocacao = new Alocacao($db);
    
    //Preenche a primeira coluna com o nome dos colaboradores
    echo "<tr>";
        while($usuario = $stmtUsu->fetch(PDO::FETCH_OBJ)){
            echo "<th rowspan='2'>";
            echo $usuario->nome;
            echo "</th>";
            
            //Preenche a primeira rowspan com as alocações da manhã
            $f = 1;
            while($f <= $n){
                $dataAloc = $year.'-'.$month.'-'.$f;
                if($stmtClient = $alocacaoDAO->searchMorning($dataAloc, $usuario->idUsuario)){
                    while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                        echo "<td>".$alocacao->idCliente;
                        echo "</td>";
                    }
                }else{
                    echo "<td>SemCompromissos</td>";
                }
                $f++;
            }  
    echo "</tr>";
    echo "<tr>";
            //Preenche a primeira rowspan com as alocações da manhã
                $f = 1;
                while($f <= $n){
                    $dataAloc = $year.'-'.$month.'-'.$f;
                    if($stmtClient = $alocacaoDAO->searchAfternoon($dataAloc, $usuario->idUsuario)){
                        while($alocacao = $stmtClient->fetch(PDO::FETCH_OBJ)){
                            echo "<td>".$alocacao->idCliente;
                            echo "</td>";
                        }
                    }else{
                        echo "<td>SemCompromissos</td>";
                    }
                    $f++;
                }
    echo "</tr>";
        }
    echo "</table>";

?>

<?php
include_once "footer.php";
?>