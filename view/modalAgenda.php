<!-- Modal -->
<?php
include_once '../model/Cliente.class.php';
include_once '../config/database.class.php';
include_once '../model/Usuario.class.php'; 
include_once '../model/TipoAlocacao.class.php';

$database = new Database();
$db = $database->getConnection();

    if(isset($_GET['idCliente'])){ 
        $id  = filter_input(INPUT_GET,'idCliente', FILTER_SANITIZE_NUMBER_INT);
    }else{
        die('ERROR: Faltando o id do cliente.');
    }
    
    if(isset($_GET['idColaborador'])){ 
        $idColaborador = filter_input(INPUT_GET, 'idColaborador', FILTER_SANITIZE_NUMBER_INT);
    }else{
        die('ERROR: Faltando o id do Colaborador.');
    }
    
    if(isset($_GET['descricao'])){ 
        $str = filter_input(INPUT_GET,'descricao', FILTER_SANITIZE_STRING);
        $desAlocacao = urldecode($str);
    }else{
        die('ERROR: Faltando a descricao da Alocação.');
    }
    
    if(isset($_GET['data']) && isset($_GET['periodo']) && (isset($_GET['confirma'])) && isset($_GET['tipAloc'])){ 
        $data  = filter_input(INPUT_GET,'data', FILTER_SANITIZE_STRING);
        $periodo  = filter_input(INPUT_GET,'periodo', FILTER_SANITIZE_STRING);
        $confirma = filter_input(INPUT_GET,'confirma', FILTER_SANITIZE_STRING);
        $tipoAloc = filter_input(INPUT_GET,'tipAloc', FILTER_SANITIZE_STRING);
    }else{
        die('ERROR: Missing ID.');
    }
    
    if($periodo === 'M'){
        $horaIni = '08:00';
        $horaFim = '12:00';
    }else if($periodo === 'V'){
        $horaIni = '14:00';
        $horaFim = '18:00';
    }
  
    
    //Instacia o cliente
    $cliente = new Cliente($db);
    
    //Instacia o usuario
    $colaborador = new Usuario($db);
    
    //Instacia o tipo alocacao
    $tipAloc = new TipoAlocacao($db);

    //Seta o parametro
    $cliente->idCliente = $id;
    $colaborador->idUsuario = $idColaborador;
    $tipAloc->idTipAloc = $tipoAloc;
    
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     <h4 class="modal-title">Editar Alocação</h4>
</div>

<!-- /modal-header -->
<div class="modal-body">
    <form action='../controller/controleAlocacao.php?operacao=update&idCol=<?php echo $idColaborador?>&data=<?php echo $data?>&horaIni=<?php echo $horaIni?>&horaFim=<?php echo $horaFim?>' method='post'>
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Colaborador:*</td>
                    <td>
                        <?php
                        include_once '../model/usuarioDAO.class.php';                
                        $usuarioDAO = new usuarioDAO($db);
                        $stmt = $usuarioDAO->searchCol();

                        //Acrescenta select drop-down
                        echo "<select class='form-control' id='idColaborador' name='idColaborador' required>";
                        echo "<option>Selecione...</option>";
                        while ($row_usu = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row_usu);
                            if($idUsuario == $colaborador->idUsuario){
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
                        include_once '../model/clienteDAO.class.php';
                        $clienteDAO = new clienteDAO($db);
                        $stmt = $clienteDAO->search();

                        //Acrescenta select drop-down
                        echo "<select class='form-control' name='idCliente' required>";

                            echo "<option>Selecione...</option>";
                            while ($row_client = $stmt->fetch(PDO::FETCH_ASSOC)){
                                extract($row_client); 

                            if($idCliente == $cliente->idCliente){
                                echo "<option value='$idCliente' selected>$razaosocial</option>";
                            }else{
                                echo "<option value='$idCliente'>$razaosocial</option>";
                            } 
                        }
                        ?>              
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Descrição:*</td>
                    <td>
                        <textarea type="text" name="desAlocacao" class="form-control" required><?php echo $desAlocacao ?></textarea>
                    </td>

                </tr>
                <tr>
                    <td>Data Alocação:*</td>
                    <td>
                        <div class="form-group has-feedback">
                            <input type="text" id="dataAlocacao" name="dataAlocacao" class="form-control" value="<?php echo $data ?>" required>
                            <span class="form-control-feedback glyphicon glyphicon glyphicon glyphicon-calendar" aria-hidden="true"></span>
                            </input>
                        </div>
                    </td>

                </tr>
                
                <tr>
                    <td>Tipo de Alocação:*</td>
                    <td>
                        <?php
                        include_once '../model/tipoAlocacaoDAO.class.php';                
                        $tipAlocDAO = new tipoAlocacaoDAO($db);
                        $stmt = $tipAlocDAO->read();

                        //Acrescenta select drop-down
                        echo "<select class='form-control' id='idTipAloc' name='idTipAloc' required>";
                        echo "<option>Selecione...</option>";
                        while ($row_taloc = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row_taloc);
                            if($idTipAloc == $tipAloc->idTipAloc){
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
                    <td>Período:*</td>
                    <td>
                        <select name="periodo" id="periodo" class="form-control" required>
                            <?php 
                                if ($periodo === 'M'){
                                    echo "<option value=\"M\" selected>Matutino</option>";
                                }else{
                                    echo "<option value=\"M\">Matutino</option>";
                                }

                                if ($periodo === 'V'){
                                    echo "<option value=\"V\" selected>Vespertino</option>";
                                }else{
                                    echo "<option value=\"V\">Vespertino</option>";
                                }
                            ?>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td>Confirmado?*</td>
                    <td>
                        <select name="confirmado" class="form-control" required>
                            <?php
                                if($confirma === 'S'){
                                    echo "<option value=\"S\" selected>Sim</option>";
                                }else{
                                    echo "<option value=\"S\">Sim</option>";
                                }
                                if($confirma === 'N'){
                                    echo "<option value=\"N\" selected>Não</option>";
                                }else{
                                    echo "<option value=\"N\">Não</option>";
                                }
                            ?>
                        </select>
                    </td>

                </tr> 
                <tr>
                    <td></td>
                    <td>
                        <?php
                        echo "<a delete-data='{$data}' delete-col='{$idColaborador}' delete-hIni='{$horaIni}' delete-hFim='{$horaFim}' class='btn btn-default delete-object'>"; 
                        echo "<span class='glyphicon glyphicon-trash' ></span> Deletar</a>";
                        ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
$(document).on('click', '.delete-object', function(){
 
    var data = $(this).attr('delete-data');
    var idCol = $(this).attr('delete-col');
    var horaIni = $(this).attr('delete-hIni');
    var horaFim = $(this).attr('delete-hFim');
    var q = confirm("Tem certeza que deseja excluir essa alocação??");
 
    if (q == true){
 
        $.post('../controller/controleAlocacao.php', {
            operacao: 'deletar',
            object_data: data,
            object_col: idCol,
            object_ini: horaIni,
            object_fim: horaFim
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('Não foi possível excluir a alocação!');
        });
 
    }
 
    return false;
});

$('.modal').on('hidden.bs.modal', function(){
    $(this).removeData('bs.modal');
});
$(document).ready(function () {
  $('#dataAlocacao').datepicker({
      format: "dd/mm/yyyy",
      language: "pt-BR"
  });
});
</script>

