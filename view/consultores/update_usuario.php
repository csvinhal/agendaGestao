<?php
include_once "header.php";

    if(isset($_SESSION['Mensagem'])){
        echo $_SESSION['Mensagem'];
        unset($_SESSION['Mensagem']);
    }
?>

<?php    
    //pega o ID do usuario a ser editado 
    $id = $_SESSION['user_id'];

    // include database e usuarioDAO
    include_once '../../config/database.class.php';
    include_once '../../model/usuarioDAO.class.php';
    include_once '../../model/Usuario.class.php';

    //Pega conexao database
    $database = new Database();
    $db = $database->getConnection();
    
    //Instacia p usuario
    $usuario = new Usuario($db);
    //Instacia usuarioDAO
    $usuarioDAO = new usuarioDAO($db);

    //Seta o ID do usuario a ser editado
    $usuario->idUsuario = $id;

    // read the details of product to be edited
    $usuarioDAO->readOne($usuario);
?>

<form action='../../controller/controleUsuario.php?operacao=update&idUsuario=<?php echo $id; ?>' method='post'>
    
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Alterar cliente</h3>
            </div>
 
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Nome:*</td>
                    <td><input type='text' name='nome' value='<?php echo $usuario->nome; ?>' class='form-control' autocomplete="off" maxlength="60" required></td>
                </tr>

                <tr>
                    <td>Sobrenome:*</td>
                    <td><input type='text' name='sobrenome' value='<?php echo $usuario->sobrenome; ?>' class='form-control' autocomplete="off" maxlength="60" required></td>
                </tr>

                <tr>
                    <td>E-mail:*</td>
                    <td><input type='text' name='email' value='<?php echo $usuario->email; ?>' class='form-control' autocomplete="off" maxlength="80" required></td>
                </tr>

                <tr>
                    <td>Senha:</td>
                    <td><input type='password' name='senha' class='form-control' autocomplete="off" maxlength="20"></td>
                </tr>

                <tr>
                    <td>Confirmar senha:</td>
                    <td><input type='password' name='conSenha' class='form-control' autocomplete="off" maxlength="20"></td>
                </tr>
               
                <tr>
                    <input type='hidden' name='ativo' value='<?php echo $usuario->ativo; ?>'>
                    <input type='hidden' name='idPapel' value='<?php echo $usuario->idPapel; ?>'>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</form>