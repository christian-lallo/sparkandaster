<?php

namespace app\Twig;

use Twig;

use Twig\TwigFunction;

class HelperTwigExtension extends \Twig_Extension
{

    public function getName()
    {
        return 'General Helper Twig Extensions';
    }

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return [];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getenv', [$this, 'getEnv']),
        ];
    }
 
    public function getEnv($varname)
    {
        $value = getenv($varname);

			return $value;
    }

}