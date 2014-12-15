<?php
// set page headers
$page_title = "Cadastrar Alocação";
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    session_unset();
}
?>

<?php
echo "<div class='right-button-margin'>";
    echo "<a href='view_evento.php' class='btn btn-default pull-right'>";
    echo "<span class='glyphicon glyphicon-list-alt'></span> Visualizar Eventos</a>";
echo "</div>";

include_once '../config/database.class.php';

$database = new Database();
$db = $database->getConnection();
?> 

<!-- HTML form for creating a product -->
<form action='../controller/controleAlocacao.php?operacao=salvar' method='post'>
 
    <table class='table table-hover table-responsive table-bordered'>
 
        <tr>
            <td>Colaborador</td>
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
            <td>Cliente</td>
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
            <td>Descrição</td>
            <td>
                <textarea type="text" name="desAlocacao" class="form-control" required></textarea>
            </td>
        
        </tr>
        <tr>
            <td>Data Alocação</td>
            <td>
                <div class="form-group has-feedback">
                    <input type="text" id="dataAlocacao" name="dataAlocacao" class="form-control" required/>
                    <span class="form-control-feedback glyphicon glyphicon glyphicon-off" aria-hidden="true"></span>
                </div>
            </td>
        
        </tr>
        
        <tr>
            <td>Período</td>
            <td>
                <select name="periodo" class="form-control" required>
                    <option value="M">Matutino</option>
                    <option value="V">Vespertino</option>
                    <option value="I">Integral</option>
                </select>
            </td>
        
        </tr>
        <tr>
            <td>Confirmado?</td>
            <td>
                <select name="confirmado" class="form-control" required>
                    <option value="S" selected>Sim</option>
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