<?php
namespace Modules\Forms;


class DbFormService extends FormService {

    public $excludeFields = []; 

    function exclude($fields) {
        $this->excludeFields = $fields;
        return $this;
    }

    function formForTable($tableName) {
        
        $form = '<div class="row">';
        $q = \DB::table('eds_fields')->where('table','=', $tableName);
        if(!empty($this->excludeFields)) {
            foreach($this->excludeFields as $field) {
                $q = $q->where('field', "!=", $field);
            }
            
        }

        $fields = $q->orderBy('sort_order')->get();
//        dd($fields);
        if($fields->isEmpty()) {
            $form .= "<p>No fields Defined for table $tableName</p>";
        }
        $form .= $this->start();
        foreach($fields as $field) {

            $config['errorFieldPrefix'] = 'home.errors';

            if(!in_array($field->form_input, $this->inputTypeWithValues)) {

                $form .= $this->field($field->form_input, $field->field)
                                ->label($field->label)
                                ->r($config);

            } else {

                $form .= $this->renderInputWithValuesField($field,  $tableName, $config);

            }

        }
        
        $form .= $this->end();
        $form .= "</div>";
        return $form;

        

    }

    function renderInputWithValuesField($field, $tableName, $config) {

        if(\Schema::hasTable($field->link_table)) {
            if(!\Schema::hasColumn($field->link_table, $field->link_field)) {
                die("link table $field->link_table does not have fk column <b>$field->link_field</b>");    
            }
            if(!\Schema::hasColumn($field->link_table, $field->link_ui_label_field)) {
                die("link table $field->link_table does not have display field <b>$field->link_ui_label_field</b>");    
            }
        } else {
            die("link table $field->link_table not found");
        }
        return $this->field($field->form_input, $field->field)
                    ->label($field->label)
                    ->fromDb(
                        $field->link_table, 
                        $field->link_ui_label_field,
                        $field->link_field)->r($config);
    }

    function field($fieldType, $fieldName) {
        
        
        
        if(isset($this->config['label'])) {
            unset($this->config['label']);
        }
        if(isset($this->config['cssClass'])) {
            unset($this->config['cssClass']);
        }
        
        $this->labelFieldDimension();
        
        if(!in_array($fieldType, array_merge($this->inputTypes, $this->inputTypeWithValues))) {
            
            var_dump($this->inputTypes);
            
            dd("function ".$fieldType." does not exist;");
        }
        
        
        $this->config['type'] = $fieldType;
        $this->config['name'] = $fieldName;
        
        if(!in_array($fieldType, $this->inputTypeWithValues)) {
            
            $this->config['values'] = ["val1", "val2"];
        }
        
        return $this;
    }

}