<?php
$page_title = "Visualizar Usu&aacute;rios";
include_once "header.php";

    if(isset($_SESSION['Mensagem'])){
        echo $_SESSION['Mensagem'];
        unset($_SESSION['Mensagem']);
    }
?>

<?php

echo "<div class='right-button-margin'>";
    echo "<a href='criar_usuario.php' class='btn btn-default pull-right'>";
    echo "<span class='glyphicon glyphicon-plus' ></span> Criar usuário</a>";
echo "</div>";

//verificar se a pagina recebe parametro URL, pagina default é 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;

//numero de linhas por paginas
$records_per_page = 4;
 
//calcula por query o limite por pagina
$from_record_num = ($records_per_page * $page) - $records_per_page;

include_once '../model/usuarioDAO.class.php';
include_once '../model/papelDAO.class.php';
include_once '../config/database.class.php';

$database = new Database();
$db = $database->getConnection();

$usuarioDAO = new usuarioDAO($db);
// query usuario
$stmt = $usuarioDAO->readAll($page, $from_record_num, $records_per_page);
$num = $stmt->rowCount();
 
// display the products if there are any
if($num>0){
    $papelDAO = new papelDAO($db);
    echo "<div class=\"row\">";
        echo "<div class=\"panel panel-primary filterable\">";
            echo "<div class=\"panel-heading\">";
                echo "<h3 class=\"panel-title\">Usu&aacute;rios</h3>";
                echo "<div class=\"pull-right\">";
                    echo "<button class=\"btn btn-default btn-xs btn-filter\"><span class=\"glyphicon glyphicon-filter\"></span> Filtrar</button>";
                echo "</div>";
            echo "</div>";
            echo "<table class='table table-hover'>";
                echo "<thead>";
                    echo "<tr class=\"filters\">";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"C&oacute;digo\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Nome\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Sobrenome\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Email\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Permiss&atilde;o\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Gerenciar\" disabled></th>";
                    echo "</tr>";
                echo "</thead>";

                echo "<tbody>"; 
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                        extract($row);

                        echo "<tr>";
                            echo "<td>{$idUsuario}</td>";
                            echo "<td>{$nome}</td>";
                            echo "<td>{$sobrenome}</td>";
                            echo "<td>{$email}</td>";
                            echo "<td>";
                                echo $papelDAO->readName($row['idPapel']);
                            echo"</td>";
                            echo "<td class='text-center'>";
                                    // botoes edite e delete
                                    echo "<a href='update_usuario.php?idUsuario={$idUsuario}' class='btn btn-default left-margin'>";
                                    echo "<span class='glyphicon glyphicon-cog' ></span> Editar</a>";
                                    echo "<a delete-id='{$idUsuario}' class='btn btn-default delete-object'>";
                                    echo "<span class='glyphicon glyphicon-trash' ></span> Deletar</a>";
                            echo "</td>";
                        echo "</tr>";

                    }
                echo "</tbody>"; 
            echo "</table>";
        echo "</div>";
    echo "</div>";
    //botoes de paginacao vao aqui
    include_once 'paginacao_usuario.php';
}
 
// avisa que nao ha usuarios
else{
    echo $num."<div>Não foram encontrados usuários.</div>";
}
?>

<script>
$(document).on('click', '.delete-object', function(){
 
    var id = $(this).attr('delete-id');
    var q = confirm("Tem certeza que deseja excluir o usu&aacute;rio??");
 
    if (q == true){
 
        $.post('../controller/controleUsuario.php', {
            operacao: 'deletar',
            object_id: id
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('Não foi possível deletar o usu&aacute;rio.');
        });
 
    }
 
    return false;
});
</script>

<?php
include_once "footer.php";

?>
