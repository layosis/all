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

$mesactual = date("Y-m-d", strtotime($node->field_mes['und'][0]['value']));

$lecturas = EntitiesData::getDatas(
   'node',
   'lectura', 
   "monto monto_excedente mes", 
   "monto_pagado=0,medidor=$ids,monto>0,mes<$mesactual");

$monto=0;
$mesesdeuda=1;
foreach ($lecturas AS $rows){
   $monto += $rows['monto'];
   $mesesdeuda +=1;
}

$pagosMensuales = EntitiesData::getDatas('node', 'porpagar', "monto fecha", 
                                          "activo=1,monto>0,periodicidad=Mensual,fecha<$mesactual");
foreach ($pagosMensuales AS $rows){
   $monto += $rows['monto'];
}






$numFac = "<div id='deuda'><div class ='numfacturas'>Nummero de facturas que adeuda : ". $mesesdeuda ."</div>";
$numFac .= "<div class ='numfacturas'>Importe total que adeuda : ". ($totalmes + $monto) ."</div></div>";

print $numFac; 
?>