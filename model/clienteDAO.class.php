<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of clienteDAO
 *
 * @author Cristiano
 */
include_once 'Cliente.class.php';
    class clienteDAO {
    
    // Cria um atributo chamado conexao para armazenar uma instância da conexão
    private $conn;

    /* Cria um método construtor para armazenar a instância da conexão na
     * No atributo conexao
    */
    public function __construct($db){
			// Armazena a instância da conexao no atributo conexao
			$this->conn = $db;
    }
    
    function create($cliente){

        $stmt = $this->conn->prepare("INSERT INTO cliente(idCliente, razaosocial, nomefantasia, CNPJ, CEP, UF, cidade, bairro, logradouro, numero, observacao)
                                                                                    VALUES(null,?,?,?,?,?,?,?,?,?,?)");
        // Adiciona os dados do cliente no lugar das interrogações da instrução SQL
        $stmt->bindValue(1,$cliente->razaoSocial);
        $stmt->bindValue(2,$cliente->nomeFantasia);
        $stmt->bindValue(3,$cliente->CNPJ);
        $stmt->bindValue(4,$cliente->CEP);
        $stmt->bindValue(5,$cliente->UF);
        $stmt->bindValue(6,$cliente->cidade);
        $stmt->bindValue(7,$cliente->bairro);
        $stmt->bindValue(8,$cliente->logradouro);
        $stmt->bindValue(9,$cliente->numero);
        $stmt->bindValue(10,$cliente->observacao);
        
        // Executa a instrução SQL
        if($stmt->execute()){
            return true;
        }else{
            return false;
        } 
    }
   
    //Deleta o cliente
    function delete($cliente){
        $stmt = $this->conn->prepare("DELETE FROM cliente WHERE idCliente = ?");
        $stmt->bindValue(1, $cliente->idCliente);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    //lista todos os clientes
    function search(){
        $stmt = $this->conn->prepare("SELECT * FROM cliente ORDER BY nomeFantasia");

        $stmt->execute();
        return $stmt;
    }
    
    //Numero de clientes cadastrados
    public function countAll(){
    
        $stmt = $this->conn->prepare("SELECT * FROM cliente");
        $stmt->execute();
        $num = $stmt->rowCount();

        return $num;
    }
    
    //Busca somente um cliente
    function readOne($cliente){
        $stmt = $this->conn->prepare("SELECT * FROM cliente WHERE idCliente = ? LIMIT 0,1");
        $stmt->bindValue(1, $cliente->idCliente);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $cliente->razaoSocial = $row['razaosocial'];
        $cliente->nomeFantasia = $row['nomefantasia'];
        $cliente->CNPJ = $row['CNPJ'];
        $cliente->CEP = $row['CEP'];
        $cliente->UF = $row['UF'];
        $cliente->cidade = $row['cidade'];
        $cliente->bairro = $row['bairro'];
        $cliente->logradouro = $row['logradouro'];
        $cliente->numero = $row['numero'];
    }
    
    function update($cliente){
        $stmt = $this->conn->prepare("UPDATE cliente SET razaosocial = ?, nomefantasia = ?, 
                CNPJ = ?, CEP = ?, UF = ?, cidade = ?, bairro = ?, logradouro = ?, numero = ? WHERE idCliente = ?");
        // Adiciona os dados do cliente no lugar das interrogações da instrução SQL
        $stmt->bindValue(1,$cliente->razaoSocial);
        $stmt->bindValue(2,$cliente->nomeFantasia);
        $stmt->bindValue(3,$cliente->CNPJ);
        $stmt->bindValue(4,$cliente->CEP);
        $stmt->bindValue(5,$cliente->UF);
        $stmt->bindValue(6,$cliente->cidade);
        $stmt->bindValue(7,$cliente->bairro);
        $stmt->bindValue(8,$cliente->logradouro);
        $stmt->bindValue(9,$cliente->numero);
        $stmt->bindValue(10,$cliente->idCliente);

        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}
