<?php

namespace App\Library;

class Log {

    private $class;

    public function __construct($class) {
        $this->class = $class;
    }

    public function info($message) {
        \Log::channel("portal")->info(join(" ", [
            "ID [".session()->getId()."]", 
            "class [" . $this->class."]", 
            "menssage [" . $message . "]"
        ]));
    }
}