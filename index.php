<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Agenda Gest&atilde;o</title>
  <link rel="stylesheet" href="style/style.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>

	<section class="container">
    	<div class="login">
      		<h1>Logar no sistema</h1>
              <form method="post" action="controller/controleUsuario.php?operacao=logar">
                <p><input type="text" name="login" id="txtLogin" placeholder="Email"></p>
                <p><input type="password" name="senha" class="campo" id="txtSenha" placeholder="Senha"></p>
                <p class="remember_me">
                  <label>
                    <input type="checkbox" name="remember_me" id="remember_me">
                    Relembre neste computador
                  </label>
                </p>
                <p class="submit"><input type="submit" name="logar" id="logar" value="Login"></p>
                <?php
                    include_once './config/functions.php';
                    sec_session_start();
                        if(isset($_SESSION['Mensagem'])){
                            echo "<p>";
                                    echo $_SESSION['Mensagem'];
                                    session_unset();
                            echo "</p>";
                    }
                ?>
              </form>
        </div>
    <div class="login-help">
    	<p>Perdeu sua senha? <a href="index.php">Clique aqui para resetar</a>.</p>
    </div>
  </section>
</body>
</html>
