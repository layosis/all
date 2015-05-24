<?php
/**
 * @file
 * EntitiesData class.
  */


class EntitiesData {
  
  /**
   * Implements getDatas().
   * Return entities query.
   *
   * @param entity_type
   *   node, or other 
   * @param bundle
   *   article or other
   * param fields
   *  string of fields such as system name (lowercase) separated by single space " "
   *  eg (field_name_1 field_name_2 ... field_name_n)
   *  eg (field_name_1 field_name_2 ... entityName(field_name_5 field_name_6 ...) ...field_name_n) 
   * @param conditions
   *   string of conditions such as sql condition, separated by comma (,)
  */
  public static function getDatas($entity_type, $bundle, $fields, $conditions) {
    $query = new EntityFieldQuery();
    
    if($conditions != ""){
      
      $conditions = explode(",", $conditions);
      
      foreach ($conditions AS $condition) {
        $value = "value";
        
        if (strpos($condition, '!=') !== FALSE) {
          $elements = explode("!=", $condition);
          $operator =  "!=";
        }else if (strpos($condition, '>=') !== FALSE) {
          $elements = explode(">=", $condition);
          $operator =  ">=";
        }else if (strpos($condition, '<=') !== FALSE) {
          $elements = explode("<=", $condition);
          $operator =  "<=";
        }else if (strpos($condition, '>') !== FALSE) {
          $elements = explode(">", $condition);
          $operator =  ">";
        }else if (strpos($condition, '<') !== FALSE) {
          $elements = explode("<", $condition);
          $operator =  "<";
        }else if (strpos($condition, '=') !== FALSE) {
          $elements = explode("=", $condition);
          $operator =  "=";
        }
        $fieldName =  $elements[0];
        $fieldValue = $elements[1];
        
        if($elements[1] == 'NULL'){
          $value = 'value';
          if($fieldName=="medidor"){
            $fieldConditions .= "->fieldCondition('field_$fieldName', 'target_id', '$fieldValue', '$operator')";
          }else
            $fieldConditions .= "->fieldCondition('field_$fieldName', '$value', '$fieldValue', '$operator')";
        }else {
          if($fieldName=="medidor") {
            $fieldConditions .= "->fieldCondition('field_$fieldName', 'target_id', '$fieldValue', '$operator')";
          } else
            $fieldConditions .= "->fieldCondition('field_$fieldName', '$value', '$fieldValue', '$operator')";
        }
        
      }//end for conditions
    }//end conditions
    
    $composition = '$query->entityCondition' . "('entity_type', '$entity_type')
    ->entityCondition('bundle', '$bundle')
    $fieldConditions
    ->execute();
    ";
    
    eval("\$entitiesInstances=$composition;");
    
    if (sizeof($entitiesInstances) > 0) {
      $entities = entity_load($entity_type, array_keys($entitiesInstances[$entity_type]));
      
      $fields = explode(" ", $fields);
      foreach ($entities as $entity) {
        $fieldData = array();
        
        $fieldData['nid'] = $entity->vid;
        
        foreach ($fields AS $field) {
          $posIni = strpos($field, '('); 
          if ( $posIni > 0 ) { //entitie
            $posEnd = strpos($field, ')');
            $subFields = substr($field, $posIni+1, $posEnd-$posIni-1);
            $field = substr($field, 0, $posIni); 
          }
          
          eval("\$fieldData['$field']=" . '$entity->field_' . $field . "[LANGUAGE_NONE][0][value];");
          
          if ($fieldData["$field"] == NULL) {
            eval("\$fieldData['$field']=" . '$entity->field_' . $field . "[und];");
            
            $areEntities = FALSE;
            if ($fieldData["$field"] != NULL) { //Entities subfields
              $areEntities = TRUE;
              $subFields = explode(",", $subFields);
              foreach ($fieldData["$field"] As $row) {
                $id = $row['target_id'];
                $type = $field;
                $object[$id] = (object)array( 'nid' => $id, 'vid' => $id, 'type' => "$type");
                $subEntity = entity_load($entity_type, array_keys($object));
                $subEntity = $subEntity[$id];
                $subFieldData['nid'] = $subEntity->vid;
                
                foreach ($subFields AS $subField) {
                  eval("\$subFieldData['$subField']['$subEntity->title']=" . '$subEntity->field_' . $subField . "[LANGUAGE_NONE][0][value];");
                }
                
                $object =  array();
              }              

            }
            
            if ($areEntities) {
              $fieldData["$field"] = $subFieldData;
              $subFieldData = array();
            }
            
          }
        
        }
        $datas[] = $fieldData;
      }
    
    }
    else {
      $datas = NULL;
    }
      
    return $datas;
  }

  /**
   * Implements setDatas().
   * Set datas in a entities.
   *
   * @param entity_type
   *   node, or other
   * @param entity_id
   *   id of entity
   * param fields
   *  string of fields such as system name (lowercase) separated by ","
   * @param fields_values
   *  string of conditions such as sql condition, separated by comma (,)
   *  if a field value is a copy of other field value set "field_" prefix
   */
  public static function setDatas($entity_type, $entity_id, $fields, $fields_values) {
    if($entity_type == "node") {
      $node = node_load($entity_id);
      
      $fields = explode(",", $fields);
      $fieldValues = explode(",", $fields_values);
      
      for ($i = 0; $i < count($fields); $i++) {
        
        $pos = strpos($fieldValues[$i], '_');
        
        if ($pos > 0) { // is a field
          $fieldValue = substr($fieldValues[$i], $pos+1, strlen($fieldValues[$i]));
          eval("\$node->field_" . $fields[$i] ."['und'][0]['value'] = \$node->field_" . $fieldValue . "['und'][0]['value'];");          
        }else {
          eval("\$node->field_" . $fields[$i] ."['und'][0]['value'] = '" . $fieldValues[$i] . "';");
        }        
      }
      node_save($node);
    }
  }
}