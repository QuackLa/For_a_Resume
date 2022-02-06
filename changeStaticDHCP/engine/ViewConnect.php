<?php

namespace DHCP;

class ViewConnect
{
    /**
     * Передача данных во вьюху
     */
    function view($object, $view, $array)
    {
        echo $object->run($view, $array);
    }
}