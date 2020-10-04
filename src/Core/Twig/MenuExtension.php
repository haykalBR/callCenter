<?php

namespace App\Core\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    /**
     * var Environment $twig
     */
    private $twig;
    public function __construct(Environment  $twig)
    {
        $this->twig=$twig;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('Navbar_build', [$this, 'navbarBuild'],['is_safe' => ['html']]),
            new TwigFunction('Menu_build', [$this, 'menuBuild'],['is_safe' => ['html']]),
        ];
    }
    /**
     * Create Navbar page 
     */
    public function navbarBuild()
    {
        return $this->twig->render('_layout/_menu/navbar.html.twig', []);
    }
    /**
     * Create Menu page 
     */
    public function menuBuild()
    {
      return $this->twig->render('_layout/_menu/menu.html.twig', []);
    }
}
