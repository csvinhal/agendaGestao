<?php
// set page headers
$page_title = "Cadastrar Usuário";
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
    echo "<span class='glyphicon glyphicon-list-alt'></span> Visualizar Usuarios</a>";
echo "</div>";
?>


<!-- HTML form para cadastrar Usuario -->
<form action="../controller/controleUsuario.php?operacao=salvar" method="post">
 
    <table class='table table-hover table-responsive table-bordered'>
 
        <tr>
            <td>Nome:*</td>
            <td><input type='text' name='nome' class='form-control' autocomplete="off" maxlength="50" required></td>
        </tr>
 
        <tr>
            <td>Sobrenome:*</td>
            <td><input type='text' name='sobrenome' class='form-control' autocomplete="off" maxlength="50" required></td>
        </tr>
        <tr>
            <td>Email:*</td>
            <td><input type='text' name='email' class='form-control' autocomplete="off" maxlength="100" required></td>
        </tr>
        <tr>
            <td>Senha:*</td>
            <td><input type='password' name='senha' class='form-control' autocomplete="off" maxlength="20" required></td>
        </tr> 
        <tr>
            <td>Perfil:*</td>
            <td><select class='form-control' name="dlPapel">
                        <option value="" selected="selected">Selecione...</option>
                        <option value="A">Administrador</option>
                        <option value="P">PMO</option>
                        <option value="C">Colaborador</option>
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

<?php
include_once "footer.php";
?>