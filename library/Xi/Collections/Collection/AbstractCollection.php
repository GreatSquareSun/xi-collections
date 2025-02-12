<?php

namespace Xi\Collections\Collection;

use Xi\Collections\Collection;
use Xi\Collections\Util\Functions;
use Xi\Collections\Enumerable\AbstractEnumerable;
use UnderflowException;

/**
 * Provides a trivial implementation of a Collection
 */
abstract class AbstractCollection extends AbstractEnumerable implements Collection
{
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
        return static::create($callback($this));
    }

    public function take($number)
    {
        $results = array();
        foreach ($this as $key => $value) {
            if ($number-- <= 0) {
                break;
            }
            $results[$key] = $value;
        }

        return static::create($results);
    }

    public function map($callback)
    {
        $results = array();
        foreach ($this as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        return static::create($results);
    }

    public function filter($predicate = null)
    {
        if (null === $predicate) {
            $predicate = $this->notEmptyFilter();
        }

        $results = array();
        foreach ($this as $key => $value) {
            if ($predicate($value, $key)) {
                $results[$key] = $value;
            }
        }

        return static::create($results);
    }

    /**
     * @return callable
     */
    private function notEmptyFilter()
    {
        return function ($value) {
            return !empty($value);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function filterNot($predicate = null)
    {
        if (null === $predicate) {
            $predicate = $this->notEmptyFilter();
        }

        $results = array();

        foreach ($this as $key => $value) {
            if (!$predicate($value, $key)) {
                $results[$key] = $value;
            }
        }

        return static::create($results);
    }

    /**
     * {@inheritdoc}
     */
    public function partition($predicate)
    {
        return static::create(array($this->filter($predicate), $this->filterNot($predicate)));
    }

    public function concatenate($other)
    {
        $results = array();
        foreach ($this as $value) {
            $results[] = $value;
        }
        foreach ($other as $value) {
            $results[] = $value;
        }

        return static::create($results);
    }

    public function union($other)
    {
        $results = $this->toArray();
        foreach ($other as $key => $value) {
            $results[$key] = $value;
        }

        return static::create($results);
    }

    public function values()
    {
        $results = array();
        foreach ($this as $value) {
            $results[] = $value;
        }

        return static::create($results);
    }

    public function keys()
    {
        $results = array();
        foreach ($this as $key => $value) {
            $results[] = $key;
        }

        return static::create($results);
    }

    public function flatMap($callback)
    {
        return $this->apply(Functions::flatMap($callback));
    }

    public function indexBy($callback)
    {
        return $this->apply(Functions::indexBy($callback));
    }

    public function groupBy($callback)
    {
        return $this->apply(Functions::groupBy($callback, $this->getCreator()));
    }

    public function pick($key)
    {
        return $this->map(Functions::pick($key));
    }

    public function invoke($method)
    {
        return $this->map(Functions::invoke($method));
    }

    public function flatten()
    {
        return $this->apply(Functions::flatten());
    }

    public function unique($strict = true)
    {
        return $this->apply(Functions::unique($strict));
    }

    public function sortWith($comparator)
    {
        return $this->apply(Functions::sortWith($comparator));
    }

    public function sortBy($metric)
    {
        return $this->apply(Functions::sortBy($metric));
    }

    /**
     * {@inheritdoc}
     */
    public function add($value, $key = null)
    {
        $results = $this->toArray();

        if ($key === null) {
            $results[] = $value;
        } else {
            $results[$key] = $value;
        }

        return static::create($results);
    }

    /**
     * {@inheritdoc}
     */
    public function min()
    {
        if ($this->isEmpty()) {
            throw new UnderflowException(
                'Can not get a minimum value on an empty collection.'
            );
        }

        $min = null;

        foreach ($this as $value) {
            if ($min === null || $value < $min) {
                $min = $value;
            }
        }

        return $min;
    }

    /**
     * {@inheritdoc}
     */
    public function max()
    {
        if ($this->isEmpty()) {
            throw new UnderflowException(
                'Can not get a maximum value on an empty collection.'
            );
        }

        $max = null;

        foreach ($this as $value) {
            if ($max === null || $value > $max) {
                $max = $value;
            }
        }

        return $max;
    }

    /**
     * {@inheritdoc}
     */
    public function sum()
    {
        $sum = 0;

        foreach ($this as $value) {
            $sum += $value;
        }

        return $sum;
    }

    /**
     * {@inheritdoc}
     */
    public function product()
    {
        $product = 1;

        foreach ($this as $value) {
            $product *= $value;
        }

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function rest()
    {
        $results = array();
        $first = true;

        foreach ($this as $key => $value) {
            if (!$first) {
                $results[$key] = $value;
            }

            $first = false;
        }

        return static::create($results);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }
}
