<?php

namespace Flamingo\Transform;

use Flamingo\Core\TransformRuntime;

/**
 * Class PhpTransformCollection
 * @package Flamingo\Transform
 */
class PhpTransformCollection extends AbstractTransformCollection
{
    /**
     * This contains a lot of simple calls to 1 argument function.
     * You can see the list of available methods in the YAML configuration.
     *
     * @see DefaultConfiguration.yaml
     *
     * @param string $function
     * @param array $parameters
     */
    public function __call($function, $parameters)
    {
        /** @var TransformRuntime $runtime */
        $runtime = $parameters[0];

        if (is_callable($function)) {
            $value = $function($runtime->getValue());
            $runtime->setValue($value);
        }
    }
}