<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuario
 *
 * @author Cristiano
 */
class Usuario {
	private $idUsuario;
	private $nome;
	private $sobrenome;
	private $email;
	private $senha;
        private $salt;
	private $idPapel;
	
	// Cria os métodos Getters e Setters
	public function __get($atributo){
		return $this->$atributo;
	}
	public function __set($atributo, $valor){
		$this->$atributo=$valor;
	}
	// Cria  a função toString da classe
	public function __toString(){
		return $this->nome;
	}
}
