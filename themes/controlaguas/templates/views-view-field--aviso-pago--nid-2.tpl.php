<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */

global $totalmes;
$node = node_load($output);
$ids = $node->field_medidor['und'][0]['target_id'];

$datestr = strtotime($node->field_mes['und'][0]['value']);
$mesactual = date("Y-m-d", $datestr);
$mesini = date('Y-m-d',mktime(0, 0, 0, (date("m", $datestr) - 1)  , 1, date("Y", $datestr)));

$multa=0;
$dias=0;
$multaEntity = EntitiesData::getDatas('node', 'porpagar', "monto limite_dias", 
                                          "activo=1,monto>0,periodicidad=incumplirPago,fecha<$mesactual");

foreach ($multaEntity AS $rows){
   $multa = $rows['monto'];
   $dias = $rows['limite_dias'];
}

/*   Meses adeudados
     --------------- */
$lecturas = EntitiesData::getDatas(
   'node',
   'lectura', 
   "monto monto_excedente fecha_lectura mes", 
   "monto_pagado=0,medidor=$ids,monto>0,mes<$mesactual");

$montodeuda=0;
$mesesdeuda=0;
foreach ($lecturas AS $rows){
   $montodeuda += $rows['monto'];
   $montodeuda += $multa;
   $mesesdeuda +=1;
}

/*   Pagos mensuales
     --------------- */
$pagosMensuales = EntitiesData::getDatas('node', 'porpagar', "monto fecha", 
                                          "activo=1,monto>0,periodicidad=Mensual,fecha<$mesactual");
$porpagar = 0;
foreach ($pagosMensuales AS $rows){
   $porpagar += $rows['monto'];
}

$porpagar = $mesesdeuda * $porpagar;

/*   Pagos individuales
     ------------------ */
$pagosInd = 0;
$pagosIndividuales =  EntitiesData::getDatas('node', 'multa', "monto monto_pagado", "monto>0,monto_pagado=0,medidor=$ids,fecha>=$mesini,fecha<$mesactual");
foreach ($pagosIndividuales AS $rows){
   $pagosInd += $rows['monto'];
}


$res = db_query("select MONTH(mo.field_mes_value) mes,YEAR(mo.field_mes_value) anio, (lac.field_lectura_actual_value - lan.field_lectura_anterior_value) consumo 
from field_data_field_medidor m 
inner join field_data_field_lectura_anterior lan using(entity_id)
inner join field_data_field_lectura_actual lac using(entity_id)
inner join field_data_field_mes mo using(entity_id)
where m.bundle='lectura'
and m.field_medidor_target_id = :id
and mo.field_mes_value < :date
order by mo.field_mes_value desc limit 4", array(':id' => $ids, ':date'=> $mesactual));

$datos ="<div id='historicos'><div class='subtitle'><span>Consumo Historico</span></div>";
foreach ($res as $rows) {
   $mesL = "";
   switch ($rows->mes) {
      case 1: $mesL = 'Enero'; break;
      case 2: $mesL = 'Febrero'; break;
      case 3: $mesL = 'Marzo'; break;
      case 4: $mesL = 'Abril'; break;
      case 5: $mesL = 'Mayo'; break;
      case 6: $mesL = 'Junio'; break;
      case 7: $mesL = 'Julio'; break;
      case 8: $mesL = 'Agosto'; break;
      case 9: $mesL = 'Septiembre'; break;
      case 10: $mesL = 'Octubre'; break;
      case 11: $mesL = 'Noviembre'; break;
      case 12: $mesL = 'Diciembre'; break;
   }



   $datos .= "<div class='mes-historico'>
   <span class='historico-label'>". $mesL . "-".$rows->anio.":</span>    
   <span class='historico-content'>". $rows->consumo ." m3</span></div>";
}
$datos .= "</div>";



$subtotal = $montodeuda + $porpagar + $pagosInd;



$numFac = "<div id='deuda'><div class ='numfacturas'>Número de facturas que adeuda : ". $mesesdeuda ."</div>";
$numFac .= "<div class ='numfacturas'>Importe que adeuda : ". $subtotal ."</div>";
$numFac .= "<div class ='numfacturas'>Total deuda : ". ($subtotal + $totalmes) ."</div></div>";

$numFac .= $datos;

print $numFac; 
?>