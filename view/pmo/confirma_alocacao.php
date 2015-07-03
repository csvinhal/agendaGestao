<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}

include_once '../../config/database.class.php';
include_once '../../model/usuarioDAO.class.php';
include_once '../../model/alocacaoDAO.class.php';
include_once '../../model/Alocacao.class.php';
$database = new Database();
$db = $database->getConnection();
?>


<form action='confirma_alocacao.php' method='post'>
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
            echo "<option>Selecione...</option>";
            echo "<option value='T'>Todos</option>";
            while ($row_usu = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row_usu);
                echo "<option value='$idUsuario'>$nome</option>";
            }              
            echo "</select>";
        ?>
        </div>
        <div class="btn-group" role="group">
            <div class="form-group has-feedback">
                <input type="text" id="dataIniAlocacao" name="dataIniAlocacao" class="form-control" placeholder="Data Inicial" autocomplete="off">
                <span class="form-control-feedback glyphicon glyphicon glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </input>
            </div>
        </div>
        <div class="btn-group" role="group">
            <div class="form-group has-feedback">
                <input type="text" id="dataFimAlocacao" name="dataFimAlocacao" class="form-control" placeholder="Data Final" autocomplete="off">
                <span class="form-control-feedback glyphicon glyphicon glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </input>
            </div>
        </div>

        <div class="btn-group" role="group">
            <button type="submit" class="btn btn-primary"><span class='glyphicon glyphicon-search' ></span> Procurar</button>
        </div>
    </div>
    <p>*Data inicial e final n&atilde;o s&atilde;o obrigat&oacute;rias</p>
</form>

<?php
    if(isset($_POST['idColaborador'])){
        $idColaborador = $_POST['idColaborador'];
        echo "<form action='../../controller/controleAlocacao.php?operacao=confirmar' method='post'>";
        echo "<div class=\"row\">";
            echo "<div class=\"panel panel-primary\">";
                echo "<div class=\"panel-heading\">";
                    echo "<h3 class=\"panel-title\">Confirmar aloca&ccedil;&atilde;o</h3>";
                echo "</div>";
                echo "<table class=\"table table-hover\">";
                    echo "<tr>";
                        echo "<th class='text-center'>Selecionar</th>";
                        echo "<th class='text-center'>Data Aloca&ccedil;&atilde;o</th>";
                        echo "<th class='text-center'>Hora Inicio</th>";
                        echo "<th class='text-center'>Hora Fim</th>";
                        echo "<th class='text-center'>Consultor</th>";
                    echo "</tr>";
                $objAlocacao = new Alocacao($db);
                $objAlocDAO = new alocacaoDAO($db);
                /*
                 * Verifica se foi inserido a data inicial ou final no arquivo
                 * Executa o filtro de acordo com as datas inseridas
                 */
                if(!empty($_POST['dataIniAlocacao']) && !empty($_POST['dataFimAlocacao'])){
                    $dataInicial = $_POST['dataIniAlocacao'];
                    $dataFinal = $_POST['dataFimAlocacao'];
                    $dataInicial = $objAlocDAO->date_converter($dataInicial);
                    $dataFinal = $objAlocDAO->date_converter($dataFinal);
                    if($idColaborador == 'T'){
                        $stmt = $objAlocDAO->procurarAlocacaoNConfBetween($dataInicial, $dataFinal);
                    }else{
                        $stmt = $objAlocDAO->procurarAlocacaoNConfirmadoBetween($idColaborador, $dataInicial, $dataFinal);
                    }
                }else if(!empty($_POST['dataIniAlocacao'])){
                    $dataInicial = $_POST['dataIniAlocacao'];
                    $dataInicial = $objAlocDAO->date_converter($dataInicial);
                    if($idColaborador == 'T'){
                        $stmt = $objAlocDAO->procurarAlocacaoNConfDataIni($dataInicial);
                    }else{
                        $stmt = $objAlocDAO->procurarAlocacaoNConfirmadoDataIni($idColaborador, $dataInicial);
                    }
                }else{
                    if($idColaborador == 'T'){
                        $stmt = $objAlocDAO->procurarAlocacaoNConf();
                    }else{
                        $stmt = $objAlocDAO->procurarAlocacaoNConfirmado($idColaborador);
                    }
                }
                if(!empty($stmt)){
                    while ($objAlocacao = $stmt->fetch(PDO::FETCH_OBJ)){
                        $objAlocacao->dataAlocacao = $objAlocDAO->date_converterBR($objAlocacao->dataAlocacao);
                        echo "<tr>";
                            echo "<td class='text-center'><input type=\"checkbox\" name=\"alocacao[]\" ";
                            echo "value=\"$objAlocacao->dataAlocacao|$objAlocacao->horaInicio|$objAlocacao->horaFim|$objAlocacao->idColaborador\"></td>";
                            echo "<td class='text-center'>".$objAlocacao->dataAlocacao."</td>";
                            echo "<td class='text-center'>".$objAlocacao->horaInicio."</td>";
                            echo "<td class='text-center'>".$objAlocacao->horaFim."</td>";
                            echo "<td class='text-center'>";
                            $objUsu = new Usuario($db);
                            $objUsu->idUsuario = $objAlocacao->idColaborador;
                            $usuarioDAO->readOne($objUsu);
                            echo $objUsu->nome;
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td>";
                            echo "<button type=\"submit\" class=\"btn btn-primary\">Confirmar</button>";
                        echo "</td>";
                        echo "<td></td>";
                    echo "</tr>";
                    }
                echo "</table>";
            echo "</div>";
        echo "</div>";
    echo "</form>";
    }
?>

<script>
    $(document).ready(function () {
      $('#dataIniAlocacao').datepicker({
          format: "dd/mm/yyyy",
          language: "pt-BR"
      });
    });
    
    $(document).ready(function () {
      $('#dataFimAlocacao').datepicker({
          format: "dd/mm/yyyy",
          language: "pt-BR"
      });
    });
</script>    
<?php
include_once "footer.php";
?>