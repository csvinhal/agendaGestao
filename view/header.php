<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
 
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1;" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>Agenda Gest&atildeo</title>
 
    <!-- some custom CSS -->
    <style>
    .left-margin{
        margin:0 .5em 0 0;
    }
 
    .right-button-margin{
        margin: 0 0 1em 0;
        overflow: hidden;
    }
    </style>
    

    
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
 
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
 
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

 
    <!-- Include all compiled plugins (below), or include individual files as needed -->

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    
    <script src="../js/bootstrap-datepicker.js"></script>
    <script src="../js/locales/bootstrap-datepicker.pt-BR.js"></script>
    <script src="../js/filters.js"></script>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">

    
    
    
    
</head>
<body>
<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include_once '../config/functions.php';
sec_session_start();
if(login_check() === true): 
    echo "<nav class='navbar navbar-default' role='navigation'>";
        echo "<div class='container-fluid'>";
            //<!-- Brand and toggle get grouped for better mobile display -->
            echo "<div class='navbar-header'>";
              echo "<button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-inverse-collapse'>";
                echo "<span class='icon-bar'></span>";
                echo "<span class='icon-bar'></span>";
                echo "<span class='icon-bar'></span>";
              echo "</button>";
              echo "<a class='navbar-brand'href='index.php'><span class='glyphicon glyphicon-home'></span>Home</a>";
            echo "</div>";
            echo "<div class='collapse navbar-collapse navbar-inverse-collapse'>";
                echo "<ul class='nav navbar-nav'>";
                  echo "<li class='dropdown'>";
                    echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>Usu&aacute;rio<span class='caret'></span></a>";
                    echo "<ul class='dropdown-menu' role='menu'>";
                        echo "<li><a href='criar_usuario.php'>Cadastrar Usu&aacute;rio</a></li>";
                        echo "<li><a href='view_usuarios.php'>Editar/Deletar Usu&aacute;rio</a></li>";
                    echo "</ul>";
                  echo "</li>";
                  echo "<li class='dropdown'>";
                    echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>Cliente<span class='caret'></span></a>";
                    echo "<ul class='dropdown-menu' role='menu'>";
                        echo "<li><a href='criar_cliente.php'>Cadastrar Cliente</a></li>";
                        echo "<li><a href='view_cliente.php'>Editar/Deletar Cliente</a></li>";
                    echo "</ul>";
                  echo "</li>";
                  echo "<li class='dropdown'>";
                    echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>Agenda<span class='caret'></span></a>";
                    echo "<ul class='dropdown-menu' role='menu'>";
                        echo "<li><a href='criar_alocacao.php'>Cadastrar Aloca&ccedil;&atilde;o</a></li>";
                        echo "<li><a href='view_agendaGeral.php'>Visualizar Agenda</a></li>";
                    echo "</ul>";
                  echo "</li>";
                echo "</ul>";
                echo "<ul role='menu' class='nav navbar-nav navbar-right'>";
                    echo "<li>";
                        echo "<p class='navbar-text'><span class='glyphicon glyphicon-user'></span> Bem Vindo ".$_SESSION['usuario']."</p>";
                    echo "</li>";
                    echo "<li>";
                            echo "<a href='../logout.php' glyphicon glyphicon-off>";
                            echo "<span class='glyphicon glyphicon-off' ></span> Logout</a>";
                    echo "</li>";
                echo "</ul>";
            echo "</div>";
        echo "</div>";
    echo "</nav>";
    //<!-- container -->
    echo "<div class='container'>";
else:
    header('location: ../error.php');
endif;
?>