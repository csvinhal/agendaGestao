<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
<script src="../../js/bootstrap-datepicker.js"></script>
<script src="../../js/locales/bootstrap-datepicker.pt-BR.js"></script>

<!-- Modal -->
<?php
include_once '../../model/clienteDAO.class.php';
include_once '../../config/database.class.php';
include_once '../../model/usuarioDAO.class.php';
include_once '../../model/tipoAlocacaoDAO.class.php';
include_once '../../model/alocacaoDAO.class.php';

$database = new Database();
$db = $database->getConnection();
    $semana  = filter_input(INPUT_GET,'semana', FILTER_SANITIZE_STRING);
    $objAlocacao = new Alocacao($db);
    $objAlocacao->dataAlocacao = filter_input(INPUT_GET,'data', FILTER_SANITIZE_STRING);
    $objAlocacao->horaInicio = substr(filter_input(INPUT_GET,'horaIni', FILTER_SANITIZE_STRING), 0, -3);
    $objAlocacao->horaFim = substr(filter_input(INPUT_GET,'horaFim', FILTER_SANITIZE_STRING), 0, -3);
    $objAlocacao->idColaborador = filter_input(INPUT_GET, 'idColaborador', FILTER_SANITIZE_NUMBER_INT);
    
    $objAlocDAO = new alocacaoDAO($db);
    $objAlocDAO->buscaAlocacao($objAlocacao);
  
    
    //Instacia o cliente
    $cliente = new Cliente($db);
    
    //Instacia o usuario
    $colaborador = new Usuario($db);
    
    //Instacia o tipo alocacao
    $objTipoAlocacao = new TipoAlocacao($db);

    //Seta o parametro
    $cliente->idCliente = $objAlocacao->idCliente;
    $colaborador->idUsuario = $objAlocacao->idColaborador;
    $objTipoAlocacao->idTipAloc = $objAlocacao->idTipAloc;
    
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     <h4 class="modal-title">Detalhes Aloca&ccedil;&atilde;o</h4>
</div>

<!-- /modal-header -->
<div class="modal-body">
    <form action='../../controller/controleAlocacao.php?operacao=update&idCol=<?php echo $objAlocacao->idColaborador?>&data=<?php echo $objAlocacao->dataAlocacao?>&horaIni=<?php echo $objAlocacao->horaInicio?>&horaFim=<?php echo $objAlocacao->horaFim?>' method='post'>
            <table class='table table-hover'>
                <tr>
                    <td>Colaborador:*</td>
                    <td>
                        <?php             
                        $usuarioDAO = new usuarioDAO($db);
                        $usuarioDAO->readOne($colaborador);

                        //Acrescenta select drop-down
                        echo "<select class='form-control' id='idColaborador' name='idColaborador' disabled>";
                        echo "<option value='$colaborador->idUsuario' selected>$colaborador->nome</option>";
                        ?>              
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Cliente:*</td>
                    <td>
                        <?php
                        $clienteDAO = new clienteDAO($db);
                        $clienteDAO->readOne($cliente);

                        //Acrescenta select drop-down
                        echo "<select class='form-control' name='idCliente' disabled>";
                            echo "<option value='$cliente->idCliente' selected>$cliente->nomeFantasia</option>";
                        ?>              
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Descri&ccedil;&atilde;o:*</td>
                    <td>
                        <textarea type="text" name="desAlocacao" class="form-control" required disabled><?php echo $objAlocacao->desAlocacao ?></textarea>
                    </td>

                </tr>
                <tr>
                    <td>Data Aloca&ccedil;&atilde;o:*</td>
                    <td>
                        <div class="form-group has-feedback">
                            <input type="text" id="dataAlocacao" name="dataAlocacao" class="form-control" value="<?php echo $objAlocacao->dataAlocacao ?>" disabled>
                            <span class="form-control-feedback glyphicon glyphicon glyphicon glyphicon-calendar" aria-hidden="true"></span>
                            </input>
                        </div>
                    </td>

                </tr>
                
                <tr>
                    <td>Tipo de Aloca&ccedil;&atilde;o:*</td>
                    <td>
                        <?php         
                        $objTipoAlocDAO = new tipoAlocacaoDAO($db);
                        $objTipoAlocacao->desAloc = $objTipoAlocDAO->readName($objAlocacao->idTipAloc);

                        //Acrescenta select drop-down
                        echo "<select class='form-control' id='idTipAloc' name='idTipAloc' disabled>";
                            echo "<option value='$objTipoAlocacao->idTipAloc' selected>$objTipoAlocacao->desAloc</option>";
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
                        echo '<select name="periodo" id="periodo" class="form-control" disabled>';
                            echo '<option value="">Selecione...</option>';
                            echo '<option value="M" selected>Matutino</option>';
                            echo '<option value="V">Vespertino</option>';
                        echo '</select>';
                    }else if(($objAlocacao->horaInicio == '14:00' && $objAlocacao->horaFim == '18:00')){
                        $periodo = 'V';
                        echo '<select name="periodo" id="periodo" class="form-control" disabled>';
                            echo '<option value="">Selecione...</option>';
                            echo '<option value="M">Matutino</option>';
                            echo '<option value="V" selected>Vespertino</option>';
                        echo '</select>';
                    }else{
                        echo '<div id="periodos" style="display: block" >';
                            echo '<div class="col-md-3">';
                                echo '<input type="text" id="horaIni" name="horaIni" value="'.$objAlocacao->horaInicio.'" class="form-control" autocomplete="off">';
                            echo '</div>';
                            echo '<div class="col-md-1">';
                                echo '<p>&agrave;s</p>';
                            echo '</div>';
                            echo '<div class="col-md-3">';
                                echo '<input type="text" id="horaFim" name="horaFim" value="'.$objAlocacao->horaFim.'" class="form-control" autocomplete="off">';                     
                            echo '</div>';
                        echo '</div>';
                    }
                ?>
                    </td>

                </tr>
                <tr>
                    <td>Confirmado?*</td>
                    <td>
                        <select name="confirmado" class="form-control" disabled>
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
            </table>
    </form>
</div>			
<!-- /modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
</div>
<!-- /modal-footer -->
<script>    
$('.modal').on('hidden.bs.modal', function(){
    $(this).removeData('bs.modal');
});

$(document).ready(function () {
  $('#dataAlocacao').datepicker({
      format: "yyyy/mm/dd",
      language: "pt-BR"
  });
});
</script>

