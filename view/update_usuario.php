<?php
$page_title = "Atualizar Usu&aacute;rio";
include_once "header.php";

    if(isset($_SESSION['Mensagem'])){
        echo $_SESSION['Mensagem'];
        unset($_SESSION['Mensagem']);
    }
?>

<?php
    echo "<div class='right-button-margin'>";
    echo "<a href='view_usuarios.php' class='btn btn-default pull-right'>Listar Usuarios</a>";
    echo "</div>";
    
    //pega o ID do usuario a ser editado 
    $id = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : die('ERROR: missing ID.');

    // include database e usuarioDAO
    include_once '../config/database.class.php';
    include_once '../model/usuarioDAO.class.php';
    include_once '../model/Usuario.class.php';

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

<form action='../controller/controleUsuario.php?operacao=update&idUsuario=<?php echo $id; ?>' method='post'>
 
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
            <td>Perfil:*</td>
                <td>
                    <?php
                        include_once '../model/papelDAO.class.php';

                        $papelDAO = new papelDAO($db);
                        $stmt = $papelDAO->read();

                        //Acrescenta select drop-down
                        echo "<select class='form-control' name='idPapel'>";

                            echo "<option>Please select...</option>";
                            while ($row_papel = $stmt->fetch(PDO::FETCH_ASSOC)){
                                extract($row_papel);
                                if($idPapel == $usuario->idPapel){
                                    echo "<option value='$idPapel' selected >";
                                }else{
                                    echo "<option value='$idPapel'>";
                                }
                                echo "$descPapel</option>";
                                // current category of the product must be selected
                            }
                        echo "</select>";
                    ?>
                </td>
            </tr>
            <tr>
                <td>Ativo:*</td>
                <td>
                    <div class="radio">
                        <label class="radio-inline">
                            <?php
                                if($usuario->ativo == TRUE){
                                    echo "<input type=\"radio\" name=\"ativo\" id=\"ativoSim\" value=\"1\" checked>";
                                }else{
                                    echo "<input type=\"radio\" name=\"ativo\" id=\"ativoSim\" value=\"1\" >";
                                }
                            ?>
                            Sim
                        </label>
                        <label class="radio-inline">
                            <?php
                                if($usuario->ativo == FALSE){
                                    echo "<input type=\"radio\" name=\"ativo\" id=\"ativoNao\" value=\"0\" checked>";
                                }else{
                                    echo "<input type=\"radio\" name=\"ativo\" id=\"ativoNao\" value=\"0\" >";
                                }
                            ?>
                            N&atilde;o
                        </label>
                    </div>
                </td>
            </tr> 

        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </td>
        </tr>
 
    </table>
</form>