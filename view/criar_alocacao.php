<?php
// set page headers
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}
?>

<?php
echo "<div class='right-button-margin'>";
    echo "<a href='view_agendaGeral.php' class='btn btn-default pull-right'>";
    echo "<span class='glyphicon glyphicon-list-alt'></span> Visualizar Agenda</a>";
echo "</div>";

include_once '../config/database.class.php';

$database = new Database();
$db = $database->getConnection();
?> 

<!-- HTML form para criar alocacao -->
<form action='../controller/controleAlocacao.php?operacao=salvar' method='post'>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Cadastrar aloca&ccedil;&atilde;o</h3>
            </div>
 
            <table class='table table-hover'>

                <tr>
                    <td>Colaborador:*</td>
                    <td>
                        <?php
                        include_once '../model/usuarioDAO.class.php';                
                        $usuarioDAO = new usuarioDAO($db);
                        $stmt = $usuarioDAO->searchCol();

                        //Acrescenta select drop-down
                        echo "<select class='form-control' name='idColaborador' required>";

                            echo "<option>Selecione...</option>";
                            while ($row_usu = $stmt->fetch(PDO::FETCH_ASSOC)){
                                extract($row_usu);
                                echo "<option value='$idUsuario'>$nome</option>";
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
                                echo "<option value='$idCliente'>$razaosocial</option>";
                            }

                        ?>              
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Descrição:*</td>
                    <td>
                        <textarea type="text" name="desAlocacao" class="form-control" autocomplete="off" required></textarea>
                    </td>

                </tr>
                <tr>
                    <td>Data Alocação:*</td>
                    <td>
                        <div class="form-group has-feedback">
                            <input type="text" id="dataAlocacao" name="dataAlocacao" class="form-control" autocomplete="off" required>
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
                        $alocDAO = new tipoAlocacaoDAO($db);
                        $stmt = $alocDAO->read();

                        echo "<select name=\"alocacao\" class=\"form-control\" required>";
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
                    <td>Período:*</td>
                    <td>
                        <select name="periodo" class="form-control" required>
                            <option value="" selected>Selecione...</option>
                            <option value="M">Matutino</option>
                            <option value="V">Vespertino</option>
                            <option value="I">Integral</option>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td>Confirmado?:*</td>
                    <td>
                        <select name="confirmado" class="form-control" required>
                            <option value="" selected>Selecione...</option>
                            <option value="S">Sim</option>
                            <option value="N">Não</option>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
      $('#dataAlocacao').datepicker({
          format: "dd/mm/yyyy",
          language: "pt-BR"
      });
    });
</script>       
			
<?php
include_once "footer.php";
?>