<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2018 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.bayrell.org/licenses/APACHE-LICENSE-2.0.html
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
namespace Runtime;
use Runtime\rtl;
use Runtime\Exceptions\IndexOutOfRange;

class Collection implements \JsonSerializable
{
	
	protected $_arr = [];
	
	
	/**
	 * Convert to Collection
	 */
	public function toCollection()
	{
		return new \Runtime\Collection($this);
	}
	
	
	
	/**
	 * Convert to Vector
	 */
	public function toVector()
	{
		return new \Runtime\Vector($this);
	}
	
	
	
	/**
	 * Copy collection
	 */
	public function copy()
	{
		$arr2 = static::createNewInstance();
		if ($this->_arr == null) $arr2->_arr = [];
		else $arr2->_arr = array_slice($this->_arr, 0);
		return $arr2;
	}
	
	
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_arr = [];
		$arr = func_get_args();
		foreach ($arr as $data)
		{
			if (is_array($data))
			{
				foreach ($data as $item)
				{
					if (!is_array($item))
					{
						$this->_arr[] = $item;
					}
				}
			}
			else if ($data instanceof \Runtime\Collection)
			{
				foreach ($data->_arr as $item)
				{
					if (!is_array($item))
					{
						$this->_arr[] = $item;
					}
				}
			}
			else
			{
				$this->_arr[] = $data;
			}
		}
	}
	
	
	
	/**
	 * Correct items
	 */
	public function _correctItemsByType($type)
	{
		if ($type == "mixed" or $type == "primitive" or $type == "var") return $this;
		
		return $this->map(
			function($item) use ($type)
			{
				return rtl::correct($item, $type, null);
			}
		);
	}
	
	
	
	/**
	 * Destructor
	 */
	public function __destruct()
	{
		unset($this->_arr);
	}
	
	
	
	/**
	 * Get array
	 */
	public function _getArr()
	{
		return $this->_arr;
	}
	
	
	
	/**
	 * Returns new Instance
	 */
	public static function createNewInstance($arr = null)
	{
		$class_name = static::class;
		$res = new $class_name();
		if ($arr != null) $res->_arr = $arr;
		return $res;
	}
	
	
	
	/**
	 * Returns value from position
	 * @param int pos - position
	 */
	public function get($pos, $default_value)
	{
		return isset($this->_arr[$pos]) ? $this->_arr[$pos] : $default_value;
	}
	
	
	
	/**
	 * Returns value from position. Throw exception, if position does not exists
	 * @param int pos - position
	 */
	public function item($pos)
	{
		if (!array_key_exists($pos, $this->_arr))
			throw new IndexOutOfRange();
		return $this->_arr[$pos];
	}
	
	
	
	/**
	 * Returns count items in vector
	 */
	public function count()
	{
		return count($this->_arr);
	}
	
	
	
	/**
	 * Find value in array. Returns -1 if value not found.
	 * @param T value
	 * @return  int
	 */
	public function indexOf($value)
	{
		$pos = array_search($value, $this->_arr, true);
		if ($pos === false)
			return -1;
		return $pos;
	}
	
	
	
	/**
	 * Find value in array, and returns position. Returns -1 if value not found.
	 * @param T value
	 * @param int pos_begin - begin position
	 * @param int pos_end - end position
	 * @return  int
	 */
	public function indexOfRange($value, $pos_begin, $pos_end)
	{
		$pos = $this->indexOf($value);
		if ($pos == -1 or $pos > $pos_end or $pos < $pos_begin)
			return -1;
		return $pos;
	}
	
	
	
	/**
	 * Get last item
	 */
	public function first($default_value = null)
	{
		$c = count($this->_arr);
		if ($c == 0)
			return $default_value;	
		return $this->_arr[0];
	}
	
	
	
	/**
	 * Get last item
	 */
	public function last($default_value = null, $pos=-1)
	{
		$c = count($this->_arr);
		if ($c == 0) return $default_value;
		if ($c + $pos + 1 == 0) return $default_value;
		return isset( $this->_arr[$c+$pos] ) ? $this->_arr[$c+$pos] : $default_value;
	}
	public function getLastItem($default_value = null, $pos=-1)
	{
		return this.last($default_value, $pos); 
	}
	
	
	
	/**
	 * Append value to the end of array
	 * @param T value
	 */
	public function pushIm($value)
	{
		$res = $this->copy();
		$res->_arr[] = $value;
		return $res;
	}
	
	
	
	/**
	 * Insert first value size_to array
	 * @return T value
	 */
	public function unshiftIm($value)
	{
		$res = $this->copy();
		array_unshift($res->_arr, $value);
		return $res;
	}
	
	
	
	/**
	 * Extract last value from array
	 * @return T value
	 */
	public function removeLastIm()
	{
		$res = $this->copy();
		array_pop($res->_arr);
		return $res;
	}
	
	
	
	/**
	 * Extract first value from array
	 * @return T value
	 */
	public function removeFirstIm()
	{
		$res = $this->copy();
		array_shift($res->_arr);
		return $res;
	}
	
	
	
	/**
	 * Insert value size_to position
	 * @param T value
	 * @param int pos - position
	 */
	public function insertIm($pos, $value)
	{
		$res = $this->copy();
		array_splice($res->_arr, $pos, 0, [$value]);
		return $res;
	}
	
	
	
	/**
	 * Remove value from position
	 * @param int pos - position
	 * @param int count - count remove items
	 */
	public function removeIm($pos, $count = 1)
	{
		$res = $this->copy();
		array_splice($res->_arr, $pos, $count);
		return $res;
	}
	
	
	
	/**
	 * Remove range
	 * @param int pos_begin - start position
	 * @param int pos_end - end position
	 */
	public function removeRangeIm($pos_begin, $pos_end)
	{
		$res = $this->copy();
		$res->removeIm($pos_begin, $pos_end - $pos_begin + 1);
		return $res;
	}
	
	
	
	/**
	 * Set value size_to position
	 * @param int pos - position
	 * @param T value 
	 */
	public function setIm($pos, $value)
	{
		if (!array_key_exists($pos, $this->_arr))
			throw new IndexOutOfRange();
		$res = $this->copy();	
		$res->_arr[$pos] = $value;
		return $res;
	}
	
	
	
	/**
	 * Append value to the end of array
	 * @param T value
	 */
	public function appendIm($value)
	{
		return $this->pushIm($value);
	}
	
	
	
	/**
	 * Insert first value size_to array
	 * @return T value
	 */
	public function prependIm($value)
	{
		return $this->unshiftIm($value);
	}
	
	
	
	/**
	 * Append vector to the end of the vector
	 * @param Collection<T> arr
	 */
	public function appendCollectionIm($arr)
	{
		if (!$arr) return $this;
		if (count($arr->_arr) == 0) return $this;
		$res = $this->copy();
		foreach ($arr->_arr as $item)
		{
			$res->_arr[] = $item;
		}
		return $res;
	}
	
	
	
	/**
	 * Prepend vector to the begin of the vector
	 * @param Collection<T> arr
	 */
	public function prependCollectionIm($arr)
	{
		if (!$arr) return $this;
		$res = $this->copy();
		foreach ($arr->_arr as $item)
		{
			array_unshift($res->_arr, $item);
		}
		return $res;
	}
	
	
	
	/**
	 * Remove value
	 * @param mixed value
	 */
	public function removeValueIm($value)
	{
		$index = $this->indexOf($value);
		if ($index != -1) return $this->removeIm($index, 1);
		return $this;
	}
	
	
	
	/**
	 * Remove value
	 * @param mixed value
	 */
	public function removeItem($value)
	{
		return $this->removeValueIm($value);
	}
	
	
	
	/**
	 * Remove values
	 * @param mixed values
	 */
	public function removeItems($values)
	{
		$res = $this;
		for ($i=0; $i<$values->count(); $i++)
		{
			$res = $res->removeItem( $values->item(i) );
		}
		return $res;
	}
	
	
	
	/**
	 * Map
	 * @param func f
	 * @return Collection
	 */
	function map($f)
	{
		$keys = array_keys($this->_arr);
		$arr2 = static::createNewInstance();
		$arr2->_arr = array_map($f, $this->_arr, $keys);
		return $arr2;
	}
	
	
	
	/**
	 * Filter items
	 * @param func f
	 * @return Collection
	 */
	function filter($f)
	{
		$arr2 = static::createNewInstance();
		$arr2->_arr = array_values(array_filter($this->_arr, $f));
		return $arr2;
	}
	
	
	
	/**
	 * Reduce
	 * @param func f
	 * @param mixed init_value
	 * @return init_value
	 */
	function reduce($f, $init_value)
	{
		return array_reduce($this->_arr, $f, $init_value);
	}
	
	
	
	/**
	 * Call function for each item
	 * @param func f
	 */
	function each($f)
	{
		array_walk($this->_arr, $f);
		return $this;
	}
	
	
	
	/**
	 * Each item recursive
	 * @param func f
	 * @param func childs Returns childs items
	 * @param func kind. 1 - Node item first, -1 - Node item last
	 */
	function recurse($f, $childs, $kind=1)
	{
		return $this;
	}
	
	
	
	/**
	 * Returns new concated Collection
	 * @param Collection v
	 * @return Collection
	 */
	function concat($v)
	{
		$arr2 = static::createNewInstance();
		$arr2->_arr = array_merge($this->_arr, $v->_arr);
		return $arr2;
	}
	
	
	
	/**
	 * Returns Collection
	 * @param int offset begin
	 * @param int length count
	 * @return Collection<T>
	 */
	function slice($offset = 0, $length = null)
	{
		$arr2 = static::createNewInstance();
		$arr2->_arr = array_slice($this->_arr, $offset, $length);
		return $arr2;
	}
	
	
	
	/**
	 * JsonSerializable
	 */
	public function jsonSerialize(){
		return $this->_arr;
	}
	
	
	
	/**
	 * Reverse array
	 */
	public function reverse(){
		array_reverse($this->_arr);
		return $this;
	}
	
	
	
	/**
	 * Sort vector
	 * @param func f - Sort user function
	 */
	public function sortIm($f = null)
	{
		$res = $this->copy();
		if ($f == null)
		{
			asort($res->_arr);
		}
		else
		{
			usort($res->_arr, $f);
		}
		return $res;
	}
	
	
	
	/**
	 * Remove dublicate values
	 */
	public function removeDublicatesIm()
	{
		$arr = [];
		for ($i=0; $i<$this->count(); $i++)
		{			
			$value = $this->item($i);
			$pos = array_search($value, $arr, true);
			if ($pos === false)
			{
				$arr[] = $value;
			}
		}
		$res = static::createNewInstance();
		$res->_arr = $arr;
		return $res;
	}
}