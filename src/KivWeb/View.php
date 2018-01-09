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
    }

    public function render(array $context): string
    {
        if ($this->template) {
            return $this->twig->load($this->template)->render($context);
        }

        throw new Exception\ServerError('Cannot render without template');
    }

    public function setTemplate(string $control, string $action): void
    {
        $this->template = $control . '/' . $action . '.html';
    }
}
