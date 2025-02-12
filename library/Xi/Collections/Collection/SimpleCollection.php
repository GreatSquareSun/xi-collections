<?php

namespace Xi\Collections\Collection;

use Xi\Collections\Util\Functions;

/**
 * Provides a trivial concrete implementation of a Collection. Accepts any
 * traversable object or array as a constructor argument.
 */
class SimpleCollection extends AbstractCollection
{
    /**
     * @var \Traversable|array
     */
    protected $traversable;

    /**
     * @param \Traversable|array $traversable
     */
    public function __construct($traversable)
    {
        $this->traversable = $traversable;
    }

    public function getIterator()
    {
        return Functions::getIterator($this->traversable);
    }

    public static function create($elements)
    {
        return new static($elements);
    }

    public static function getCreator()
    {
        return Functions::getCallback(get_called_class(), 'create');
    }
}
