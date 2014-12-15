<?php
$page_title = "Visualizar Usuarios";
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    session_unset();
}
?>

<?php

echo "<div class='right-button-margin'>";
    echo "<a href='criar_usuario.php' class='btn btn-default pull-right'>Criar usuário</a>";
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
    echo "<table class='table table-hover table-responsive table-bordered'>";
        echo "<tr>";
            echo "<th>Codigo</th>";
            echo "<th>Nome</th>";
            echo "<th>Sobrenome</th>";
            echo "<th>Email</th>";
            echo "<th>Permissao</th>";
            echo "<th>Gerenciar</th>";
        echo "</tr>";
 
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
                echo "<td>";
                        // botões edite e delete
                        echo "<a href='update_usuario.php?idUsuario={$idUsuario}' class='btn btn-default left-margin'>Editar</a>";
                        echo "<a delete-id='{$idUsuario}' class='btn btn-default delete-object'>Deletar</a>";
                echo "</td>";
            echo "</tr>";
 
        }
 
    echo "</table>";
 
    //botões de paginação vão aqui
    include_once 'paginacao_usuario.php';
}
 
// avisa que não há usuários
else{
    echo $num."<div>Não foram encontrados usuários.</div>";
}
?>

<script>
$(document).on('click', '.delete-object', function(){
 
    var id = $(this).attr('delete-id');
    var q = confirm("Tem certeza que deseja excluir o usuário??");
 
    if (q == true){
 
        $.post('../controller/controleUsuario.php', {
            operacao: 'deletar',
            object_id: id
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('Não foi possível deletar o usuário.');
        });
 
    }
 
    return false;
});
</script>


<?php
include_once "footer.php";
?>