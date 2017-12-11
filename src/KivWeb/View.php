<?php
namespace Esler\KivWeb;

use Twig_Environment;

class View
{

    private $template;
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->template = $twig->load('default.html');
    }

    public function render(array $context): string
    {
        return $this->template->render($context);
    }

    public function setTemplate(string $control, string $action): void
    {
        $filename = $control . '/' . $action . '.html';

        $this->template = $this->twig->load($filename);
    }
}
