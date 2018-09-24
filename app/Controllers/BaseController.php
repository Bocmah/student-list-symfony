<?php

namespace StudentList\Controllers;

use Symfony\Component\HttpFoundation\Response;

abstract class BaseController
{
    /**
     * Renders the specific view passing $params
     *
     * @param $file
     * @param array $params
     * @return mixed
     */
    protected function render($file, array $params = [])
    {
        extract($params,EXTR_SKIP);
        ob_start();
        require __DIR__."/../../views/{$file}";

        return new Response(ob_get_clean());
    }
}
