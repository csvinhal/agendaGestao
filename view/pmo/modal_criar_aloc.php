<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
<script src="../../js/bootstrap-datepicker.js"></script>
<script src="../../js/jquery.maskedinput.js"></script>
<script src="../../js/functions.js"></script>
<script src="../../js/locales/bootstrap-datepicker.pt-BR.js"></script>

<!-- Modal -->
<?php
include_once '../../config/database.class.php';

$database = new Database();
$db = $database->getConnection();

    if(isset($_GET['data'])){ 
        $data  = filter_input(INPUT_GET,'data', FILTER_SANITIZE_STRING);
    }else{
        die('ERROR: Faltando a data da aloca&ccedil;&atilde;o.');
    }
    
    if(isset($_GET['semana'])){ 
        $semana  = filter_input(INPUT_GET,'semana', FILTER_SANITIZE_STRING);
    }
    
    if(isset($_GET['idColaborador'])){ 
        $id  = filter_input(INPUT_GET,'idColaborador', FILTER_SANITIZE_NUMBER_INT);
    }
?> 

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     <h4 class="modal-title">Incluir Aloca&ccedil;&atilde;o</h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
<!-- HTML form para criar alocacao -->
<form action='../../controller/controleAlocacao.php?operacao=salvar' method='post'>
 
    <table class='table table-hover'>

        <tr>
            <td>Colaborador:*</td>
            <td>
                <?php
                include_once '../../model/usuarioDAO.class.php';                
                $usuarioDAO = new usuarioDAO($db);
                $stmt = $usuarioDAO->searchCol();

                //Acrescenta select drop-down
                echo "<select class='form-control' name='idColaborador' id='idColaborador' required>";

                    echo "<option>Selecione...</option>";
                    while ($row_usu = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row_usu);
                        if($idUsuario == $id){
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

                //Acrescenta select drop-down
                echo "<select class='form-control' name='idCliente' id='idCliente' required>";

                    echo "<option>Selecione...</option>";
                    while ($row_client = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row_client);
                        echo "<option value='$idCliente'>$nomefantasia</option>";
                    }

                ?>              
                </select>
            </td>
        </tr>
        <tr>
            <td>Descri&ccedil;&atilde;o:*</td>
            <td>
                <textarea type="text" name="desAlocacao" id="desAlocacao" class="form-control" autocomplete="off" required></textarea>
            </td>

        </tr>
        <tr>
            <td>Data Inicial da Aloca&ccedil;&atilde;o:*</td>
            <td>
                <div class="form-group has-feedback">
                    <input type="text" id="dataIniAlocacao" name="dataIniAlocacao" class="form-control" autocomplete="off" 
                           placeholder="dd/mm/yyyy" required 
                                <?php 
                                    if(!empty($data))
                                        {
                                        include_once '../../model/alocacaoDAO.class.php';
                                        $objalocacaDAO = new AlocacaoDAO($db);
                                        $data = $objalocacaDAO->date_converterBR($data);
                                            echo "value='$data'";
                                            
                                        } 
                                ?>>
                    <span class="form-control-feedback glyphicon glyphicon glyphicon glyphicon-calendar" aria-hidden="true"></span>
                    </input>
                </div>
            </td>

        </tr>

        <tr>
            <td>Data Final da Aloca&ccedil;&atilde;o:*</td>
            <td>
                <div class="form-group has-feedback">
                    <input type="text" id="dataFimAlocacao" name="dataFimAlocacao" class="form-control" autocomplete="off" placeholder="dd/mm/yyyy">
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
                $alocDAO = new tipoAlocacaoDAO($db);
                $stmt = $alocDAO->read();

                echo "<select name=\"alocacao\" id=\"alocacao\" class=\"form-control\" required>";
                echo "<option>Selecione...</option>";
                while ($row_tipAloc = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row_tipAloc);
                    echo "<option value=\"$idTipAloc\">$desAloc</option>";
                }
                echo "</select>";
                ?>
            </td>

        </tr>        
        <tr>
            <td>Per&iacute;odo:*</td>
            <td>
                <div id="periodos" style="display: block" >
                    <div class="col-md-10">
                        <select name="periodo" id="periodo" class="form-control">
                            <option value="" selected>Selecione...</option>
                            <option value="M">Matutino</option>
                            <option value="V">Vespertino</option>
                            <option value="I">Integral</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" onclick="mostrarOcultar()" class="btn btn-default"><span class='glyphicon glyphicon glyphicon-refresh'></span></button>                     
                    </div>
                </div>
                <div id="horas"  style="display: none" class="row">
                    <div class="col-md-3">
                        <input type="text" id="horaIni" name="horaIni" class="form-control" autocomplete="off">
                    </div>
                    <div class="col-md-1">
                        <p>&agrave;s</p>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="horaFim" name="horaFim" class="form-control" autocomplete="off">                     
                    </div>
                    <div class="col-md-3">
                        <button type="button" onclick="mostrarOcultar()" class="btn btn-default"><span class='glyphicon glyphicon glyphicon-refresh'></span></button>                     
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>Confirmado?:*</td>
            <td>
                <select name="confirmado" id="confirmado" class="form-control" required>
                    <option value="" selected>Selecione...</option>
                    <option value="S">Sim</option>
                    <option value="N">N&atilde;o</option>
                </select>
            </td>

        </tr>
        <tr>
            <td>
                Bloquear aloca&ccedil;&atilde;o?
            </td>
            <td>
                <input type="checkbox" name="bloqueio" id="bloqueio" value="1"/> 
            </td>
        </tr>
        <tr>
            <td>
                <input type="hidden" name="semana" value="<?php if(isset($semana)){echo $semana;} ?>"/> 
            </td>
            <td>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </td>
        </tr>
    </table>
</form>
</div>			
<!-- /modal-body 
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save changes</button>
</div>	-->
<!-- /modal-footer -->
<script>    
$('.modal').on('hidden.bs.modal', function(){
    $(this).removeData('bs.modal');
});

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

document.getElementById('bloqueio').onchange = function() {
    document.getElementById('idCliente').disabled = this.checked;
    document.getElementById('desAlocacao').disabled = this.checked;
    document.getElementById('alocacao').disabled = this.checked;
    document.getElementById('periodo').disabled = this.checked;
    document.getElementById('confirmado').disabled = this.checked;
};

jQuery("#horaIni").mask("99:99");
jQuery("#horaFim").mask("99:99");
</script>

