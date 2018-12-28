<?php

class Validator
{
    private $messages = [];

    /**
     * @param $element
     * @param $constraints
     * @param $name
     * @return bool
     */
    public function validate($element, $constraints, $name){
        $result = true;

        foreach ($constraints as $constraint){
            if ($this->$constraint($element,$name)==false){
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param $element
     * @param $name
     * @return bool
     */
    private function notBlank($element, $name){
        if (preg_replace('/\s+/', '', $element) === ''){
            $this->messages [] = "Pole \"$name\" nesmí být prázdné";
            return false;
        }
        return true;
    }

    /**
     * @param $element
     * @param $name
     * @return bool
     */
    private function notNull($element, $name){
        if ($element == null){
            $this->messages[] = "Pole \"$name\" nesmí být NULL";
            return false;
        }
        return true;
    }

    /**
     * @param $element
     * @param $name
     * @return bool
     */
    private function isEmail($element, $name){
        if (!filter_var($element, FILTER_VALIDATE_EMAIL)){
            $this->messages[] = "Není validní email!";
            return false;
        }
        return true;
    }

    /**
     * @param $element
     * @param $name
     * @return bool
     */
    private function noSpecialChars($element, $name){
        if (!preg_match("/^[a-zA-Z0-9]*$/",$element)){
            $this->messages[] = "Pole \"$name\" může obsahovat pouze písmena a číslice!";
            return false;
        }
        return true;
    }

    /**
     * @param $element
     * @param $name
     * @return bool
     */
    private function shorterThan32($element, $name){
        if (strlen($element) > 32){
            $this->messages[] = "Řetězec v poli \"$name\" nesmí být delší než 32 znaků!";
            return false;
        }
        return true;
    }

    /**
     * @param $element
     * @param $name
     * @return bool
     */
    private function shorterThan255($element, $name){
        if (strlen($element) > 255){
            $this->messages[] = "Řetězec v poli \"$name\" nesmí být delší než 255 znaků!";
            return false;
        }
        return true;
    }

    /**
     * @param $element
     * @param $name
     * @return bool
     */
    private function shorterThan65535($element, $name){
        if (strlen($element) > 65535){
            $this->messages[] = "Řetězec v poli \"$name\" nesmí být delší než 65535 znaků!";
            return false;
        }
        return true;
    }

    /**
     * @param $element
     * @param $name
     * @return bool
     */
    private function longerThan4($element, $name){
        if (strlen($element) < 5){
            $this->messages[] = "Řetězec v poli \"$name\" musí být mít alespoň 5 znaků!";
            return false;
        }
        return true;
    }
}