<?php

require_once __DIR__.'/AbstractKernel.php';

class ApiKernel extends AbstractKernel
{
    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug, self::CONTEXT_API);
    }

    public function registerBundles()
    {
        $bundles = parent::registerBundles();

        array_push(
            $bundles,
            new FOS\RestBundle\FOSRestBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle()
        );

        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
//            array_push(
//                $bundles,
//                new Symfony\Bundle\DebugBundle\DebugBundle()
//            );
        }

        return $bundles;
    }
}
