<?php

namespace fsm\dualistbox;

use backend\components\Logger;
use yii\widgets\InputWidget;

class DualListbox extends InputWidget
{
    public $items = [];

    public function init()
    {
        parent::init();
        $this->registerAssets();
    }

    public function run()
    {
        $view = $this->getView();
        $inputId = $this->attribute;
        $selectedValues = $this->model->$inputId;
        $id = static::getId();

        $sel = $this->renderSelect($selectedValues, $id);
        $options = '';
        foreach($this->options as $key => $value) {
            $options .= $key . ': ' . '"' . $value . '",';
        }

        $js = <<<SCRIPT

            $('#$id').bootstrapDualListbox({
                $options
            });
SCRIPT;

        $view->registerJs($js);

        return $sel;
    }

    public function renderSelect($selectedValues, $id)
    {
        $ret = '<select id="' . $id . '" name="'.$this->model->formName().'['.$this->attribute.'][]" style="display: none" multiple = "multiple">';

        foreach ($this->items as $keys => $values) {
            if (empty($values)) continue;
            if (!is_array($values)) {
                $ret .= '<option value="' . $values . '">' . $values . '</option>' . "\n";
            } else {
                $ret .= '<optgroup label="' . $keys . '">';
                foreach ($values as $key => $value) {
                    if ($this->search_in_array($value, $selectedValues)) {
                        $ret .= '<option value="' . $value . '" selected="selected">' . $value . '</option>' . "\n";
                    } else {
                        $ret .= '<option value="' . $value . '">' . $value . '</option>' . "\n";
                    }
                }
                $ret .= "</optgroup>";
            }
        }

        $ret .= '</select>';

        return $ret;
    }

    function search_in_array($value, $array) {
        if(in_array($value, $array)) {
            return true;
        }
        foreach($array as $item) {
            if(is_array($item) && $this->search_in_array($value, $item))
                return true;
        }
        return false;
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        DualListboxAsset::register($view);
    }
}

?>