<?php
namespace Esler\KivWeb;

use Twig_Environment;

class View
{

    private $template;
    private $twig;

    /**
     * Constructor
     *
     * @param Twig_Environment $twig twig env
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Renders a view with given context
     *
     * @param array $context a context
     *
     * @return string
     */
    public function render(array $context): string
    {
        if ($this->template) {
            return $this->twig->load($this->template)->render($context);
        }

        throw new Exception\ServerError('Cannot render without template');
    }

    /**
     * Sets twig template to view
     *
     * @param string $control control
     * @param string $action  action
     *
     * @return void
     */
    public function setTemplate(string $control, string $action): void
    {
        $this->template = $control . '/' . $action . '.html';
    }
}
