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

$database = new Database();
$db = $database->getConnection();
?>

<form action='../../controller/controleRelatorios.php?relatorio=consultorAlocacao' method='post'>
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