<?php

namespace StudentList\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    /**
     * Redirects to a given $url
     *
     * @param string $url
     * @param int $status
     * @return RedirectResponse
     */
    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }
}
