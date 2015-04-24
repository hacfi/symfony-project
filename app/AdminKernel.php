<?php

require_once __DIR__.'/AbstractKernel.php';

class AdminKernel extends AbstractKernel
{
    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug, self::CONTEXT_ADMIN);
    }

    public function registerBundles()
    {
        $bundles = parent::registerBundles();

//        array_push(
//            $bundles,
//            //new App\Bundle\AdminBundle\AppAdminBundle()
//        );

        return $bundles;
    }
}
