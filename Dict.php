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
use Runtime\rtl;
use Runtime\Exceptions\KeyNotFound;

class Dict implements \JsonSerializable
{
	
	
	protected $_map = null;
	
	
	/**
	 * Returns map
	 */
	public function _getArr()
	{
		return $this->_map;
	}
	
	
	
	/**
	 * Returns new Instance
	 */
	public static function create($obj=null)
	{
		$class_name = static::class;
		return new $class_name($obj);
	}
	
	
	
	/**
	 * Returns new Instance
	 */
	public static function createNewInstance($obj=null)
	{
		$class_name = static::class;
		return new $class_name($obj);
	}
	
	
	
	/**
	 * Returns copy of the current Dict
	 */
	function copy()
	{
		return static::createNewInstance($this->_map);
	}
	
	
	
	/**
	 * Convert to dict
	 */
	public function toDict()
	{
		return new \Runtime\Dict($this);
	}
	
	
	
	/**
	 * Convert to map
	 */
	public function toMap()
	{
		return new \Runtime\Map($this);
	}
	
	
	
	/**
	 * Correct items
	 */
	public function _correctItemsByType($type)
	{
		if ($type == "mixed" or $type == "primitive" or $type == "var") return $this;
		
		return $this->map(
			function($key, $value) use ($type)
			{
				return rtl::correct($value, $type, null);
			}
		);
	}
	
	
	
	/**
	 * Constructor
	 */
	public function __construct($map = null)
	{
		$this->_map = [];
		if ($map instanceof Dict)
		{
			foreach ($map->_map as $key => $value)
			{
				$key = rtl::toString($key);
				$this->_map[$key] = $value;
			}		
		}
		else if (is_array($map))
		{
			foreach ($map as $key => $value)
			{
				$key = rtl::toString($key);
				$this->_map[$key] = $value;
			}		
		}
		else if (is_object($map))
		{
			$values = get_object_vars($map);
			foreach ($values as $key => $value)
			{
				$key = rtl::toString($key);
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
	 * Return true if key exists
	 * @param T key
	 * @return bool var
	 */
	public function contains($key)
	{
		$key = rtl::toString($key);
		return isset($this->_map[$key]);
	}
	
	
	
	/**
	 * Return true if key exists
	 * @param T key
	 * @return bool var
	 */
	public function has($key)
	{
		$key = rtl::toString($key);
		return isset($this->_map[$key]);
	}
	
	
	
	/**
	 * Returns value from position
	 * @param T key
	 * @param T default_value
	 * @return T
	 */
	public function get($key, $default_value, $type_value = "mixed", $type_template = "")
	{
		$key = rtl::toString($key);
		$val = isset($this->_map[$key]) ? $this->_map[$key] : $default_value;
		$val = rtl::convert($val, $type_value, $default_value, $type_template);
		return $val;
	}
	
	
	
	/**
	 * Returns value from position. Throw exception, if position does not exists
	 * @param T key - position
	 * @return T
	 */
	public function item($key, $type_value = "mixed", $type_template = "")
	{
		$key = rtl::toString($key);
		if (!array_key_exists($key, $this->_map)){
			throw new KeyNotFound($key);
		}
		return $this->_map[$key];
	}
	
	
	
	/**
	 * Set value size_to position
	 * @param T pos - position
	 * @param T value 
	 * @return self
	 */
	public function setIm($key, $value)
	{
		$res = $this->copy();
		$key = rtl::toString($key);
		$res->_map[$key] = $value;
		return $res;
	}
	
	
	
	/**
	 * Remove value from position
	 * @param T key
	 * @return self
	 */
	public function removeIm($key)
	{
		$key = rtl::toString($key);
		if (isset($this->_map[$key]))
		{
			$res = $this->copy();
			unset($res->_map[$key]);
			return $res;
		}
		return $this;
	}
	
	
	
	/**
	 * Returns count items in vector
	 */
	public function count()
	{
		return count($this->_map);
	}
	
	
	
	/**
	 * Returns vector of the keys
	 * @return Vector<T>
	 */
	public function keys()
	{
		$keys = array_keys($this->_map);
		$res = \Runtime\Collection::create($keys);
		return $res;
	}
	
	
	
	/**
	 * Returns vector of the values
	 * @return Vector<T>
	 */
	public function values()
	{
		$values = array_values($this->_map);
		$res = \Runtime\Collection::create($values);
		return $res;
	}
	
	
	
	/**
	 * Call function for each item
	 * @param func f
	 */
	function each($f)
	{
		foreach ($this->_map as $key => $value)
		{
			$f( $key, $value );
		}
		return $this;
	}
	
	
	
	/**
	 * Call function map
	 * @param func f
	 * @return Dict
	 */
	function map($f)
	{
		$res = [];
		foreach ($this->_map as $key => $value)
		{
			$res[$key] = $f( $key, $value );
		}
		return static::createNewInstance($res);
	}
	
	
	
	/**
	 * Filter items
	 * @param func f
	 * @return Dict
	 */
	function filter($f)
	{
		$arr2 = static::createNewInstance();
		$arr2->_map = [];
		foreach ($this->_map as $key => $value)
		{
			if ($f($key, $value))
			{
				$arr2->_map[$key] = $value;
			}
		}
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
		$res = $init_value;
		foreach ($this->_map as $key => $value)
		{
			$res = $f($res, $key, $value );
		}
		return $res;
	}
	
	
	
	/**
	 * Add values from other map
	 * @param Dict<T, T> map
	 * @return self
	 */
	function concat($map)
	{
		if ($map != null)
		{
			$res = $this->copy();
			$map->each(
				function ($key) use ($map, $res)
				{
					$res->_map[$key] = $map->item($key);
				}
			);
			return $res;
		}
		return $this;
	}
	
	
	
	/**
	 * Get and set methods
	 */
	function __set($name, $value){
		return $this->set($name, $value);
	}
	function __get($name){
		return $this->get($name, null);
	}
	function __isset($name){
		return $this->has($name);
	}
	function __unset($name){
		return $this->remove($name);
	}
	
	
	
	/**
	 * JsonSerializable
	 */
	public function jsonSerialize(){
		return (object) $this->_map;
	}
	
	
	public function getClassName(){return "Runtime.Dict";}
	public static function getCurrentClassName(){return "Runtime.Dict";}
	public static function getParentClassName(){return "";}
}