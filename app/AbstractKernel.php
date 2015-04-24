<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

abstract class AbstractKernel extends Kernel
{
    const CONTEXT_ADMIN = 'admin';
    const CONTEXT_API   = 'api';

    /**
     * @var string
     */
    private $context;

    /**
     * @var float
     */
    private $kernelStartTime;

    /**
     * {@inheritdoc}
     *
     * @param string $environment
     * @param bool   $debug
     * @param string $context
     */
    public function __construct($environment, $debug, $context)
    {
        $this->kernelStartTime = microtime(true);

        parent::__construct($environment, $debug);

        if (!$context) {
            throw new \InvalidArgumentException(sprintf('No context has been set for kernel "%s"', get_class($this)));
        }

        $this->setContext($context);
    }

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SerializerBundle\JMSSerializerBundle(),

            new App\Bundle\CoreBundle\AppCoreBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            array_push(
                $bundles,
                new Symfony\Bundle\DebugBundle\DebugBundle(),
                new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
                new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle()
            );
        }

        return $bundles;
    }

    /**
     * Load container configuration
     *
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/'.$this->getContext().'/config_'.$this->getEnvironment().'.yml');

        if (file_exists($localConfig = __DIR__.'/config/config.local.yml')) {
            $loader->load($localConfig);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return $this->rootDir.'/../var/cache/'.$this->getContext().'/'.$this->getEnvironment();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return $this->rootDir.'/../var/logs/'.$this->getContext();
    }

    /**
     * {@inheritdoc}
     */
    protected function getKernelParameters()
    {
        return array_merge(
            parent::getKernelParameters(),
            [
                'kernel.context' => $this->getContext(),
            ]
        );
    }

    /**
     * Get kernel start time.
     *
     * @return float
     */
    public function getKernelStartTime()
    {
        return $this->kernelStartTime;
    }

    /**
     * Gets the name of the application.
     */
    protected function getContext()
    {
        return $this->context;
    }

    /**
     * Set the context
     *
     * @param string $context
     */
    private function setContext($context)
    {
        $this->context = $this->name = $context;
    }
}
