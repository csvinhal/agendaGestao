<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Alocacao
 *
 * @author Cristiano
 */
class Alocacao {
	private $dataAlocacao;
        private $horaInicio;
        private $horaFim;
	private $desAlocacao;
        private $idTipAloc;
        private $periodo;
        private $confirmado;
	private $idUsuario;
	private $idColaborador;
	private $idCliente;
        private $editando;
        private $bloqueado;






        // Cria os m�todos Getters e Setters
	public function __get($atributo){
		return $this->$atributo;
	}
	public function __set($atributo, $valor){
		$this->$atributo=$valor;
	}
	// Cria  a fun��o toString da classe
	public function __toString(){
		return $this->nome;
	}
}
