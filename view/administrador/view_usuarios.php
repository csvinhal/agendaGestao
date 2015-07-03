<?php
include_once "header.php";

    if(isset($_SESSION['Mensagem'])){
        echo $_SESSION['Mensagem'];
        unset($_SESSION['Mensagem']);
    }
?>

<?php

echo "<div class='right-button-margin'>";
    echo "<a href='criar_usuario.php' class='btn btn-default pull-right'>";
    echo "<span class='glyphicon glyphicon-plus' ></span> Criar usu&aacute;rio</a>";
echo "</div>";

include_once '../../model/usuarioDAO.class.php';
include_once '../../model/papelDAO.class.php';
include_once '../../config/database.class.php';

$database = new Database();
$db = $database->getConnection();

$usuarioDAO = new usuarioDAO($db);
// query usuario
$stmt = $usuarioDAO->search();
$num = $stmt->rowCount();
 
// display the products if there are any
if($num>0){
    $papelDAO = new papelDAO($db);
    echo "<table id='tabelaUsuarios' class='table table-hover'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th class='text-center'>C&oacute;digo</th>";
                echo "<th class='text-center'>Nome</th>";
                echo "<th class='text-center'>Sobrenome</th>";
                echo "<th class='text-center'>Email</th>";
                echo "<th class='text-center'>Permiss&atilde;o</th>";
                echo "<th class='text-center'>Status</th>";
                echo "<th class='text-center'>Gerenciar</th>";
            echo "</tr>";
        echo "</thead>";

        echo "<tbody>"; 
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                echo "<tr>";
                    echo "<td class='text-center'>{$idUsuario}</td>";
                    echo "<td class='text-center'>{$nome}</td>";
                    echo "<td class='text-center'>{$sobrenome}</td>";
                    echo "<td class='text-center'>{$email}</td>";
                    echo "<td class='text-center'>";
                        echo $papelDAO->readName($row['idPapel']);
                    echo"</td>";
                    echo "<td class=\"text-center\">";
                    if($ativo == 'S'){
                        echo "Ativado";
                    }else{
                        echo "Desativado";
                    }
                echo "</td>";
                echo "<td class='text-center'>";
                        // botoes edite e delete
                        echo "<a href='update_usuario.php?idUsuario={$idUsuario}' class='btn btn-default left-margin'>";
                        echo "<span class='glyphicon glyphicon-cog' ></span> Editar</a>";
                        echo "<a delete-id='{$idUsuario}' onclick='deletarUsuario()' class='btn btn-default delete-object'>";
                        echo "<span class='glyphicon glyphicon-trash' ></span> Deletar</a>";
                echo "</td>";
                echo "</tr>";
            }
        echo "</tbody>"; 
    echo "</table>";
}
 
// avisa que nao ha usuarios
else{
    echo $num."<div>N&atilde;o foram encontrados usu&aacute;rios.</div>";
}
?>

<script>
$(document).ready(function(){
    $('#tabelaUsuarios').dataTable();
});
</script>

<?php
include_once "footer.php";
?>
