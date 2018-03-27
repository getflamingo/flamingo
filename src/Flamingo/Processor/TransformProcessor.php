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
        'modifiers' => [],
    ];

    /**
     * Run inline methods to quickly update properties.
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

                try {
                    $function = $this->createFunction($code, $record);
                    $value = call_user_func_array($function, array_values($record));
                } catch (\Exception $e) {
                    throw new RuntimeException($e->getMessage());
                }

                if (isset($value)) {
                    $record[$property] = $value;
                }
            }
        }
    }

    /**
     * Create a user function with a defined return statement.
     * The names of the columns are translated to variables.
     *
     * @param string $code
     * @param array $record
     * @return string
     */
    protected function createFunction($code, array $record)
    {
        $code = sprintf('return %s;', rtrim($code, ';'));
        $args = preg_filter('/^/', '$', array_keys($record));
        $function = create_function(implode(',', $args), $code);

        return $function;
    }
}
