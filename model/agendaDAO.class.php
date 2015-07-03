<?php

    class agendaDAO {    
        
        //função para receber o dia corrente
        function getActualDay(){
            $hoje = date('j');
            return $hoje;
        }
        
        //função para receber o mes corrente
        function getActualMonth(){
            $mesAtual = date('n');
            return $mesAtual;
        }
        
        //função para receber o ano corrente
        function getActualYear(){
            $anoAtual = date('Y');
            return $anoAtual;
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
                return 'Ter&ccedil;a-Feira';
            }else if($extesive == 'Wednesday'){
                return 'Quarta-Feira';
            }else if($extesive == 'Thursday'){
                return 'Quinta-Feira';
            }else if($extesive == 'Friday'){
                return 'Sexta-Feira';
            }else if($extesive == 'Saturday'){
                return 'S&aacute;bado';
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
        
        //Gera o calendario mensal em semana, cada semana representa um array de dias
        function retornaCalendario($month, $year, $month_ant, $year_ant){
            $n = $this->getNumberDay($month, $year);
            $primeiroDia = mktime(0,0,0,$month,1,$year);
            $ultimoDia = mktime(0,0,0,$month,$n,$year);
            $diaSem = date('w', $primeiroDia);
            $ultDiaMes = date('w', $ultimoDia);

            $calendario = array(
                0 => array_fill(0, 7, NULL)
            );    

            $semana = 0;
            if($diaSem > 0){
                $dif = $diaSem - 1;
                $QtdDias = $this->getNumberDay($month_ant, $year_ant);
                $NumDias = $QtdDias - $dif;
                for($x = 0; $x < $diaSem; $x++){
                    $calendario[$semana][$x] = $NumDias;
                    $NumDias++;
                }
            }
            for($i = 1; $i <= $n; $i++){
                if ($diaSem >= 7) {
                    $diaSem = 0;
                    $semana++;
                }
                $calendario[$semana][$diaSem] = $i;
                $diaSem++;
            }

                if($ultDiaMes < 6){
                    $x = (int)$ultDiaMes + 1;
                    $d = 1;
                    while($x <= 6){
                        $calendario[$semana][$x] = $d;
                        $x++;
                        $d++;
                    }
                }
            $calendario[$semana] += array_fill($diaSem, 6, NULL);
            return $calendario;
        }
        
        //Retorna o array de dias especifico de uma semana
        function retornaCalendarioSemanal($month, $year, $month_ant, $year_ant, $a){
            $calendario = $this->retornaCalendario($month, $year, $month_ant, $year_ant);
            return $calendario[$a];
        }
        
        //Retorna a semana do dia atual
        function retornaSemanaCalendario($month, $year, $month_ant, $year_ant){
            $hoje = $this->getActualDay();
            $count = count($this->retornaCalendario($month, $year, $month_ant, $year_ant));
            $calendario = $this->retornaCalendario($month, $year, $month_ant, $year_ant);
			for($i = 0; $i <= $count; $i++){
                if(in_array($hoje, $calendario[$i])){
                    return $i;
                }
            }
            /*for($i = 0; $i <= $count; $i++){
                if(($hoje < 20 && $i = 0) || ($hoje > 20 && $i > 1)){
                    if(in_array($hoje, $calendario[$i])){
                        return $i;
                    }
                }
            }*/
        }
        
        //Retorna a semana do dia passado em parametro
        function retornaSemanaDia($month, $year, $month_ant, $year_ant, $dia){
            $this->$dia = $dia;
            $count = count($this->retornaCalendario($month, $year, $month_ant, $year_ant));
            $calendario = $this->retornaCalendario($month, $year, $month_ant, $year_ant);
            for($i = 0; $i <= $count; $i++){
                if(in_array($dia, $calendario[$i])){
                    return $i;
                }
            }
        }
        
        function contaCalendarioSemanal($month, $year, $month_ant, $year_ant){
            //recebe o numero de dia do mes
            $n = $this->getNumberDay($month, $year);
            //Seta primeiro dia do mes
            $primeiroDia = mktime(0,0,0,$month,1,$year);
            //Seta ultimo dia do mes
            $ultimoDia = mktime(0,0,0,$month,$n,$year);
            $diaSem = date('w', $primeiroDia);
            $ultDiaMes = date('w', $ultimoDia);

            $calendario = array(
                0 => array_fill(0, 7, NULL)
            );    

            $semana = 0;
            if($diaSem > 0){
                $dif = $diaSem - 1;
                $QtdDias = $this->getNumberDay($month_ant, $year_ant);
                $NumDias = $QtdDias - $dif;
                for($x = 0; $x < $diaSem; $x++){
                    $calendario[$semana][$x] = $NumDias;
                    $NumDias++;
                }
            }
            for($i = 1; $i <= $n; $i++){
                if ($diaSem >= 7) {
                    $diaSem = 0;
                    $semana++;
                }
                $calendario[$semana][$diaSem] = $i;
                $diaSem++;
            }

                if($ultDiaMes < 6){
                    $x = (int)$ultDiaMes + 1;
                    $d = 1;
                    while($x <= 6){
                        $calendario[$semana][$x] = $d;
                        $x++;
                        $d++;
                    }
                }
            $calendario[$semana] += array_fill($diaSem, 6, NULL);
            return count($calendario);
        }
        
        //Renderiza os botões da agenda, exemplo imprimir, enviar e-mail
        function renderizaBotoes($data, $semana, $diasSem, $month){
            $html = '<div class="btn-toolbar" role="toolbar">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" onclick="location.href=\'./view_agendaSemanal.php\'">Calendario Semanal</button>
                    <button type="button" class="btn btn-default" onclick="location.href=\'./view_agendaGeral.php\'">Calendario Mensal</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" onclick="location.href=\'../../controller/controleAlocacao.php?operacao=imprimir&data='.$data.'&semana='.$semana.'\'">Imprimir Agenda</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" onclick="location.href=\'../../controller/controleAlocacao.php?operacao=enviar&data='.$data.'&semana='.$semana.'&dia='.$diasSem[0].'&mes='.$month.'\'">Enviar Agenda por E-mail</button>
                </div>
            </div>';
            return $html;
        }
        
        //Renderiza os botões da agenda, exemplo imprimir, enviar e-mail
        function renderizaBotoesMensal(){
            $html = '<div class="btn-toolbar" role="toolbar">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" onclick="location.href=\'./view_agendaSemanal.php\'">Calendario Semanal</button>
                    <button type="button" class="btn btn-default" onclick="location.href=\'./view_agendaGeral.php\'">Calendario Mensal</button>
                </div>
            </div>';
            return $html;
        }
        
        //Renderiza a paginacao das agendas
        function renderizaPaginacao($day, $month, $year, $semana, $month_ant, $year_ant, $month_prox, $year_prox, $semanaAnt, $semanaProx){
            $html = '<div class="btn-toolbar" role="toolbar">
                <div class="btn-group" role="group">
                    <ul class="pagination">
                        <li><a href="?data='.$day.'/'.$month_ant.'/'.$year_ant.'&semana=0"><span aria-hidden="true">&laquo;</span><span class="sr-only">Anterior</span></a></li>
                        <li class="disabled"><a href="#">'.$this->getNameMon($month).'-'.$year.'</a></li>
                        <li><a href="?data='.$day.'/'.$month_prox.'/'.$year_prox.'&semana=0"><span aria-hidden="true">&raquo;</span><span class="sr-only">Próximo</span></a></li>';
            if(isset($_GET['data']))
            {
                $html.= '<li><a href="?data='.$_GET['data'].'&semana='.$semanaAnt.'"><span aria-hidden="true">&laquo;</span><span class="sr-only">Anterior</span></a></li>';
                $semanaVis = $semana + 1;
                $html.= '<li class="disabled"><a href="#">Semana '.$semanaVis.'</a></li>';
                $html.= '<li><a href="?data='.$_GET['data'].'&semana='.$semanaProx.'"><span aria-hidden="true">&raquo;</span><span class="sr-only">Próximo</span></a></li>';
            }else{
                $html.= '<li><a href="?semana='.$semanaAnt.'"><span aria-hidden="true">&laquo;</span><span class="sr-only">Anterior</span></a></li>';
                $semanaVis = $semana + 1;
                $html.= '<li class="disabled"><a href="#">Semana '.$semanaVis.'</a></li>';
                $html.= '<li><a href="?semana='.$semanaProx.'"><span aria-hidden="true">&raquo;</span><span class="sr-only">Próximo</span></a></li>';
            }
            $html.= '</ul>
            </div>
            <div style="text-align: right; margin-right: 30px">
                <img src="http://agenda.gestao.com.br/PHPMailer/img/logo_joinville.jpg" alt="Logo Gestão" style="max-width:200px;">
                <img src="http://agenda.gestao.com.br/PHPMailer/img/indice.png" alt="Logo Gestão" style="max-width:200px;">
            </div>
            </div>';
        return $html;
        }
        
        function renderizaPaginacaoMensal($day, $month, $year, $month_ant, $year_ant, $month_prox, $year_prox){
            $html= '<div class="btn-toolbar" role="toolbar">
                <div class="btn-group" role="group">
                    <ul class="pagination">
                        <li><a href="?data='.$day.'/'.$month_ant.'/'.$year_ant.'"><span aria-hidden="true">&laquo;</span><span class="sr-only">Anterior</span></a></li>
                        <li class="disabled"><a href="#">'.$this->getNameMon($month).'-'.$year.'</a></li>
                        <li><a href="?data='.$day.'/'.$month_prox.'/'.$year_prox.'"><span aria-hidden="true">&raquo;</span><span class="sr-only">Próximo</span></a></li>
                    </ul>
                </div>
                <div style="text-align: right; margin-right: 30px">
                    <img src="http://agenda.gestao.com.br/PHPMailer/img/logo_joinville.jpg" alt="Logo Gestão" style="max-width:200px;">    
                    <img src="http://agenda.gestao.com.br/PHPMailer/img/indice.png" alt="Logo Gestão" style="max-width:200px;">
                </div>
            </div>';
            return $html;
        }
                
        function renderizaDiasSemana($semana, $diasSem, $month, $month_ant, $month_prox){
            $f = 0;
            $html= "";
            while($f <= 6){
                if(($semana == 0) && ($diasSem[$f] > 7)){
                    $html.= '<td class="text-center destaque"><strong>'.$diasSem[$f].'-'.$this->getNameMon($month_ant).'</strong></td>';
                }else if(($semana == 4) && ($diasSem[$f] < 7)){
                    $html.= '<td class="text-center destaque"><strong>'.$diasSem[$f].'-'.$this->getNameMon($month_prox).'</strong></td>';
                }else{
                    $html.= '<td class="text-center destaque"><strong>'.$diasSem[$f].'-'.$this->getNameMon($month).'</strong></td>';
                }
                $f++;
            }
            return $html;
        }
        
        function renderizaDiaExtenso($semana, $diasSem, $month, $year, $month_ant, $month_prox, $year_ant, $year_prox){
            $f = 0;
            $html= "";
            while($f <= 6){
                if(($semana == 0) && ($diasSem[$f] > 7)){
                    $dayExtensive = $this->getDayExtensive($month_ant, $diasSem[$f], $year_ant);
                }else if(($semana == 4) && ($diasSem[$f] < 7)){
                    $dayExtensive = $this->getDayExtensive($month_prox, $diasSem[$f], $year_prox);
                }else{
                    $dayExtensive = $this->getDayExtensive($month, $diasSem[$f], $year);
                }
                    $html.='<td class="text-center destaque"><strong>'.$dayExtensive.'</strong></td>';   
                $f++;
            }
            return $html;
        }
        
        function retornaDataFormatada($f, $semana, $diasSem, $month, $year, $month_ant, $month_prox, $year_ant, $year_prox){
            if(($semana == 0) && ($diasSem[$f] > 7)){
                $data = date('Y-m-d', mktime(0,0,0,$month_ant,$diasSem[$f],$year_ant));
            }else if(($semana == 4) && ($diasSem[$f] < 7)){
                $data = date('Y-m-d', mktime(0,0,0,$month_prox,$diasSem[$f],$year_prox));
            }else{
                $data = date('Y-m-d', mktime(0,0,0,$month,$diasSem[$f],$year));
            }
            return $data;
        }
        
        function renderizaAlocacaoBloqueada($objAlocacao, $semana){
            $html= '<td class="text-center bloqueado">';
            if($_SESSION['perfil'] == 'A' || $_SESSION['perfil'] == 'P'){
                $html.= '<a data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                        . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'&semana='.$semana.'" data-target="#myModal">
                            &nbsp;</a>';
            }
            $html.='</td>';
            return $html;
        }
        
        function renderizaAlocacaoBloqueadaMensal($objAlocacao){
            $html= '<td class="text-center bloqueado">';
            if($_SESSION['perfil'] == 'A' || $_SESSION['perfil'] == 'P'){
                $html.= '<a data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                        . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'" data-target="#myModal">
                            &nbsp;</a>';
            }
            $html.='</td>';
            return $html;
        }
        
        function renderizaAlocacaoFolga($objAlocacao, $objCliente, $objTipoAlocacao, $semana){
            $html= '<td class="text-center folga">';
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'&semana='.$semana.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            $html.= '</td>';
            return $html;
        }
        
        function renderizaAlocacaoFolgaMensal($objAlocacao, $objCliente, $objTipoAlocacao){
            $html= '<td class="text-center folga">';
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            $html.= '</td>';
            return $html;
        }
        
        function renderizaAlocacaoFeriado($objAlocacao, $objCliente, $objTipoAlocacao, $semana){
            $html= '<td class="text-center feriado">';
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'&semana='.$semana.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            $html.= '</td>';
            return $html;
        }
        
        function renderizaAlocacaoFeriadoMensal($objAlocacao, $objCliente, $objTipoAlocacao){
            $html= '<td class="text-center feriado">';
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            $html.= '</td>';
            return $html;
        }
        
        function renderizaAlocacaoFerias($objAlocacao, $objCliente, $objTipoAlocacao, $semana){
            $html= '<td class="text-center ferias">';
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'&semana='.$semana.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            $html.= '</td>';
            return $html;
        }
        
        function renderizaAlocacaoFeriasMensal($objAlocacao, $objCliente, $objTipoAlocacao){
            $html= '<td class="text-center ferias">';
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            $html.= '</td>';
            return $html;
        }
        
        function renderizaAlocacao($preenchimento, $objAlocacao, $objCliente, $objTipoAlocacao, $semana){
            if($preenchimento == "0"){
                $html= '<td class="text-center">';
            }else{
                $html= '<td class="text-center destaque">';
            }
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'&semana='.$semana.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            $html.= '</td>';
            return $html;
        }
        
        function renderizaAlocacaoMensal($preenchimento, $objAlocacao, $objCliente, $objTipoAlocacao){
            if($preenchimento == "0"){
                $html= '<td class="text-center">';
            }else{
                $html= '<td class="text-center destaque">';
            }
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            $html.= '</td>';
            return $html;
        }
        
        function renderizaAlocacaoMult($objAlocacao, $objCliente, $objTipoAlocacao, $semana){
            $html= '';
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'&semana='.$semana.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            return $html;
        }
        
        function renderizaAlocacaoMultMensal($objAlocacao, $objCliente, $objTipoAlocacao){
            $html= '';
            if($objAlocacao->confirmado == 'S'){
                    $html.= '<a class="confirmado"'; 
                }else if($objAlocacao->confirmado == 'N'){
                    $html.= '<a class="nconfirmado"'; 
                }
            $html.= ' data-toggle="modal" href="modalAgenda.php?&data='.$objAlocacao->dataAlocacao.'&idColaborador='.$objAlocacao->idColaborador.''
                    . '&horaIni='.$objAlocacao->horaInicio.'&horaFim='.$objAlocacao->horaFim.'" data-target="#myModal">';
            $html.= '<div class="show-tooltip" title="'.$objAlocacao->desAlocacao.'">';
            if($objAlocacao->idCliente == '152'){
                    $html.= $objTipoAlocacao->desAloc;
                }else{
                    $html.= $objCliente->nomeFantasia.' - '.$objTipoAlocacao->desAloc;
                }
            $html.= '</div></a>';
            return $html;
        }
        
        function renderizaSemAlocacao($diasSem, $f, $preenchimento, $usuario,
                $month, $year, $month_ant, $month_prox, $year_ant, $year_prox, $semana, $dataAloc){
            if(($semana == 0) && ($diasSem[$f] > 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_ant, $diasSem[$f], $year_ant));
            }else if(($semana == 4) && ($diasSem[$f] < 7)){
                $dayOfWeek = date('l', mktime(0,0,0,$month_prox, $diasSem[$f], $year_prox));
            }else{
                $dayOfWeek = date('l', mktime(0,0,0,$month, $diasSem[$f], $year));
            }
            
            $html = '';
            //Imprime sabado e domingo em destaque
            if(($dayOfWeek == 'Saturday') || ($dayOfWeek == 'Sunday')){
                $html.= '<td class="text-center destaque">&nbsp;</td>';
            }else if($preenchimento == "0"){
                $html.= '<td class="text-center">';
                //chama o modal
                if($_SESSION['perfil'] == 'A' || $_SESSION['perfil'] == 'P'){
                    $html.= '<a data-toggle="modal" href="modal_criar_aloc.php?data='.$dataAloc.'&semana='.$semana.'&idColaborador='.$usuario->idUsuario.'" data-target="#myModal">';
                }
                $html.= '&nbsp;</td>';
            }else{
                $html.= "<td class='text-center destaque'>";
                //chama o modal
                if($_SESSION['perfil'] == 'A' || $_SESSION['perfil'] == 'P'){
                    $html.= '<a data-toggle="modal" href="modal_criar_aloc.php?data='.$dataAloc.'&semana='.$semana.'&idColaborador='.$usuario->idUsuario.'" data-target="#myModal">';
                }
                $html.= '&nbsp;</td>';
            }
        return $html;    
        }
        
        function renderizaSemAlocacaoMensal($day, $month, $year, $dataAloc, $preenchimento, $usuario){
            $dayOfWeek = date('l', mktime(0,0,0,$month, $day, $year));
                    
            //Imprime sabado e domingo em destaque
            if(($dayOfWeek == 'Saturday') || ($dayOfWeek == 'Sunday')){
                $html = "<td class='text-center destaque'>&nbsp;</td>";
            }else if($preenchimento == "1"){
                $html = "<td class='text-center destaque'>";
                if($_SESSION['perfil'] == 'A' || $_SESSION['perfil'] == 'P'){
                //chama o modal
                    $html.= "<a data-toggle=\"modal\" href=\"modal_criar_aloc.php?data=$dataAloc&idColaborador=$usuario->idUsuario\" data-target=\"#myModal\">";
                }
                $html.= "&nbsp;</td>";
            }else{
                $html= "<td class='text-center'>";
                //chama o modal
                if($_SESSION['perfil'] == 'A' || $_SESSION['perfil'] == 'P'){
                    $html.= "<a data-toggle=\"modal\" href=\"modal_criar_aloc.php?data=$dataAloc&idColaborador=$usuario->idUsuario\" data-target=\"#myModal\">";
                }
                $html.= "&nbsp;</td>";
            }
            return $html;
        }
    }
?>

