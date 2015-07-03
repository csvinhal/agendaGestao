<?php
include_once "header.php";
?>

<?php
include_once '../../config/database.class.php';

$database = new Database();
$db = $database->getConnection();
?> 

<!-- HTML form para criar alocacao -->

<form enctype="multipart/form-data" action='../../PHPMailer/formulario.php?enviar=true' method='post'>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Nome Remetente</label>
            <input class="form-control" id="nomeRemetente" name="nomeRemetente">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Assunto</label>
            <input class="form-control" id="assunto" name="assunto">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
          <label for="exampleInputFile">Anexo</label>
          <input type="file" id="arquivo" name="arquivo">
          <input type="hidden" value="cristiano@gestao.com.br" name="email" >
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Mensagem</label>
            <textarea type="text" name="mensagem" class="form-control" autocomplete="off" required></textarea>
        </div>
    </div>
        <button type="submit" class="btn btn-default">Enviar</button>
    </div>
</form>

<?php
include_once "footer.php";
?>