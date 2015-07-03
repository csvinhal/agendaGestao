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
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
 
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!--<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>-->
    <script src="./js/jquery-1.11.2.min.js"></script>
    <script src="./js/jquery-migrate-1.2.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->

    <!-- Latest compiled and minified JavaScript -->
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>-->
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    
    <!-- JavaScript para o datapicker-->
    <script src="./js/bootstrap-datepicker.js"></script>
    <script src="./js/locales/bootstrap-datepicker.pt-BR.js"></script>
    
    <!-- JavaScript para os filtros nas visualizacoes dos dados -->
    <script src="./js/filters.js"></script>
    <!-- JavaScript com as funcoes basicas -->
    <script src="./js/functions.js"></script>
    <!-- CSS geral da agenda -->
    <link rel="stylesheet" href="./css/calendar.css">
</head>
<body>
<div class='container'>
    
<?php
    include_once './config/functions.php';
    sec_session_start();
	
    echo "<div class=\"alert alert-danger alert-dismissable\">";
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
    echo "<p>Voc&ecirc; n&atilde;o est&aacute; autorizado a acessar a p&aacute;gina, por favor fa&ccedil;a login</p>";
    echo "<p><a class='btn btn-primary btn-lg' href='index.php' role='button'>Voltar</a></p>";
    echo "</div>";
?>

</div>
<!-- /container --> 
</body>
</html>
