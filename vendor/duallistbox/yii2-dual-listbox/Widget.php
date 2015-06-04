<?php

namespace duallistbox\duallistbox;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * This is just an example.
 */
class Widget extends InputWidget
{

    public $nametitle;
//    public $title;
//    public $lngOptions;
    public $options;
    public $attributes;
    public $data;
    public $data_id;
    public $data_value;

    public function init()
    {
        parent::init();

        $this->data_id = isset($this->data_id) ? $this->data_id : 'id';
        $this->data_value = isset($this->data_value) ? $this->data_value : 'name';

        $this->nametitle = isset($this->nametitle) ? $this->nametitle : '';
        $this->registerAssets();
    }

    public function run()
    {
        $view = $this->getView();
        $inputId = $this->attribute;
        $selected = \yii\helpers\Json::decode($this->model->$inputId, JSON_UNESCAPED_UNICODE);
        $selected = ($selected == null) ? [] : $selected;
        $this->attributes = $this->model->attributes();

        $data = ($this->data) ? $this->data->asArray()->all() : [];

        echo '<div id="'.$inputId.'" >';

        $ret = '<select name="'.$this->model->formName().'['.$this->attribute.'][]" style="display: none;" multiple = "multiple">';

        foreach ($data as $key => $value) {

            if (!in_array($value[$this->data_id], $selected)) {
                $ret .= '<option value="' . $value[$this->data_id] . '">' . $value[$this->data_value] . '</option>' . "\n";
            } else {
                $ret .= '<option value="' . $value[$this->data_id] . '" selected="selected">' . $value[$this->data_value] . '</option>' . "\n";
            }

        }
        $ret .= '</select>';

        $options = '';
        foreach($this->options as $key => $value) {
            $options .= $key . ': ' . '"' . $value . '",';
        }


        $js = <<<SCRIPT

            $('#$inputId').bootstrapDualListbox({
                $options
            });
SCRIPT;

        $view->registerJs($js);

        return $ret.'</div>';
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        Asset::register($view);
//
//        $attr = $this->attribute;
//        $js = <<<SCRIPT
//
//SCRIPT;
//
//        $view->registerJs($js);
    }
}