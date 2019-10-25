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
class _Map implements \ArrayAccess, \JsonSerializable
{
	public $_map = [];
	
	
	/**
	 * From
	 */
	static function from($map)
	{
		$class_name = static::class;
		$res = new $class_name(null);
		if ($map != null)
		{
			foreach ($map as $key => $value)
			{
				$key = $res->toStr($key);
				$res->_map[$key] = $value;
			}
		}
		else if (is_object($map))
		{
			$values = get_object_vars($map);
			foreach ($values as $key => $value)
			{
				$key = $res->toStr($key);
				$res->_map[$key] = $value;
			}
		}
		return $res;	
	}
	
	
	/**
	 * JsonSerializable
	 */
	public function toStr($value)
	{
		return rtl::toStr($value);
	}
	
	
	/**
	 * JsonSerializable
	 */
	public function jsonSerialize()
	{
		return (object) $this->_map;
	}
	
	
	/**
	 * Constructor
	 */
	public function __construct($__ctx, $map=null)
	{
		$this->_map = [];
		if ($map == null) {}
		else if ($map instanceof Dict)
		{
			foreach ($map->_map as $key => $value)
			{
				$key = $this->toStr($key);
				$this->_map[$key] = $value;
			}		
		}
		else if (is_array($map))
		{
			foreach ($map as $key => $value)
			{
				$key = $this->toStr($key);
				$this->_map[$key] = $value;
			}
		}
		else if (is_object($map))
		{
			$values = get_object_vars($map);
			foreach ($values as $key => $value)
			{
				$key = $this->toStr($key);
				$this->_map[$key] = $value;
			}
		}
	}
	
	
	/**
	 * Destructor
	 */
	public function __destruct()
	{
		unset($this->_map);
	}
	
	
	/**
	 * Get array
	 */
	public function _getArr()
	{
		return $this->_map;
	}
	
	
	/**
	 * Get and set methods
	 */
	function __isset($k){return $this->has($k);}
	function __get($k){return $this->item($k);}
	function __set($k,$v){throw new \Runtime\Exceptions\AssignStructValueError($k);}
	function __unset($k){throw new \Runtime\Exceptions\AssignStructValueError($k);}
	public function offsetExists($k){return $this->has($k);}
	public function offsetGet($k){return $this->item($k);}
	public function offsetSet($k,$v){throw new \Runtime\Exceptions\AssignStructValueError($k);}
	public function offsetUnset($k){throw new \Runtime\Exceptions\AssignStructValueError($k);}
	
	/* Class name */
	public function getClassName(){return "Runtime._Map";}
	public static function getCurrentClassName(){return "Runtime._Map";}
	public static function getParentClassName(){return "";}
}
class Dict extends \Runtime\_Map
{
	/**
	 * Returns new Instance
	 * @return Object
	 */
	static function Instance($__ctx)
	{
		return new \Runtime\Dict($__ctx);
	}
	/**
	 * Returns new Instance
	 * @return Object
	 */
	static function create($__ctx, $obj)
	{
		$class_name = static::class;
		return new $class_name($obj);
	}
	/**
	 * Returns copy of Dict
	 * @param int pos - position
	 */
	function copy($__ctx)
	{
		$new_obj = static::Instance($__ctx);
		$new_obj->_map = $this->_map;
		return $new_obj;
	}
	/**
	 * Convert to dict
	 */
	function toDict($__ctx)
	{
		return new \Runtime\Dict($__ctx, $this);
	}
	/**
	 * Convert to dict
	 */
	function toMap($__ctx)
	{
		return new \Runtime\Map($__ctx, $this);
	}
	/**
	 * Return true if key exists
	 * @param string key
	 * @return bool var
	 */
	function contains($__ctx, $key)
	{
		$key = $this->toStr($key);
		return isset($this->_map[$key]);
	}
	/**
	 * Return true if key exists
	 * @param string key
	 * @return bool var
	 */
	function has($__ctx, $key)
	{
		return $this->contains($__ctx, $key);
	}
	/**
	 * Returns value from position
	 * @param string key
	 * @param T default_value
	 * @return T
	 */
	function get($__ctx, $key, $default_value)
	{
		$key = $this->toStr($key);
		$val = isset($this->_map[$key]) ? $this->_map[$key] : $default_value;
		return $val;
	}
	/**
	 * Returns value from position. Throw exception, if position does not exists
	 * @param string key - position
	 * @return T
	 */
	function item($__ctx, $key)
	{
		$key = $this->toStr($key);
		if (!array_key_exists($key, $this->_map))
		{
			throw new KeyNotFound($key);
		}
		return $this->_map[$key];
	}
	/**
	 * Set value size_to position
	 * @param string key - position
	 * @param T value 
	 * @return self
	 */
	function setIm($__ctx, $key, $value)
	{
		$res = $this->copy($__ctx);
		$key = $this->toStr($key);
		$res->_map[$key] = $value;
		return $res;
	}
	/**
	 * Remove value from position
	 * @param string key
	 * @return self
	 */
	function removeIm($__ctx, $key)
	{
		$key = $this->toStr($key);
		if (isset($this->_map[$key]))
		{
			$res = $this->copy($__ctx);
			unset($res->_map[$key]);
			return $res;
		}
		return $this;
	}
	/**
	 * Returns vector of the keys
	 * @return Collection<string>
	 */
	function keys($__ctx)
	{
		$keys = array_keys($this->_map);
		$res = \Runtime\Collection::from($keys);
		return $res;
	}
	/**
	 * Returns vector of the values
	 * @return Collection<T>
	 */
	function values($__ctx)
	{
		$values = array_values($this->_map);
		$res = \Runtime\Collection::from($values);
		return $res;
	}
	/**
	 * Call function map
	 * @param fn f
	 * @return Dict
	 */
	function map($__ctx, $f)
	{
		$map2 = static::Instance($__ctx);
		foreach ($this->_map as $key => $value)
		{
			$new_val = $f($__ctx, $value, $key);
			$map2->_map[$key] = $new_val;
		}
		return $map2;
	}
	/**
	 * Filter items
	 * @param fn f
	 * @return Collection
	 */
	function filter($__ctx, $f)
	{
		$map2 = static::Instance($__ctx);
		foreach ($this->_map as $key => $value)
		{
			$flag = $f($__ctx, $value, $key);
			if ($flag) $map2->_map[$key] = $value;
		}
		return $map2;
	}
	/**
	 * Call function for each item
	 * @param fn f
	 */
	function each($__ctx, $f)
	{
		foreach ($this->_map as $key => $value)
		{
			$f($__ctx, $value, $key);
		}
	}
	/**
	 * Transition Dict to Collection
	 * @param fn f
	 * @return Collection
	 */
	function transition($__ctx, $f)
	{
		$arr = new \Runtime\Collection($__ctx);
		foreach ($this->_map as $key => $value)
		{
			$arr->_arr[] = $f($__ctx, $value, $key);
		}
		return $arr;
	}
	/**
	 * 	
	 * @param fn f
	 * @param var init_value
	 * @return init_value
	 */
	function reduce($__ctx, $f, $init_value)
	{
		foreach ($this->_map as $key => $value)
		{
			$init_value = $f($__ctx, $init_value, $value, $key);
		}
		return $init_value;
	}
	/**
	 * Add values from other map
	 * @param Dict<T> map
	 * @return self
	 */
	function concat($__ctx, $map=null)
	{
		if ($map == null) return $this;
		$res = $this->copy($__ctx);
		foreach ($this->_map as $key => $value)
		{
			$res->_map[$key] = $value;
		}
		return $res;
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.Dict";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Dict";
	}
	static function getParentClassName()
	{
		return "Runtime._Map";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Dict",
			"name"=>"Runtime.Dict",
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