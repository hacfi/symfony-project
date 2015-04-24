<?php

namespace App\Bundle\CoreBundle;

use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use App\Bundle\CoreBundle\DependencyInjection\AppCoreExtension;

class AppCoreBundle extends Bundle
{
    private $configurationAlias;

    public function __construct($alias = 'app_core')
    {
        $this->configurationAlias = $alias;
    }

    public function getContainerExtension()
    {
        return new AppCoreExtension($this->configurationAlias);
    }

    public function registerCommands(Application $application)
    {
        return;
    }
}
