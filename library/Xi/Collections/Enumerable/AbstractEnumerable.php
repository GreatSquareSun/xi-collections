<?php
namespace Xi\Collections\Enumerable;

use Xi\Collections\Enumerable;

/**
 * Provides a trivial implementation of an Enumerable
 */
abstract class AbstractEnumerable implements Enumerable
{
    public function toArray()
    {
        return iterator_to_array($this->getIterator());
    }
    
    public function tap($callback)
    {
        $callback($this);
        return $this;
    }

    public function each($callback, $userdata = null)
    {
        foreach ($this as $key => $value) {
            $callback($value, $key, $userdata);
        }
        return $this;
    }

    public function reduce($callback, $initial = null)
    {
        $result = $initial;
        foreach ($this as $key => $value) {
            $result = $callback($result, $value, $key);
        }
        return $result;
    }

    public function find($predicate)
    {
        foreach ($this as $key => $value) {
            if ($predicate($value, $key)) {
                return $value;
            }
        }
        return null;
    }

    public function exists($predicate)
    {
        foreach ($this as $key => $value) {
            if ($predicate($value, $key)) {
                return true;
            }
        }
        return false;
    }

    public function forAll($predicate)
    {
        foreach ($this as $key => $value) {
            if (!$predicate($value, $key)) {
                return false;
            }
        }
        return true;
    }
    
    public function first()
    {
        foreach ($this as $value) {
            return $value;
        }
        return null;
    }

    public function last()
    {
        $result = null;
        foreach ($this as $value) {
            $result = $value;
        }
        return $result;
    }

    public function count($predicate = null)
    {
        if (null === $predicate) {
            return count($this->toArray());
        }

        $count = 0;
        foreach ($this as $key => $value) {
            if ($predicate($value, $key)) {
                $count++;
            }
        }
        return $count;
    }
}