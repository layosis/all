<?php

//   *************     RETRASO  **************************

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
    global $i;
    global $list;

    $my_date = new DateTime(date('Y-m-d H:i:s'));
    $medidor = $row->field_field_medidor[0]['raw']['entity']->nid;
    $activo = "";
    $valor="";
    $hoy = (isset($_POST['fecha']))? $_POST['fecha'] : date('Y-m-d');
    
    $i = ($i == "")? $i=0 : $i = $i; 
    $act = "";

    $idpago=0;
    $sql = "select me.entity_id
    from field_data_field_medidor me, field_data_field_fecha fe, field_data_field_motivo mo
    where me.entity_id = fe.entity_id and me.bundle = 'multa' and me.entity_id = mo.entity_id
    and me.field_medidor_target_id = ".$medidor." and  fe.field_fecha_value like '".$hoy."%' 
    and mo.field_motivo_value like 'Retraso a asamblea en fecha:%'";


    $clase="";
    $idpago = db_query($sql)->fetchField();

    $disabled = '';
    if(isset($_POST['fecha'])){  //  si se presiona el boton guardar
        if(isset($_POST['controlretraso'][$i]) && $_POST['controlretraso'][$i] == $output) {  // si es cheket (retraso)
            $act = ' checked';
            $disabled = ' disabled';
            if($idpago > 0){}  // si tiene registro de falta o retraso
            else{
                $node = new stdClass();
                $node->type = 'multa';
                $node->title = 1;
                $node->language = LANGUAGE_NONE; 
                $node->uid = $user->uid; 
                $node->status = 1; 
                $node->promote = 0; 
                $node->comment = 0;
                $node->field_medidor['und'][0]['target_id'] = $medidor;
                $node->field_porpagar['und'][0]['target_id'] =  5894;
                $node->field_monto['und'][0]['value'] = $_SESSION['retraso'];
                $node->field_motivo['und'][0]['value'] = 'Retraso a asamblea en fecha: ' . $_POST['fecha'];
                $node->field_fecha['und'][0]['value'] = date('Y-m-d H:i:s');
                $node = node_submit($node);
                node_save($node);                                
            }
        } 
    } else {  // si se cargo por primera vez la pagina
        if($idpago > 0){  // si tiene registro de falta o retraso
            $act = " checked";
            $disabled = ' disabled';
        } else {
            $act = "";
            $disabled = '';
        }
    } 
    
    $chec = '<div class="div-form-checkbox '.$clase.'"><input type="checkbox" id="edit-controlretraso-'.$output.'" name="controlretraso['.$i.']" value="'.$output.'" class="form-checkbox"'.$act. $disabled .'></div>';
    $i++;
    print $chec;
?>
