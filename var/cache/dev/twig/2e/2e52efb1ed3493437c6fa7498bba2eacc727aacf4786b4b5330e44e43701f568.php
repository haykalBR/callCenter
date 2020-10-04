<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* base.html.twig */
class __TwigTemplate_f75a0d044fe80828ef14097a68c5c8b76aa728f1d70eac0ddc5eceaa95c237ee extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "base.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "base.html.twig"));

        // line 1
        $this->loadTemplate("_layout/head.html.twig", "base.html.twig", 1)->display($context);
        // line 2
        $this->loadTemplate("_layout/head-end.html.twig", "base.html.twig", 2)->display($context);
        // line 3
        echo $this->extensions['App\Core\Twig\MenuExtension']->navbarBuild();
        echo "
";
        // line 4
        echo $this->extensions['App\Core\Twig\MenuExtension']->menuBuild();
        echo "
 <div class=\"content-wrapper\">
   ";
        // line 6
        $this->displayBlock("content", $context, $blocks);
        echo "
 </div>
";
        // line 8
        $this->loadTemplate("_layout/footer.html.twig", "base.html.twig", 8)->display($context);
        // line 9
        $this->loadTemplate("_layout/foot.html.twig", "base.html.twig", 9)->display($context);
        // line 10
        echo "
";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  65 => 10,  63 => 9,  61 => 8,  56 => 6,  51 => 4,  47 => 3,  45 => 2,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% include \"_layout/head.html.twig\" %}
{% include \"_layout/head-end.html.twig\" %}
{{ Navbar_build() }}
{{ Menu_build() }}
 <div class=\"content-wrapper\">
   {{  block('content') }}
 </div>
{% include \"_layout/footer.html.twig\" %}
{% include \"_layout/foot.html.twig\" %}

", "base.html.twig", "/var/www/application/templates/base.html.twig");
    }
}
