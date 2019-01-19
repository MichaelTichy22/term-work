<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 9.10.18
 * Time: 8:59
 */

abstract class Form
{
    protected $name;
    protected $method;
    protected $action;
    protected $enctype;
    protected $elements;
    protected $messages;

    public function __construct($name, $method, $action, $enctype = null)
    {
        $this->name = $name;
        $this->method = $method;
        $this->action = $action;
        $this->enctype = $enctype;
    }

    abstract function build(DatabaseManager $db = null);

    /**
     * @param $name
     * @param $label
     * @param $tag
     * @param array $options
     */
    public function addElement($name, $label, $tag, $options, $value = null)
    {
        if (empty($value)){
            $value = '';
        }
        $element = ['name' =>  $name, 'label'=> $label, 'tag'=> $tag, 'options'=>$options, 'value'=>$value];

        $this->elements[] = $element;
    }

    public function renderForm()
    {
        require './Views/Form/form.phtml';
    }

    /**
     * @param $entities
     * @param $keyValue
     * @param $keyName
     * @return array
     */
    protected function getSelectOptions($entities, $keyValue, $keyName, $nullValue = false)
    {
        $options = [];

        if ($nullValue) {
            $option = ['value'=> null, 'name' => '--nevybráno--', 'selected'=>''];
            $options[]=$option;
        }

        foreach ($entities as $entity){
            $option = ['value'=> '', 'name' => '', 'selected'=>''];
            $option['value'] = $entity[$keyValue];
            $option['name'] = $entity[$keyName];
            $options[]=$option;
        }

        return $options;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $validator = new Validator();
        $result = true;
        foreach ($this->elements as $element){
            if (array_key_exists('constraints',$element['options'])){
                if(!$validator->validate($element['value'],$element['options']['constraints'], $element['label'])){
                    $result = false;
                }
            }
        }
        $this->messages = $validator->getMessages();

        return $result;
    }

    /**
     * @param $values
     */
    public function setValues($values){
        $i = 0;
        $newElements = [];

        foreach($this->elements as $element){

            if ($element['tag'] === 'input'){
                if ($element['options']['type'] === 'submit') {
                    $newElements[] = $element;
                    continue;
                }

                if ($element['options']['type'] === 'checkbox') {
                    if (isset($values['checkboxes'])) {
                        foreach ($values['checkboxes'] as $checkVal) {
                            if ($element['value'] === $checkVal) {
                                $element['options']['checked'] = true;
                            }
                        }
                    }
                }
            }

            if($element['tag'] === 'select'){
                $newOptions = [];
                foreach ($element['options']['options'] as $option) {
                    if($option['value'] === $values[$i]){
                        $option['selected'] = 'selected';
                    }
                    $newOptions[] = $option;
                }
                $element['options']['options'] = $newOptions;
            }

            if ($element['options']['type'] !== 'checkbox') {
                $element['value'] = $values[$i];
            }


            $newElements[] = $element;
            $i++;
        }
        $this->elements = $newElements;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return null
     */
    public function getEnctype()
    {
        return $this->enctype;
    }


}