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
?>
<?php 

    $idpersona = db_query("select entity_id
    from field_data_field_nombres nom
    where nom.entity_id = (
    SELECT `entity_id` FROM `field_data_field_medidor` WHERE bundle = 'persona' and `field_medidor_target_id` = (
    SELECT `field_medidor_target_id` FROM `field_data_field_medidor` WHERE `entity_id` = :id))", array(':id' => $output))->fetchField();

    $persona = node_load($idpersona);
    $nombres = $persona->field_nombres['und'][0]['value']." ".$persona->field_apellido_paterno['und'][0]['value']." ".$persona->field_apellido_materno['und'][0]['value'];
    print $nombres; 


?>
