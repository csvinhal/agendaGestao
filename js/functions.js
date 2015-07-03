function deletarUsuario(){
    $(document).on('click', '.delete-object', function(){
 
    var id = $(this).attr('delete-id');
    var q = confirm("Tem certeza que deseja excluir o usu�rio??");
 
    if (q == true){
 
        $.post('../../controller/controleUsuario.php', {
            operacao: 'deletar',
            object_id: id
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('N�o foi poss�vel deletar o usu&aacute;rio.');
        });
 
    }
 
    return false;
});
}

function deletarCliente(){
    $(document).on('click', '.delete-object', function(){
 
    var id = $(this).attr('delete-id');
    var q = confirm("Tem certeza que deseja excluir o cliente??");
 
    if (q == true){
 
        $.post('../../controller/controleCliente.php', {
            operacao: 'deletar',
            object_id: id
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('N�o foi poss�vel deletar o cliente.');
        });
 
    }
 
    return false;
});
}

function deletarAlocacao(){
    $(document).on('click', '.delete-object', function(){
 
    var data = $(this).attr('delete-data');
    var idCol = $(this).attr('delete-col');
    var horaIni = $(this).attr('delete-hIni');
    var horaFim = $(this).attr('delete-hFim');
    var q = confirm("Tem certeza que deseja excluir essa aloca��o?");
 
    if (q == true){
 
        $.post('../../controller/controleAlocacao.php', {
            operacao: 'deletar',
            object_data: data,
            object_col: idCol,
            object_ini: horaIni,
            object_fim: horaFim
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('N�o foi poss�vel excluir a aloca��o!');
        });
 
    }
 
    return false;
});
}

function liberarAlocacao(){
    $(document).on('click', '.libera-object', function(){
 
    var data = $(this).attr('libera-data');
    var idCol = $(this).attr('libera-col');
    var horaIni = $(this).attr('libera-hIni');
    var horaFim = $(this).attr('libera-hFim');
    var q = confirm("Tem certeza que deseja liberar essa aloca��o?");
 
    if (q == true){
 
        $.post('../../controller/controleAlocacao.php', {
            operacao: 'liberar',
            object_data: data,
            object_col: idCol,
            object_ini: horaIni,
            object_fim: horaFim
        }, function(data){
            location.reload(alert("Aloca��o liberada com sucesso!"));
        }).fail(function() {
            alert('N�o foi poss�vel liberar a aloca��o!');
        });
    }
    return false;
});
}

function mostrarOcultar(){
    if(document.getElementById("periodos").style.display == "block"){
        document.getElementById("horas").style.display = "block";
        document.getElementById("periodos").style.display = "none";
        document.getElementById("periodos").attr('required', false);
    }else if(document.getElementById("periodos").style.display == "none"){
        document.getElementById("periodos").style.display = "block";
        document.getElementById("horas").style.display = "none";  
        document.getElementById("horas").attr('required', false); 
    }
}