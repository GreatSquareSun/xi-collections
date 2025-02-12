<?php

namespace Xi\Collections\Collection;

use Xi\Collections\Collection;
use Xi\Collections\Util\Functions;
use Xi\Collections\Enumerable\OuterEnumerable;

/**
 * Forwards all operations to another Collection provided at construction. In
 * case of operations that return other Collection objects, wraps the results
 * with new instances of the class.
 */
class OuterCollection extends OuterEnumerable implements Collection
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @param Collection $collection
     */
    public function __construct($collection)
    {
        parent::__construct($collection);
        $this->collection = $collection;
    }

    /**
     * @return Collection
     */
    public function getInnerCollection()
    {
        return $this->collection;
    }

    /**
     * @param  Collection                $elements
     * @return OuterCollection
     * @throws \InvalidArgumentException
     */
    public static function create($elements)
    {
        if ($elements instanceof Collection) {
            return new static($elements);
        }
        throw new \InvalidArgumentException("OuterCollection can only wrap Collection instances");
    }

    public static function getCreator()
    {
        return Functions::getCallback(get_called_class(), 'create');
    }

    public function view()
    {
        return new SimpleCollectionView($this, static::getCreator());
    }

    public function apply($callback)
    {
        return static::create($this->collection->apply($callback));
    }

    public function take($number)
    {
        return static::create($this->collection->take($number));
    }

    /**
     * {@inheritdoc}
     */
    public function rest()
    {
        return static::create($this->collection->rest());
    }

    public function map($callback)
    {
        return static::create($this->collection->map($callback));
    }

    public function filter($predicate = null)
    {
        return static::create($this->collection->filter($predicate));
    }

    /**
     * {@inheritdoc}
     */
    public function filterNot($predicate = null)
    {
        return static::create($this->collection->filterNot($predicate));
    }

    /**
     * {@inheritdoc}
     */
    public function partition($predicate)
    {
        return static::create($this->collection->partition($predicate));
    }

    public function concatenate($other)
    {
        return static::create($this->collection->concatenate($other));
    }

    public function union($other)
    {
        return static::create($this->collection->union($other));
    }

    public function values()
    {
        return static::create($this->collection->values());
    }

    public function keys()
    {
        return static::create($this->collection->keys());
    }

    public function flatMap($callback)
    {
        return static::create($this->collection->flatMap($callback));
    }

    public function indexBy($callback)
    {
        return static::create($this->collection->indexBy($callback));
    }

    public function groupBy($callback)
    {
        return static::create($this->collection->groupBy($callback));
    }

    public function pick($key)
    {
        return static::create($this->collection->pick($key));
    }

    public function invoke($method)
    {
        return static::create($this->collection->invoke($method));
    }

    public function flatten()
    {
        return static::create($this->collection->flatten());
    }

    public function unique($strict = true)
    {
        return static::create($this->collection->unique($strict));
    }

    public function sortWith($comparator)
    {
        return static::create($this->collection->sortWith($comparator));
    }

    public function sortBy($metric)
    {
        return static::create($this->collection->sortBy($metric));
    }

    /**
     * {@inheritdoc}
     */
    public function add($value, $key = null)
    {
        return static::create($this->collection->add($value, $key));
    }

    /**
     * {@inheritdoc}
     */
    public function min()
    {
        return $this->collection->min();
    }

    /**
     * {@inheritdoc}
     */
    public function max()
    {
        return $this->collection->max();
    }

    /**
     * {@inheritdoc}
     */
    public function sum()
    {
        return $this->collection->sum();
    }

    /**
     * {@inheritdoc}
     */
    public function product()
    {
        return $this->collection->product();
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->collection->isEmpty();
    }
}
