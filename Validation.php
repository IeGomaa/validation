<?php

namespace Validation;

class Validation {

    /**
     * @var array $patterns
     */
    private $patterns = [
        'email'         => '/^[\w.-]+?@[a-z]+?\.[a-z]+$/',
        'alpha'         => '/^[a-z]+$/',
        'int'           => '/^[0-9]+$/',
        'float'         => '/^[0-9\.]+$/',
        'tel'           => '/^[0-9+\h()-]+$/'
    ];

    private $field;
    private $data;
    private $errors = [];
    public function input($field) {
        $this->field = $field;
        return $this;
    }

    public function value($value) {
        $this->data = $value;
        return $this;
    }

    public function string() {
        $this->data = filter_var($this->data,FILTER_SANITIZE_STRING);
        if (!(preg_match($this->patterns['alpha'],$this->data))) {
            $this->errors[$this->field][] = 'Sorry Data Cann\'t Match With String Data';
        }
        return $this;
    }

    public function integer() {
        $this->data = filter_var($this->data,FILTER_SANITIZE_NUMBER_INT);
        $this->data = filter_var($this->data,FILTER_VALIDATE_INT,FILTER_NULL_ON_FAILURE);

        if ($this->data === NULL) {
            $this->errors[$this->field][] = 'Data Must Be As Integer';
        }

        if (!(preg_match($this->patterns['int'],$this->data))) {
            $this->errors[$this->field][] = 'Sorry Data Cann\'t Match With Integer Data';
        }
        return $this;
    }

    public function float() {
        $this->data = filter_var($this->data,FILTER_SANITIZE_NUMBER_FLOAT,[
            'flags' => FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND
        ]);
        $this->data = filter_var($this->data,FILTER_VALIDATE_FLOAT,FILTER_NULL_ON_FAILURE);

        if ($this->data === NULL) {
            $this->errors[$this->field][] = 'Data Must Be As Float';
        }

        if (!(preg_match($this->patterns['float'],$this->data))) {
            $this->errors[$this->field][] = 'Sorry Data Cann\'t Match With Floating Point';
        }

        return $this;
    }

    public function telephone() {
        if (!(preg_match($this->patterns['tel'],$this->data))) {
            $this->errors[$this->field][] = 'Data Must Be Telephone Number';
        }
        return $this;
    }

    public function email() {
        $this->data = filter_var($this->data,FILTER_SANITIZE_EMAIL);
        $this->data = filter_var($this->data,FILTER_VALIDATE_EMAIL,FILTER_NULL_ON_FAILURE);

        if ($this->data === NULL) {
            $this->errors[$this->field][] = 'Data Must Be Email';
        }

        if ((!preg_match($this->patterns['email'],$this->data))) {
            $this->errors[$this->field][] = 'Sorry Data Cann\'t Match With Email Data';
        }
        return $this;
    }

    public function max($length) {
        if (strlen($this->data) > $length) {
            $this->errors[$this->field][] = 'Data Must Be Less Than ' . $length;
        }
        return $this;
    }

    public function min($length) {
        if (strlen($this->data) < $length) {
            $this->errors[$this->field][] = 'Data Must Be Greater Than ' . $length;
        }
        return $this;
    }

    public function required() {

        if (is_null($this->data)) {
            $this->errors[$this->field][] = 'Data Can Not Be Null';
        }

        if (empty($this->data)) {
            $this->errors[$this->field][] = 'Data Can Not Be Empty';
        }

        if (strlen(trim($this->data)) === 0) {
            $this->errors[$this->field][] = 'Data Not Valid!!';
        }
        return $this;
    }

    private function showError() {
        if (!empty($this->errors)) {

            foreach ($this->errors as $key => $val) {
                echo '<h1 style="font-family: cursive;color: red;">' . ucwords(strtolower($key)) . ':</h1>';
                echo '<ul>';
                foreach ($val as $item) {
                    echo '<li style="font-family: cursive">' . $item . '</li>';
                }
                echo '<ul>';
            }

        }
    }

    public function successful() {
        if (!empty($this->errors)) {
            $this->showError();
        } else {
            echo 'Data Is Valid';
        }
    }

}