<?php

namespace Flamingo\Transform;

use Flamingo\Core\TransformRuntime;
use joshtronic\LoremIpsum;

/**
 * Class StringTransformCollection
 * @package Flamingo\Transform
 */
class StringTransformCollection extends AbstractTransformCollection
{
    /**
     * @param TransformRuntime $runtime
     */
    public function str_replace(TransformRuntime $runtime)
    {
        $arguments = $runtime->getArguments();
        $value = str_replace($arguments[0], $arguments[1], $runtime->getValue());
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function lipsum(TransformRuntime $runtime)
    {
        $arguments = $runtime->getArguments();
        $generator = new LoremIpsum();

        if (is_array($arguments)) {
            $count = $arguments[0] ?: 1;
            $type = $arguments[1] ?: '';
        } else {
            $count = $arguments ?: 1;
            $type = '';
        }

        switch ($type) {
            case 'paragraph':
            case 'paragraphs':
                $value = $generator->paragraphs($count);
                break;
            case 'sentence':
            case 'sentences':
                $value = $generator->sentences($count);
                break;
            case 'word':
            case 'words':
            default:
                $value = $generator->words($count);
        }

        $runtime->setValue($value);
    }
}