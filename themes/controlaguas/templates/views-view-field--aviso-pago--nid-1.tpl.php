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

//  dpm($row);

global $totalmes;
 $lectura = node_load($output);
 $medidor = $row->field_field_medidor[0]['raw']['target_id'];
 $mesini = date('Y-m-01',strtotime($lectura->field_mes['und'][0]['value']));
 $mesfin = date('Y-m-t',strtotime($lectura->field_mes['und'][0]['value']));

 $pagosIndividuales =  EntitiesData::getDatas('node', 'multa', "medidor(direccion) monto monto_pagado motivo fecha plazo", "monto>0,monto_pagado=0,medidor=$medidor,fecha>=$mesini,fecha<=$mesfin");

 $pagosMensuales = EntitiesData::getDatas('node', 'porpagar', "concepto monto periodicidad fecha", "activo=1,monto>0,periodicidad=Mensual");




$campos="";
$txt="";
$extras = 0;

 foreach ($pagosMensuales AS $mensual){
   $txt .="<div class= 'views-field mensuales'>";
   $txt .="<spam class = 'views-label'>". $mensual['concepto'] ."</spam>";
   $txt .="<div class = 'field-content'>". $mensual['monto'] ." Bs.</div>";
   $txt .="</div>";
   $extras += $mensual['monto']; 
 }


 foreach ($pagosIndividuales AS $pagos){

   $txt .="<div class= 'views-field individuales'>";
   $txt .="<spam class = 'views-label'>". $pagos['motivo'] ."</spam>";
   $txt .="<div class = 'field-content'>". $pagos['monto'] ." Bs.</div>";
   $txt .="</div>";
   $extras += $pagos['monto']; 
 }

$totalmes = $row->field_field_monto[0]['raw']['value'] + $extras;
 $campos .= $txt;
 $campos .="<div class= 'views-field total'>";
 $campos .="<spam class = 'views-label'>Total:</spam>";
 $campos .="<div class = 'field-content'>";
 $campos .=   $totalmes . " Bs.";
 $campos .="</div>";
 $campos .="</div>"; 
print $campos; 
?>
