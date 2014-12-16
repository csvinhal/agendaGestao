<?php

    class agendaDAO {    
        
        //função para receber o dia corrente
        function getActualDay(){
            $hoje = date('j');
            return $hoje;
        }
        
        //função para retornar o primeiro dia
        function getFirstDay($month, $year){
            return date('j', mktime(0,0,0,$month,1,$year));
        }
        
        //função para receber o dia em extenso
        function getDayExtensive($month, $day, $year){
            $extesive = date('l', mktime(0,0,0,$month, $day, $year));
            if($extesive == 'Monday'){
                return 'Segunda-Feira';
            }else if($extesive == 'Tuesday'){
                return 'Terça-Feira';
            }else if($extesive == 'Wednesday'){
                return 'Quarta-Feira';
            }else if($extesive == 'Thursday'){
                return 'Quinta-Feira';
            }else if($extesive == 'Friday'){
                return 'Sexta-Feira';
            }else if($extesive == 'Saturday'){
                return 'Sábado';
            }else if($extesive == 'Sunday'){
                return 'Domingo';
            }
        }
        
        //função para receber o nome do mes
        function getNameMon($month){
            if($month == 1){
                return $month = "Jan";
            }else if($month == 2){
                return $month = "Fev";
            }else if($month == 3){
                return $month = "Mar";
            }else if($month == 4){
                return $month = "Abr";
            }else if($month == 5){
                return $month = "Maio";
            }else if($month == 6){
                return $month = "Jun";
            }else if($month == 7){
                return $month = "Jul";
            }else if($month == 8){
                return $month = "Ago";
            }else if($month == 9){
                return $month = "Set";
            }else if($month == 10){
                return $month = "Out";
            }else if($month == 11){
                return $month = "Nov";
            }else if($month == 12){
                return $month = "Dez";
            }
        }
        
        //verifica se ano é bisesto
        function getDayBisesto($month, $year){
            if((($year % 4) == 0 and ($year % 100)!=0) or ($year % 400)==0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        
        //recebe o numero de dia do mes
        function getNumberDay($month, $year){
            if($month == 1){
                return $number = 31;
            }else if($month == 2){
                if($this->getDayBisesto($month, $year) == TRUE){
                    return $number = 29;
                }else{
                    return $number = 28;
                }
            }else if($month == 3){
                return $number = 31;
            }else if($month == 4){
                return $number = 30;
            }else if($month == 5){
                return $number = 31;
            }else if($month == 6){
                return $number = 30;
            }else if($month == 7){
                return $number = 31;
            }else if($month == 8){
                return $number = 31;
            }else if($month == 9){
                return $number = 30;
            }else if($month == 10){
                return $number = 31;
            }else if($month == 11){
                return $number = 30;
            }else if($month == 12){
                return $number = 31;
            }
        }
    }
?>

