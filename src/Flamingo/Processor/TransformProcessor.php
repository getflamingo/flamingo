<?php

namespace Flamingo\Processor;

use Flamingo\Exception\RuntimeException;

/**
 * Class TransformProcessor
 * @package Flamingo\Processor
 */
class TransformProcessor extends AbstractProcessor
{
    /**
     * @var array
     */
    protected $options = [
        'propertyMustExist' => false,
        'placeholder' => '{?}',
        'modifiers' => [],
    ];

    /**
     * Run inline methods to quickly update properties.
     * TODO: Add security check of som sort, for the eval() function
     *
     * @throws RuntimeException
     */
    public function run()
    {
        foreach ($this->table as &$record) {
            foreach ($this->options['modifiers'] as $property => $code) {

                if (
                    array_key_exists($property, $record) === false
                    && $this->options['propertyMustExist'] === false
                ) {
                    continue;
                }

                $value = $record[$property];

                try {
                    $code = str_replace($this->options['placeholder'], '$value', $code);
                    $code = sprintf('$value = %s;', rtrim($code, ';'));
                    eval($code);
                } catch (\Exception $e) {
                    throw new RuntimeException($e->getMessage());
                }

                $record[$property] = $value;
            }
        }
    }
}
