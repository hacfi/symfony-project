<?php

namespace App\Util;

use FOS\RestBundle\Controller\Annotations\View;

use Symfony\Component\Templating\TemplateReference;

/**
 * View annotation class.
 *
 * @Annotation
 * @Target({"METHOD","CLASS"})
 */
class NoTemplateView extends View
{
    public function getTemplate()
    {
        return $this->template ?: new TemplateReference();
    }
}
