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
use Runtime\Vector;
use Runtime\Exceptions\KeyNotFound;

class Map implements \JsonSerializable{
	
	
	protected $_map = null;
	
	
	
	/**
	 * Correct items
	 */
	public function _correctItemsByType($type){
		return $this->map(function($key, $value) use ($type){
			return rtl::correct($value, $type, null);
		});
	}
	
	
	
	/**
	 * Constructor
	 */
	public function __construct($map = null){
		$this->_map = [];
		if ($map instanceof Map){
			foreach ($map->_map as $key => $value){
				$key = rtl::toString($key);
				$this->_map[$key] = $value;
			}		
		}
		else if (is_array($map)){
			foreach ($map as $key => $value){
				$key = rtl::toString($key);
				$this->_map[$key] = $value;
			}		
		}
		else if (is_object($map)){
			$values = get_object_vars($map);
			foreach ($values as $key => $value){
				$key = rtl::toString($key);
				$this->_map[$key] = $value;
			}
		}
	}
	
	
	
	/**
	 * Destructor
	 */
	public function __destruct(){
		unset($this->_map);
	}
	
	
	/**
	 * Returns value from position
	 * @param T key
	 * @param T default_value
	 * @return T
	 */
	public function get($key, $default_value, $type_value = "mixed", $type_template = ""){
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
	public function item($key, $type_value = "mixed", $type_template = ""){
		$key = rtl::toString($key);
		if (!array_key_exists($key, $this->_map)){
			throw new KeyNotFound(null, $key);
		}
		return $this->_map[$key];
	}
	
	
	
	/**
	 * Set value size_to position
	 * @param T pos - position
	 * @param T value 
	 * @return self
	 */
	public function set($key, $value){
		$key = rtl::toString($key);
		$this->_map[$key] = $value;
		return $this;
	}
	
	
	
	/**
	 * Remove value from position
	 * @param T key
	 * @return self
	 */
	public function remove($key){
		$key = rtl::toString($key);
		if (isset($this->_map[$key]))
			unset($this->_map[$key]);
		return $this;
	}
	
	
	
	/**
	 * Return true if key exists
	 * @param T key
	 * @return bool var
	 */
	public function contains($key){
		$key = rtl::toString($key);
		return isset($this->_map[$key]);
	}
	
	
	
	/**
	 * Return true if key exists
	 * @param T key
	 * @return bool var
	 */
	public function has($key){
		$key = rtl::toString($key);
		return isset($this->_map[$key]);
	}
	
	
	
	/**
	 * Clear all values from vector
	 * @return self
	 */
	public function clear(){
		$this->_map = [];
		return $this;
	}

	
	
	/**
	 * Returns count items in vector
	 */
	public function count(){
		return count($this->_map);
	}
	
	
	
	/**
	 * Returns vector of the keys
	 * @return Vector<T>
	 */
	public function keys(){
		$keys = array_keys($this->_map);
		$res = new Vector();
		$res->_assignArr($keys);		
		return $res;
	}
	
	
	
	/**
	 * Returns vector of the values
	 * @return Vector<T>
	 */
	public function values(){
		$values = array_values($this->_map);
		$res = new Vector();
		$res->_assignArr($values);		
		return $res;
	}
	
	
	
	/**
	 * Call function for each item
	 * @param func f
	 */
	function each($f){
		$keys = array_keys($this->_map);
		array_walk($keys, function($key) use ($f){
			$value = $this->item($key);
			$f($key, $value);
		});
		return $this;
	}
	
	
	
	/**
	 * Call function map
	 * @param func f
	 * @return Map
	 */
	function map($f){
		$res = new Map();
		$this->each(function($key) use ($res, $f){
			$value = $this->item($key);
			$res->set($key, $f($key, $value));
		});
		return $res;
	}
	
	
	
	/**
	 * Reduce
	 * @param func f
	 * @param mixed init_value
	 * @return init_value
	 */
	function reduce($f, $init_value){
		$res = $init_value;
		$this->each(function ($key, $value) use (&$res, $f){
			$res = $f($res, $key, $value);
		});
		return $res;
	}
	
	
	
	/**
	 * Add values from other map
	 * @param Map<T, T> map
	 * @return self
	 */
	function addMap($map){
		if ($map != null)
			$map->each(function ($key) use ($map){
				$this->set($key, $map->item($key));
			});
		return $this;
	}
	
	
	
	/**
	 * Returns copy of the current Map
	 */
	function copy(){
		$instance = new \Runtime\Map();
		$this->each(function ($key, $value) use ($instance){
			$instance->set($key, $value);
		});
		return $instance;
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
}