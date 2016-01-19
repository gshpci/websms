<?php
    /**
    *
    *
    *
    *
    *
     */

    require_once 'classes/Database.php';

    function __autoload($class)
    {
        $className = ucfirst($class);

        require_once "classes/{$className}.php";
    }