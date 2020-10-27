<?php

class SourceNotFoundException extends Exception {
    protected $error;

    function __construct($error) {
        $this->error = $error;
    }

    function getError() {
        return $this->error;
    }
}