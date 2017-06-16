<?php
$config = ['a'=>11];

class Quiz {
    static public function install() {
        global $config;
        var_dump($config);
    }
}
Quiz::install();