<?php
class MyClass
{
    private $foo = FALSE;

    public function __construct()
    {
        $this->$foo = TRUE;

        var_dump($this->$foo);
    }
}