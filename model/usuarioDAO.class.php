<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuarioDAO
 *
 * @author Cristiano
 */
    include_once 'Usuario.class.php';

    class usuarioDAO {
    
    // Cria um atributo chamado conexao para armazenar uma inst�ncia da conex�o
    private $conn;

    /* Cria um m�todo construtor para armazenar a inst�ncia da conex�o na
     * No atributo conexao
    */
    public function __construct($db){
			// Armazena a inst�ncia da conexao no atributo conexao
			$this->conn = $db;
    }
    
    //Cria um valor randomico
    function createSalt(){
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        return $random_salt;
    }
    
    //Verifica se foi realizado mais de 5 tentativas de acesso
    function checkbrute($user_id) {
        date_default_timezone_set('America/Sao_Paulo');
        //Pega o timestamp do momento
        $now = time();  
        $valid_attempts = $now - (2 * 60 * 60);
        
        $stmt = $this->conn->prepare("SELECT time FROM login_attempts WHERE idUsuario = ? 
                                    and time > $valid_attempts;");
        $stmt->bindValue(1, $user_id);
        $stmt->execute();
            
        $rs_check = $stmt->fetch();
        if ($stmt->rowCount() >= 5) {
            return true;
        } else {
            return false;
        }
    }
    
    //Inserir tentativa de logins falhos
    function insertAttempts($param){
        $now = time();
        try{
            $stmt = $this->conn->prepare("INSERT INTO login_attempts(idUsuario, time) 
                                                            VALUES (?, ?);");
            $stmt->bindValue(1, $param);
            $stmt->bindValue(2, $now);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            } 
        }catch(PDOException $pe){
            $_SESSION['Mensagem'] = "O seguinte erro ocorreu ao tentar registrar a falha de login: ".$e->getMessage();
        }
    }
    
    //Fun��o para retornar a senha do usuario
    function searchPass($user_id){
        $stmt = $this->conn->prepare("SELECT senha FROM usuario WHERE idUsuario = ? LIMIT 0,1");
        $stmt->bindValue(1, $user_id);
        $stmt->execute();
        
        if($stmt->rowcount()){
            return $stmt;
        }else{
            return false;
        }
    }
    
    //Fun�ao para inserir usuario
    function create($usuario){
        $stmt = $this->conn->prepare("INSERT INTO usuario(idUsuario, nome, sobrenome, email, senha, salt, idPapel, ativo)
                                        VALUES(null,?,?,?,?,?,?,?);");
        // Adiciona os dados do usuario no lugar das interroga��es da instru��o SQL
        $stmt->bindValue(1,$usuario->nome);
        $stmt->bindValue(2,$usuario->sobrenome);
        $stmt->bindValue(3,$usuario->email);
        $stmt->bindValue(4,$usuario->senha);
        $stmt->bindValue(5,$usuario->salt);
        $stmt->bindValue(6,$usuario->idPapel);
        $stmt->bindValue(7,$usuario->ativo);
        
        // Executa a instru��o SQL
        if($stmt->execute()){
            return true;
        }else{
            return false;
        } 
    }
    
    //Deleta o usuario
    function delete($usuario){
        $stmt = $this->conn->prepare("DELETE FROM usuario WHERE idUsuario = ?;");
        $stmt->bindValue(1, $usuario->idUsuario);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    //Atualiza usuario sem senha
    function update($usuario){
        $stmt = $this->conn->prepare("UPDATE usuario SET nome = ?, sobrenome = ?,
                 email= ?, idPapel = ?, ativo = ? WHERE idUsuario = ?");
        // Adiciona os dados do usuario no lugar das interroga��es da instru��o SQL
        $stmt->bindValue(1,$usuario->nome);
        $stmt->bindValue(2,$usuario->sobrenome);
        $stmt->bindValue(3,$usuario->email);
        $stmt->bindValue(4,$usuario->idPapel);
        $stmt->bindValue(5,$usuario->ativo);
        $stmt->bindValue(6,$usuario->idUsuario);

        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    //Busca usuarios com senha
    function fullUpdate($usuario){
        $stmt = $this->conn->prepare("UPDATE usuario SET nome = ?, sobrenome = ?,
                 email = ?, senha = ?, idPapel = ?, ativo = ? WHERE idUsuario = ?");
        $stmt->bindValue(1,$usuario->nome);
        $stmt->bindValue(2,$usuario->sobrenome);
        $stmt->bindValue(3,$usuario->email);
        $stmt->bindValue(4,$usuario->senha);
        $stmt->bindValue(5,$usuario->idPapel);
        $stmt->bindValue(6,$usuario->ativo);
        $stmt->bindValue(7,$usuario->idUsuario);

        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    
    //busca todos os usuarios
    function search(){
        $stmt = $this->conn->prepare("SELECT * FROM usuario ORDER BY nome");
        $stmt->execute();
        
        return $stmt;
    }
    
    public function searchCol(){
        $stmt = $this->conn->prepare("SELECT idUsuario, nome, email FROM usuario WHERE idPapel = ? AND ativo = 'S' ORDER BY nome");
        $stmt->bindValue(1, 'C');
        $stmt->execute();
        
        return $stmt;
    }
    
    function searchConAtivo($param){
        $stmt = $this->conn->prepare("SELECT idUsuario, nome FROM usuario WHERE idPapel = ? AND idUsuario = ?");
        $stmt->bindValue(1, 'C');
        $stmt->bindValue(2, $param);
        $stmt->execute();
        
        return $stmt;
    }
    
    //Ler todos os usuario cadastrados, exceto administradores
    function readUsu(){
        $stmt = $this->conn->prepare("SELECT * 
                                FROM usuario WHERE idPapel <> 'A' ORDER BY nome");

        $stmt->execute();
        return $stmt;
    }
    
    //Numero de usuarios cadastrados
    public function countAll(){
    
        $stmt = $this->conn->prepare("SELECT * FROM usuario");
        $stmt->execute();
        $num = $stmt->rowCount();

        return $num;
    }
    
    function readOne($usuario){
        $stmt = $this->conn->prepare("SELECT nome, sobrenome, email, idPapel, ativo FROM usuario WHERE idUsuario = ? LIMIT 0,1");
        $stmt->bindValue(1, $usuario->idUsuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $usuario->nome = $row['nome'];
        $usuario->sobrenome = $row['sobrenome'];
        $usuario->email = $row['email'];
        $usuario->idPapel = $row['idPapel'];
        $usuario->ativo = $row['ativo'];
    }
    
    function readSalt($usuario){
        $stmt = $this->conn->prepare("SELECT salt FROM usuario WHERE idUsuario = ? LIMIT 0,1");
        $stmt->bindValue(1, $usuario->idUsuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $usuario->salt = $row['salt'];
    }
    
    
    //fun��o para validar login
    function logar($usuario){
        try{
            $stmt = $this->conn->prepare("SELECT idUsuario, nome, email, senha, salt, idPapel, ativo FROM usuario WHERE email = ? LIMIT 1;");
            $stmt->bindValue(1, $usuario->email);
            $stmt->execute();
            
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($stmt->rowCount() == 1) {
                $status = $rs->ativo;
                if ($status == 'N'){
                    $_SESSION['Mensagem'] = 'Usu&aacute;rio desativado!';
                }else{
                    $password = hash('sha512', $usuario->senha . $rs->salt);
                    if($this->checkbrute($rs->idUsuario)){
                        //Conta bloquada por muitas tentativas acesso
                        $_SESSION['Mensagem'] = 'Conta bloqueada por mais de 5 tentativas de acesso erroneas.';
                        return false;
                    }else{
                        // Verifica se a senha digita corresponde a mesma do banco de dados.
                        if($password == $rs->senha){
                            // Se a senha for correta
                            // Pega a string do browser do usuario.
                            $user_browser = $_SERVER['HTTP_USER_AGENT'];
                            $rs->idUsuario = preg_replace("/[^0-9]+/", "", $rs->idUsuario);
                            $_SESSION['user_id'] = $rs->idUsuario;
                            $rs->nome = preg_replace("/[^a-zA-Z0-9_\-]+/","",$rs->nome);
                            $_SESSION['usuario'] = $rs->nome;
                            $_SESSION['login_string'] = hash('sha512', $rs->senha.$user_browser);
                            $_SESSION['perfil'] = $rs->idPapel;
                            //Logado com sucesso
                            return true;
                        }else{
                            //Se a senha n�o for correta salva a tentativa de login falha
                            if($this->insertAttempts($rs->idUsuario)){
                                $_SESSION['Mensagem'] = 'Senha inv&aacute;lida!';;                                                        
                            }else{
                                $_SESSION['Mensagem'] = 'N&atilde;o foi poss&iacute;vel realizar a inser&ccedil;&atilde;o da tentativa falha!';
                            }       
                        }
                    }
                }
            }else{
                $_SESSION['Mensagem'] = 'Usu&aacute;rio n&atilde;o cadastrado!';
            }
        }catch(PDOException $pe){
            $_SESSION['Mensagem'] = "O seguinte erro ocorreu: ".$e->getMessage();
        }
    }
}
?>