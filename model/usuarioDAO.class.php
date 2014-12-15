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
    include_once '../config/database.class.php';
    include_once 'Usuario.class.php';

    class usuarioDAO {
    
    // Cria um atributo chamado conexao para armazenar uma instância da conexão
    private $conn;

    /* Cria um método construtor para armazenar a instância da conexão na
     * No atributo conexao
    */
    public function __construct($db){
			// Armazena a instância da conexao no atributo conexao
			$this->conn = $db;
    }
    
    function checkbrute($user_id) {
        //Pega o timestamp do momento
        $now = time();  
        $valid_attempts = $now - (2 * 60 * 60);
        
        $stmt = $this->conn->prepare("SELECT time FROM login_attempts WHERE idUsuario = ? 
                                    and time > $valid_attempts;");
        $stmt->bindValue(1, $user_id);
        $stmt->execute();
            
        $rs_check = $stmt->fetch();
        if ($stmt->rowCount() > 5) {
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
    
    //função para validar login
    public function logar($usuario){
            $stmt = $this->conn->prepare("SELECT idUsuario, nome, email, senha FROM usuario WHERE email = ? LIMIT 1;");
            $stmt->bindValue(1, $usuario->email);
            $stmt->execute();
            
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($stmt->rowCount() == 1) {
                if($this->checkbrute($rs->idUsuario)){
                    //Conta bloquada por muitas tentativas acesso
                    $_SESSION['Mensagem'] = 'Conta bloqueada por mais de 5 tentativas de acesso erroneas.';
                    return false;
                }else{
                    // Verifica se a senha digita corresponde a mesma do banco de dados.
                    if($usuario->senha == $rs->senha){
                        // Se a senha for correta
                        // Pega a string do browser do usuario.
                        $user_browser = $_SERVER['HTTP_USER_AGENT'];

                        
                        $rs->idUsuario = preg_replace("/[^0-9]+/", "", $rs->idUsuario);
                        $_SESSION['user_id'] = $rs->idUsuario;
                        $rs->nome = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                "", 
                                                                $rs->nome);
                        
                        $_SESSION['usuario'] = $rs->nome;
                        $_SESSION['login_string'] = hash('sha512', $rs->senha.$user_browser);
                        //Logado com sucesso
                        return true;
                    }else{
                        //Se a senha não for correta salva a tentativa de login falha
                        if($this->insertAttempts($rs->idUsuario)){
                            $_SESSION['Mensagem'] = 'Senha inválida!';                                                        
                        }else{
                            $_SESSION['Mensagem'] = 'Não foi possível realizar a inserção da tentativa falha!';
                        }
                        
                            
                    }
                }
            }else{
                $_SESSION['Mensagem'] = 'Usuário não cadastrado!';
            }
            /*$rs = $stmt->fetch(PDO::FETCH_OBJ);
            $_SESSION['usuario'] = $rs->nome;

            if($stmt->rowCount() == 1){
                    return true;
            }else{
                    return false;
            }*/
    }
    
    function create($usuario){
        $stmt = $this->conn->prepare("INSERT INTO usuario(idUsuario, nome, sobrenome, email, senha, idPapel)
                                        VALUES(null,?,?,?,?,?);");
        // Adiciona os dados do usuario no lugar das interrogações da instrução SQL
        $stmt->bindValue(1,$usuario->nome);
        $stmt->bindValue(2,$usuario->sobrenome);
        $stmt->bindValue(3,$usuario->email);
        $stmt->bindValue(4,$usuario->senha);
        $stmt->bindValue(5,$usuario->idPerfil);
        
        // Executa a instrução SQL
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
    
    function searchCol(){
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE idPapel = ? ORDER BY nome");
        $stmt->bindValue(1, 'C');
        $stmt->execute();
        
        return $stmt;
    }
    
    function readAll($page, $from_record_num, $records_per_page){
        $stmt = $this->conn->prepare("SELECT * 
                                FROM usuario ORDER BY nome ASC LIMIT {$from_record_num}, {$records_per_page}");

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
    
    //Deleta o usuario
    function delete($usuario){
        $stmt = $this->conn->prepare("DELETE FROM usuario WHERE idUsuario = ?");
        $stmt->bindValue(1, $usuario->idUsuario);

        if($resultado = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    function readOne($usuario){
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE idUsuario = ? LIMIT 0,1");
        $stmt->bindValue(1, $usuario->idUsuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $usuario->nome = $row['nome'];
        $usuario->sobrenome = $row['sobrenome'];
        $usuario->email = $row['email'];
        $usuario->idPapel = $row['idPapel'];
    }
    
    function update($usuario){
        $stmt = $this->conn->prepare("UPDATE usuario SET nome = ?, sobrenome = ?,
                 email= ?, idPapel = ? WHERE idUsuario = ?");
        // Adiciona os dados do usuario no lugar das interrogações da instrução SQL
        $stmt->bindValue(1,$usuario->nome);
        $stmt->bindValue(2,$usuario->sobrenome);
        $stmt->bindValue(3,$usuario->email);
        $stmt->bindValue(4,$usuario->idPerfil);
        $stmt->bindValue(5,$usuario->idUsuario);

        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}