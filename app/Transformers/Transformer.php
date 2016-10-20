<?php

namespace App\Transformers;

abstract class Transformer
{
    /**
     *	Transform a collection.
     */
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    abstract public function transform($item);
}
