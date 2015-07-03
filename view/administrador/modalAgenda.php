<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
<script src="../../js/bootstrap-datepicker.js"></script>
<script src="../../js/jquery.maskedinput.js"></script>
<script src="../../js/locales/bootstrap-datepicker.pt-BR.js"></script>

<!-- Modal -->
<?php
include_once '../../config/functions.php';
sec_session_start();
include_once '../../model/Cliente.class.php';
include_once '../../config/database.class.php';
include_once '../../model/Usuario.class.php'; 
include_once '../../model/TipoAlocacao.class.php';
include_once '../../model/alocacaoDAO.class.php';
include_once '../../model/Alocacao.class.php';
/**
 * Mostra erros
 *
 ini_set('display_errors',1);
 ini_set('display_startup_erros',1);
 error_reporting(E_ALL);
 */
$database = new Database();
$db = $database->getConnection();
    $semana  = filter_input(INPUT_GET,'semana', FILTER_SANITIZE_STRING);
    $objAlocacao = new Alocacao($db);
    $objAlocacao->dataAlocacao = filter_input(INPUT_GET,'data', FILTER_SANITIZE_STRING);
    $objAlocacao->horaInicio = substr(filter_input(INPUT_GET,'horaIni', FILTER_SANITIZE_STRING), 0, -3);
    $objAlocacao->horaFim = substr(filter_input(INPUT_GET,'horaFim', FILTER_SANITIZE_STRING), 0, -3);
    $objAlocacao->idColaborador = filter_input(INPUT_GET, 'idColaborador', FILTER_SANITIZE_NUMBER_INT);
    
    $data = $objAlocacao->dataAlocacao;
    $horaIni = $objAlocacao->horaInicio;
    $horaFim = $objAlocacao->horaFim;
    $idColaborador = $objAlocacao->idColaborador;
    
    $objAlocDAO = new alocacaoDAO($db);
    $objAlocDAO->buscaAlocacao($objAlocacao);
    $retorno = $objAlocDAO->procurarBloqueio($objAlocacao->dataAlocacao, $objAlocacao->horaInicio, $objAlocacao->horaFim, $objAlocacao->idColaborador);
    if($retorno != '0'){
        $habilita = "disabled";
    }else{
        $habilita = "required";
        $objAlocDAO->bloquearEdicao($objAlocacao->dataAlocacao, $objAlocacao->horaInicio, $objAlocacao->horaFim, $objAlocacao->idColaborador);
    }
?>   
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Editar Aloca&ccedil;&atilde;o</h4>
    </div>

    <!--modal-header -->
    <div class="modal-body">
    <form action="../../controller/controleAlocacao.php?operacao=update&idCol=<?php echo $idColaborador?>&data=<?php echo $data?>&horaIni=<?php echo $horaIni?>&horaFim=<?php echo $horaFim?>" method="post">
        <table class="table table-hover">
            <tr>
                <td>Colaborador:*</td>
                <td>
                    <?php
                        include_once '../../model/usuarioDAO.class.php';                
                        $usuarioDAO = new usuarioDAO($db);
                        $stmt = $usuarioDAO->searchCol();
                    ?>
                    <select class="form-control" id="idColaborador" name="idColaborador" <?php echo $habilita ?>>
                    <option>Selecione...</option>
                    <?php
                    while ($row_usu = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row_usu);
                        if($idUsuario == $objAlocacao->idColaborador){
                            echo "<option value='$idUsuario' selected>$nome</option>";
                        }else{
                            echo "<option value='$idUsuario'>$nome</option>";
                        }
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Cliente:*</td>
                <td>
                    <?php
                        include_once '../../model/clienteDAO.class.php';
                        $clienteDAO = new clienteDAO($db);
                        $stmt = $clienteDAO->search();
                    ?>                    
                    <select class="form-control" id="idCliente" name="idCliente" <?php echo $habilita ?>>
                    <option>Selecione...</option>
                    <?php
                    while ($row_client = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row_client); 
                        if($objAlocacao->idCliente == $idCliente){
                            echo "<option value='$idCliente' selected>$nomefantasia</option>";
                        }else{
                            echo "<option value='$idCliente'>$nomefantasia</option>";
                        }
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Descri&ccedil;&atilde;o:*</td>
                <td>
                    <textarea type="text" name="desAlocacao" id="desAlocacao" class="form-control" <?php echo $habilita ?>><?php echo $objAlocacao->desAlocacao ?></textarea>
                </td>
            </tr>
            <tr>
            <?php 
            $objAlocacao->dataAlocacao = $objAlocDAO->date_converterBR($objAlocacao->dataAlocacao);
            ?>
                <td>Data Aloca&ccedil;&atilde;o:*</td>
                <td>
                    <div class="form-group has-feedback">
                        <input type="text" id="dataAlocacao" name="dataAlocacao" class="form-control" value="<?php echo $objAlocacao->dataAlocacao ?>" <?php echo $habilita ?>>
                            <span class="form-control-feedback glyphicon glyphicon glyphicon glyphicon-calendar" aria-hidden="true"></span>
                        </input>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Tipo de Aloca&ccedil;&atilde;o:*</td>
                <td>
                    <?php
                        include_once '../../model/tipoAlocacaoDAO.class.php';                
                        $tipAlocDAO = new tipoAlocacaoDAO($db);
                        $stmt = $tipAlocDAO->read();
                    ?>

                    <select class="form-control" id="idTipAloc" name="idTipAloc" <?php echo $habilita ?>>
                    <option>Selecione...</option>;
                    <?php
                        while ($row_taloc = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row_taloc);
                            if($idTipAloc == $objAlocacao->idTipAloc){
                                echo "<option value='$idTipAloc' selected>$desAloc</option>";
                            }else{
                                echo "<option value='$idTipAloc'>$desAloc</option>";
                            }
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Per&iacute;odo:*</td>
                <td>
                <?php
                    if(($objAlocacao->horaInicio == '08:00' && $objAlocacao->horaFim == '12:00')){
                        $periodo = 'M';
                        echo '<div id="periodos" style="display: block" >';
                            echo '<div class="col-md-10">';
                                echo '<select name="periodo" id="periodo" class="form-control" '.$habilita.'>';
                                    echo '<option value="">Selecione...</option>';
                                    echo '<option value="M" selected>Matutino</option>';
                                    echo '<option value="V">Vespertino</option>';
                                echo '</select>';
                            echo '</div>';
                            echo '<div class="col-md-2">';
                                echo '<button type="button" onclick="mostrarOcultar()" class="btn btn-default" '.$habilita.'><span class="glyphicon glyphicon glyphicon-refresh"></span></button>';                   
                            echo '</div>';
                        echo '</div>';
                    }else if(($objAlocacao->horaInicio == '14:00' && $objAlocacao->horaFim == '18:00')){
                        $periodo = 'V';
                        echo '<div id="periodos" style="display: block" >';
                            echo '<div class="col-md-10">';
                                echo '<select name="periodo" id="periodo" class="form-control"'.$habilita.'>';
                                    echo '<option value="">Selecione...</option>';
                                    echo '<option value="M">Matutino</option>';
                                    echo '<option value="V" selected>Vespertino</option>';
                                echo '</select>';
                            echo '</div>';
                            echo '<div class="col-md-2">';
                                echo '<button type="button" onclick="mostrarOcultar()" class="btn btn-default" '.$habilita.'><span class="glyphicon glyphicon glyphicon-refresh"></span></button>';                   
                            echo '</div>';
                        echo '</div>';
                    }else{
                        echo '<div id="periodos" style="display: block">';
                            echo '<div class="col-md-3">';
                                echo '<input type="text" id="horaIni" name="horaIni" value="'.$objAlocacao->horaInicio.'" class="form-control" autocomplete="off"'.$habilita.'>';
                            echo '</div>';
                            echo '<div class="col-md-1">';
                                echo '<p>&agrave;s</p>';
                            echo '</div>';
                            echo '<div class="col-md-3">';
                                echo '<input type="text" id="horaFim" name="horaFim" value="'.$objAlocacao->horaFim.'" class="form-control" autocomplete="off"'.$habilita.'>';                     
                            echo '</div>';
                        echo '</div>';
                    }
                ?>
                    <div id="horas"  style="display: none" class="row">
                        <div class="col-md-3">
                            <input type="text" id="horaIni" name="horaIni" value="<?php echo $objAlocacao->horaInicio ?>" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-md-1">
                            <p>&agrave;s</p>
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="horaFim" name="horaFim" value="<?php echo $objAlocacao->horaFim ?>" class="form-control" autocomplete="off">                     
                        </div>
                        <div class="col-md-3">
                            <button type="button" onclick="mostrarOcultar()" class="btn btn-default"><span class='glyphicon glyphicon glyphicon-refresh'></span></button>                     
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Confirmado?*</td>
                <td>
                    <select name="confirmado" id="confirmado" class="form-control" <?php echo $habilita ?>>
                        <?php
                            if($objAlocacao->confirmado === 'S'){
                                echo "<option value=\"S\" selected>Sim</option>";
                            }else{
                                echo "<option value=\"S\">Sim</option>";
                            }
                            if($objAlocacao->confirmado === 'N'){
                                echo "<option value=\"N\" selected>N&atilde;o</option>";
                            }else{
                                echo "<option value=\"N\">N&atilde;o</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Bloquear aloca&ccedil;&atilde;o?
                </td>
                <td>
                    <?php
                        if($objAlocacao->bloqueado == 'S'){
                            echo '<input type="checkbox" name="bloqueio" id="bloqueio" value="1" checked/>';
                        }else if($retorno != '0'){
                            echo '<input type="checkbox" name="bloqueio" id="bloqueio" value="1" '.$habilita.'/>';
                        }else{
                            echo '<input type="checkbox" name="bloqueio" id="bloqueio" value="1"/>';
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td><input type="hidden" name="semana" value="<?php if(isset($semana)){echo $semana;}?>" />
                </td>
                <td>
                    <a delete-data="<?php echo $objAlocacao->dataAlocacao ?>" onclick="deletarAlocacao()" delete-col="<?php echo $objAlocacao->idColaborador ?>" delete-hIni="<?php echo $objAlocacao->horaInicio ?>" delete-hFim="<?php echo $objAlocacao->horaFim ?>" class="btn btn-default delete-object" <?php echo $habilita ?>>
                    <span class="glyphicon glyphicon-trash" ></span> Deletar</a>
                    <button type="button" class="btn btn-default liberarAloc" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" <?php echo $habilita ?>>Atualizar</button>
                </td>
            </tr>
        </table>
    </form>
    </div>

<script>
$('.modal').on('hidden.bs.modal', function(){
    var ret = '<?php echo $retorno ?>';
    var data = '<?php echo $data ?>';
    var idCol = '<?php echo $idColaborador ?>';
    var horaIni = '<?php echo $horaIni ?>';
    var horaFim = '<?php echo $horaFim ?>';
    if(ret == 0){
        $.post('../../controller/controleAlocacao.php', {
            operacao: 'liberar',
            object_data: data,
            object_col: idCol,
            object_ini: horaIni,
            object_fim: horaFim
        });
        $(this).removeData('bs.modal');
        
        setTimeout(function wait(){
        location.reload();
        }, 500);
        
    }else{
       $(this).removeData('bs.modal');
    
        setTimeout(function wait(){
        location.reload();
        }, 500); 
    }
});


$(document).ready(function () {
  $('#dataAlocacao').datepicker({
      format: "dd/mm/yyyy",
      language: "pt-BR"
  });
});

$('.modal').on('shown.bs.modal', function(){
    var aChk = document.getElementById("bloqueio");
    if(aChk.checked === true){
        document.getElementById('idColaborador').disabled = true;
        document.getElementById('idCliente').disabled = true;
        document.getElementById('desAlocacao').disabled = true;
        document.getElementById('dataAlocacao').disabled = true;
        document.getElementById('idTipAloc').disabled = true;
        document.getElementById('horaIni').disabled = true;
        document.getElementById('horaFim').disabled = true;
        document.getElementById('periodo').disabled = true;
        document.getElementById('confirmado').disabled = true;
    }
});

document.getElementById('bloqueio').onchange = function() {
    document.getElementById('idColaborador').disabled = this.checked;
    document.getElementById('idCliente').disabled = this.checked;
    document.getElementById('desAlocacao').disabled = this.checked;
    document.getElementById('dataAlocacao').disabled = this.checked;
    document.getElementById('idTipAloc').disabled = this.checked;
    document.getElementById('horaIni').disabled = this.checked;
    document.getElementById('horaFim').disabled = this.checked;
    document.getElementById('periodo').disabled = this.checked;
    document.getElementById('confirmado').disabled = this.checked;
};

jQuery("#horaIni").mask("99:99");
jQuery("#horaFim").mask("99:99");
</script>