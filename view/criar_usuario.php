<?php
// set page headers
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}

include_once '../config/functions.php';
?>

<?php
echo "<div class='right-button-margin'>";
    echo "<a href='view_usuarios.php' class='btn btn-default pull-right'>";
    echo "<span class='glyphicon glyphicon-list-alt'></span> Visualizar Usu&aacute;rios</a>";
echo "</div>";
?>


<!-- HTML form para cadastrar Usuario -->
<form action="../controller/controleUsuario.php?operacao=salvar" method="post">

    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Cadastrar usu&aacute;rios</h3>
            </div>
            <table class='table table-hover'>

                <tr>
                    <td>Nome:*</td>
                    <td><input type='text' name='nome' class='form-control' autocomplete="off" maxlength="60" required></td>
                </tr>

                <tr>
                    <td>Sobrenome:*</td>
                    <td><input type='text' name='sobrenome' class='form-control' autocomplete="off" maxlength="60" required></td>
                </tr>
                <tr>
                    <td>Email:*</td>
                    <td><input type='text' name='email' class='form-control' autocomplete="off" maxlength="80" required></td>
                </tr>
                <tr>
                    <td>Senha:*</td>
                    <td><input type='password' name='senha' class='form-control' autocomplete="off" maxlength="20" required></td>
                </tr>
                <tr>
                    <td>Confirmar senha:*</td>
                    <td><input type='password' name='conSenha' class='form-control' autocomplete="off" maxlength="20" required></td>
                </tr> 
                <tr>
                    <td>Perfil:*</td>
                    <td>
                    <?php 
                    include_once '../config/database.class.php';
                    include_once '../model/papelDAO.class.php';

                        $database = new Database();
                        $db = $database->getConnection();

                        $papelDAO = new papelDAO($db);
                        $stmt = $papelDAO->read();

                        echo "<select class='form-control' name='dlPapel'>";
                        echo "<option selected=\"selected\">Please select...</option>";
                            while ($row_papel = $stmt->fetch(PDO::FETCH_ASSOC)){
                                extract($row_papel);
                                echo "<option value=\"$idPapel\">$descPapel</option>";
                            }
                        echo "</select>";
                    ?>
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

<?php
include_once "footer.php";
?>