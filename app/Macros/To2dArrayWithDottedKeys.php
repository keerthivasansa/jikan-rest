<?php
namespace App\Macros;

class To2dArrayWithDottedKeys
{
    public function __invoke(): \Closure
    {
        return function ($prefix = '') {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveArrayIterator($this->toArray()),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            $path = [];
            $flatArray = [];

            foreach ($iterator as $key => $value) {
                $path[$iterator->getDepth()] = $key;

                if (!is_array($value)) {
                    $flatArray[
                    $prefix . implode('.', array_slice($path, 0, $iterator->getDepth() + 1))
                    ] = $value;
                }
            }

            return $flatArray;
        };
    }
}
