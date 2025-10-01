<?php
namespace App\Compat;

class SvgExtensionCompat extends \manuelodelain\Twig\Extension\SvgExtension
{
    // Predeclare the property so PHP 8.2 doesn't treat it as "dynamic"
    public $basePath;

    public function __construct($basePath)
    {
        // With $basePath declared, the parent assignment won't trigger a deprecation
        parent::__construct($basePath);
    }
}
