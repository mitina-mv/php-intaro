<?php

namespace App\View;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;

class View
{
    private $template;

    public function __construct()
    {
        $filesystemLoader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/pages/%name%.php');
        $this->template = new PhpEngine(new TemplateNameParser(), $filesystemLoader);
    }

    public function make($path, $data = [])
    {
        return $this->template->render($path, $data);
    }
}