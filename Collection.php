<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2019 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
namespace Runtime;
class _Collection implements \ArrayAccess, \JsonSerializable
{
	public $_arr = [];
	
	
	/**
	 * From
	 */
	static function from($arr)
	{
		$class_name = static::class;
		$res = new $class_name();
		if ($arr != null)
		{
			if ($arr instanceof \Runtime\Collection)
			{
				$res->_arr = $arr->_arr;
			}
			else if (gettype($arr) == 'array') $res->_arr = $arr;
		}
		return $res;	
	}
	
	
	/**
	 * JsonSerializable
	 */
	public function jsonSerialize()
	{
		return $this->_arr;
	}
	
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_arr = array_slice(func_get_args(), 1);
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
	 * Get and set methods
	 */
	function __isset($k){return isset($this->_arr[$k]);}
	function __get($k){return $this->item($name);}
	function __set($k,$v){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	function __unset($k){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	public function offsetExists($k){return isset($this->_arr[$k]);}
	public function offsetGet($k){return $this->item($name);}
	public function offsetSet($k,$v){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	public function offsetUnset($k){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	
	
	/* Class name */
	public function getClassName(){return "Runtime._Collection";}
	public static function getCurrentClassName(){return "Runtime._Collection";}
	public static function getParentClassName(){return "";}
	
}
class Collection extends \Runtime\_Collection
{
	/**
	 * Returns new Instance
	 * @return Object
	 */
	static function Instance($__ctx)
	{
		return new \Runtime\Collection($__ctx);
	}
	/**
	 * Returns new Instance
	 * @return Object
	 */
	static function create($__ctx, $arr)
	{
		return static::from($arr);
	}
	/**
	 * Returns copy of Collectiom
	 * @param int pos - position
	 */
	function copy($__ctx)
	{
		$class_name = static::class;
		$arr2 = new $class_name();
		if ($this->_arr == null) $arr2->_arr = [];
		else $arr2->_arr = array_slice($this->_arr, 0);
		return $arr2;
	}
	/**
	 * Convert to collection
	 */
	function toCollection($__ctx)
	{
		return \Runtime\Collection::from($this);
	}
	/**
	 * Convert to vector
	 */
	function toVector($__ctx)
	{
		return \Runtime\Vector::from($this);
	}
	/**
	 * Returns value from position
	 * @param int pos - position
	 */
	function get($__ctx, $pos, $default_value)
	{
		$val = isset($this->_arr[$pos]) ? $this->_arr[$pos] : $default_value;
		return $val;
	}
	/**
	 * Returns value from position. Throw exception, if position does not exists
	 * @param int pos - position
	 */
	function item($__ctx, $pos)
	{
		if (!array_key_exists($pos, $this->_arr))
		{
			throw new IndexOutOfRange($__ctx);
		}
		return $this->_arr[$pos];
	}
	/**
	 * Returns count items in vector
	 */
	function count($__ctx)
	{
		return count($this->_arr);
	}
	/**
	 * Find value in array. Returns -1 if value not found.
	 * @param T value
	 * @return  int
	 */
	function indexOf($__ctx, $value)
	{
		$pos = array_search($value, $this->_arr, true);
		if ($pos === false) return -1;
		return $pos;
	}
	/**
	 * Find value in array, and returns position. Returns -1 if value not found.
	 * @param T value
	 * @param int pos_begin - begin position
	 * @param int pos_end - end position
	 * @return  int
	 */
	function indexOfRange($__ctx, $value, $pos_begin, $pos_end)
	{
		$pos = $this->indexOf($__ctx, $value);
		if ($pos == -1 or $pos > $pos_end or $pos < $pos_begin)
			return -1;
		return $pos;
	}
	/**
	 * Get first item
	 */
	function first($__ctx, $default_value=null)
	{
		$c = count($this->_arr);
		if ($c == 0) return $default_value;	
		return $this->_arr[0];
	}
	/**
	 * Get last item
	 */
	function last($__ctx, $default_value=null, $pos=-1)
	{
		$c = count($this->_arr);
		if ($c == 0) return $default_value;
		if ($c + $pos + 1 == 0) return $default_value;
		return isset( $this->_arr[$c+$pos] ) ? $this->_arr[$c+$pos] : $default_value;
	}
	/**
	 * Get last item
	 */
	function getLastItem($__ctx, $default_value=null, $pos=-1)
	{
		return $this->last($__ctx, $default_value, $pos);
	}
	/**
	 * Append value to the end of the Collection and return new Collection
	 * @param T value
	 */
	function pushIm($__ctx, $value)
	{
		$res = $this->copy($__ctx);
		$res->_arr[] = $value;
		return $res;
	}
	/**
	 * Insert first value size_to array
	 * @return T value
	 */
	function unshiftIm($__ctx, $value)
	{
		$res = $this->copy($__ctx);
		array_unshift($res->_arr, $value);
		return $res;
	}
	/**
	 * Extract last value from array
	 * @return T value
	 */
	function removeLastIm($__ctx)
	{
		$res = $this->copy($__ctx);
		array_pop($res->_arr);
		return $res;
	}
	/**
	 * Extract first value from array
	 * @return T value
	 */
	function removeFirstIm($__ctx)
	{
		$res = $this->copy($__ctx);
		array_shift($res->_arr);
		return $res;
	}
	/**
	 * Insert value to position
	 * @param T value
	 * @param int pos - position
	 */
	function insertIm($__ctx, $pos, $value)
	{
		$res = $this->copy($__ctx);
		array_splice($res->_arr, $pos, 0, [$value]);
		return $res;
	}
	/**
	 * Remove value from position
	 * @param int pos - position
	 * @param int count - count remove items
	 */
	function removeIm($__ctx, $pos, $count=1)
	{
		$res = $this->copy($__ctx);
		array_splice($res->_arr, $pos, $count);
		return $res;
	}
	/**
	 * Remove range
	 * @param int pos_begin - start position
	 * @param int pos_end - end position
	 */
	function removeRangeIm($__ctx, $pos_begin, $pos_end)
	{
		$res = $this->copy($__ctx);
		$res->removeIm($pos_begin, $pos_end - $pos_begin + 1);
		return $res;
	}
	/**
	 * Set value size_to position
	 * @param int pos - position
	 * @param T value 
	 */
	function setIm($__ctx, $pos, $value)
	{
		if (!array_key_exists($pos, $this->_arr))
			throw new IndexOutOfRange($__ctx);
		$res = $this->copy($__ctx);	
		$res->_arr[$pos] = $value;
		return $res;
	}
	/**
	 * Append value to the end of the vector
	 * @param T value
	 */
	function appendIm($__ctx, $value)
	{
		return $this->pushIm($__ctx, $value);
	}
	/**
	 * Insert first value to begin of the vector
	 * @return T value
	 */
	function prependIm($__ctx, $value)
	{
		return $this->unshiftIm($__ctx, $value);
	}
	/**
	 * Append vector to the end of the vector
	 * @param Collection<T> arr
	 */
	function appendCollectionIm($__ctx, $arr)
	{
		if (!$arr) return $this;
		if (count($arr->_arr) == 0) return $this;
		$res = $this->copy($__ctx);
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
	function prependCollectionIm($__ctx, $arr)
	{
		if (!$arr) return $this;
		$res = $this->copy($__ctx);
		$sz = count($arr->_arr);
		for ($i=$sz-1; $i>=0; $i--)
		{
			array_unshift($res->_arr, $arr->_arr[$i]);
		}
		return $res;
	}
	/**
	 * Remove value
	 */
	function removeValueIm($__ctx, $value)
	{
		$index = $this->indexOf($__ctx, $value);
		if ($index != -1)
		{
			return $this->removeIm($__ctx, $index);
		}
		return $this;
	}
	/**
	 * Remove value
	 */
	function removeItemIm($__ctx, $value)
	{
		return $this->removeValueIm($__ctx, $value);
	}
	/**
	 * Remove value
	 */
	function removeItemsIm($__ctx, $values)
	{
		$res = $this;
		for ($i = 0;$i < $values->count($__ctx);$i++)
		{
			$res = $res->removeItem($__ctx, $values->item($__ctx, $i));
		}
		return $res;
	}
	/**
	 * Map
	 * @param fn f
	 * @return Collection
	 */
	function map($__ctx, $f)
	{
		$arr2 = $this->copy($__ctx);
		foreach ($this->_arr as $key => $value)
		{
			$arr2->_arr[$key] = $f($__ctx, $value, $key);
		}
		return $arr2;
	}
	/**
	 * Filter items
	 * @param fn f
	 * @return Collection
	 */
	function filter($__ctx, $f)
	{
		$arr2 = static::Instance($__ctx);
		foreach ($this->_arr as $key => $value)
		{
			if ( $f($__ctx, $value, $key) )
			{
				$arr2->_arr[] = $value;
			}
		}
		return $arr2;
	}
	/**
	 * Transition Collection to Dict
	 * @param fn f
	 * @return Dict
	 */
	function transition($__ctx, $f)
	{
		$d = new \Runtime\Dict();
		foreach ($this->_arr as $key => $value)
		{
			$p = $f(__ctx, $value, $key);
			$d->map[$p[1]] = $p[0];
		}
		return $d;
	}
	/**
	 * Reduce
	 * @param fn f
	 * @param var init_value
	 * @return init_value
	 */
	function reduce($__ctx, $f, $init_value)
	{
		foreach ($this->_arr as $key => $value)
		{
			$init_value = $f($__ctx, $init_value, $value, $key);
		}
		return $init_value;
	}
	/**
	 * Call function for each item
	 * @param fn f
	 */
	function each($__ctx, $f)
	{
		foreach ($this->_arr as $key => $value)
		{
			$f($__ctx, $value, $key);
		}
	}
	/**
	 * Returns Collection
	 * @param Collection<T> arr
	 * @return Collection<T>
	 */
	function concat($__ctx, $arr=null)
	{
		if ($arr == null) return $this;
		$arr2 = static::Instance($__ctx);
		$arr2->_arr = array_merge($this->_arr, $v->_arr);
		return $arr2;
	}
	/**
	 * Returns Collection
	 * @param Collection<T> arr
	 * @return Collection<T>
	 */
	function intersect($__ctx, $arr)
	{
		return $this->filter($__ctx, function ($__ctx, $item) use (&$arr)
		{
			return $arr->indexOf($__ctx, $item) >= 0;
		});
	}
	/**
	 * Returns new Collection
	 * @param int offset
	 * @param int lenght
	 * @return Collection<T>
	 */
	function slice($__ctx, $offset, $length=null)
	{
		$arr2 = static::Instance($__ctx);
		$arr2->_arr = array_slice($this->_arr, $offset, $length);
		return $arr2;
	}
	/**
	 * Reverse array
	 */
	function reverseIm($__ctx)
	{
		$arr2 = $this->copy($__ctx);
		array_reverse($arr2->_arr);
		return $arr2;
	}
	/**
	 * Sort vector
	 * @param fn f - Sort user function
	 */
	function sortIm($__ctx, $f=null)
	{
		$res = $this->copy($__ctx);
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
	function removeDublicatesIm($__ctx)
	{
		$arr = []; $sz = count($this->_arr);
		for ($i=0; $i<$sz; $i++)
		{			
			$value = $this->_arr[$i];
			$pos = array_search($value, $arr, true);
			if ($pos === false)
			{
				$arr[] = $value;
			}
		}
		$res = static::Instance($__ctx);
		$res->_arr = $arr;
		return $res;
	}
	/**
	 * Find item pos
	 * @param fn f - Find function
	 * @return int - position
	 */
	function find($__ctx, $f)
	{
		$sz = count($this->_arr);
		for ($i=0; $i<$sz; $i++)
		{
			$elem = $this->_arr[$i];
			if ( $f($__ctx, $elem) )
			{
				return $i;
			}
		}
		return -1;
	}
	/**
	 * Find item
	 * @param var item - Find function
	 * @param fn f - Find function
	 * @param T def_value - Find function
	 * @return item
	 */
	function findItem($__ctx, $f, $def_value=null)
	{
		$pos = $this->find($__ctx, $f);
		return $this->get($__ctx, $pos, $def_value);
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.Collection";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Collection";
	}
	static function getParentClassName()
	{
		return "Runtime._Collection";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Collection",
			"name"=>"Runtime.Collection",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($__ctx,$field_name)
	{
		return null;
	}
	static function getMethodsList($__ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($__ctx,$field_name)
	{
		return null;
	}
}