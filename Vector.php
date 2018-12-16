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

class Vector implements \JsonSerializable{
	
	protected $_arr = [];
	
	
	/**
	 * Constructor
	 */
	public function __construct(){
		$this->_arr = [];
		$arr = func_get_args();
		foreach ($arr as $data){
			if (is_array($data)){
				foreach ($data as $item){
					if (!is_array($item)){
						$this->_arr[] = $item;
					}
				}
			}
			else{
				$this->_arr[] = $data;
			}
		}
	}
	
	
	
	/**
	 * Correct items
	 */
	public function _correctItemsByType($type){
		return $this->map(function($item) use ($type){
			return rtl::correct($item, $type, null);
		});
	}
	
	
	
	/**
	 * Destructor
	 */
	public function __destruct(){
		unset($this->_arr);
	}
	
	
	
	/**
	 * Assign arr
	 */
	public function _assignArr(&$arr){
		$this->_arr = $arr;
		return $this;
	}
	
	
	
	/**
	 * Get array
	 */
	public function _getArr(){
		return $this->_arr;
	}
	
	
	
	/**
	 * Returns new Instance
	 */
	public function createNewInstance(){
		$class_name = get_class($this);
		return new $class_name();
	}
	
	
	
	/**
	 * Assign all data from other object
	 * @param Vector obj
	 */
	public function assign($obj){
		$this->_arr = $obj->_arr;
	}
	
	
	/**
	 * Append value to the end of array
	 * @param T value
	 */
	public function push($value){
		$this->_arr[] = $value;
		return $this;
	}
	
	
	
	/**
	 * Insert first value size_to array
	 * @return T value
	 */
	public function unshift($value){
		array_unshift($this->_arr, $value);
		return $this;
	}
	
	
	
	/**
	 * Extract last value from array
	 * @return T value
	 */
	public function pop(){
		return array_pop($this->_arr);
	}
	
	
	
	/**
	 * Extract first value from array
	 * @return T value
	 */
	public function shift(){
		return array_shift($this->_arr);
	}
	
	
	
	/**
	 * Find value in array. Returns -1 if value not found.
	 * @param T value
	 * @return  int
	 */
	public function indexOf($value){
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
	public function indexOfRange($value, $pos_begin, $pos_end){
		$pos = $this->indexOf($value);
		if ($pos == -1 or $pos > $pos_end or $pos < $pos_begin)
			return -1;
		return $pos;
	}
	
	
	
	/**
	 * Insert value size_to position
	 * @param T value
	 * @param int pos - position
	 */
	public function insert($pos, $value){
		array_splice($this->_arr, $pos, 0, [$value]);
		return $this;
	}
	
	
	
	/**
	 * Remove value from position
	 * @param int pos - position
	 * @param int count - count remove items
	 */
	public function remove($pos, $count = 1){
		array_splice($this->_arr, $pos, $count);
		return $this;
	}
	
	
	
	/**
	 * Remove range
	 * @param int pos_begin - start position
	 * @param int pos_end - end position
	 */
	public function removeRange($pos_begin, $pos_end){
		$this->remove($pos_begin, $pos_end - $pos_begin + 1);
		return $this;
	}
	
	
	
	/**
	 * Returns value from position
	 * @param int pos - position
	 */
	public function get($pos, $default_value){
		return isset($this->_arr[$pos]) ? $this->_arr[$pos] : $default_value;
	}
	
	
	
	/**
	 * Returns value from position. Throw exception, if position does not exists
	 * @param int pos - position
	 */
	public function item($pos){
		if (!array_key_exists($pos, $this->_arr))
			throw new IndexOutOfRange();
		return $this->_arr[$pos];
	}
	
	
	
	/**
	 * Set value size_to position
	 * @param int pos - position
	 * @param T value 
	 */
	public function set($pos, $value){
		if (!array_key_exists($pos, $this->_arr))
			throw new IndexOutOfRange();
		$this->_arr[$pos] = $value;
		return this;
	}
	
	
	
	/**
	 * Clear all values from vector
	 */
	public function clear(){
		$this->_arr = [];
	}
	
	
	
	/**
	 * Returns count items in vector
	 */
	public function count(){
		return count($this->_arr);
	}
	
	
	
	/**
	 * Append value to the end of array
	 * @param T value
	 */
	public function append($value){
		$this->push($value);
		return $this;
	}
	
	
	
	/**
	 * Insert first value size_to array
	 * @return T value
	 */
	public function prepend($value){
		$this->unshift($value);
		return $this;
	}
	
	
	
	/**
	 * Append vector to the end of the vector
	 * @param Vector<T> arr
	 */
	public function appendVector($arr){
		$arr->each(function($item){
			$this->append($item);
		});
	}
	
	
	
	/**
	 * Prepend vector to the begin of the vector
	 * @param Vector<T> arr
	 */
	public function prependVector($arr){
		$arr->each(function($item){
			$this->prepend($item);
		});
	}
	
	
	
	/**
	 * Returns count items in vector
	 */
	public function length(){
		return count($this->_arr);
	}
	
	
	
	/**
	 * Get last item
	 */
	public function getLastItem($default_value = null){
		$c = count($this->_arr);
		return isset($this->_arr[$c-1]) ? $this->_arr[$c-1] : $default_value;
	}
	public function last($default_value = null){ return this.getLastItem($default_value); }
	
	
	
	/**
	 * Remove value
	 * @param mixed value
	 */
	public function removeValue($value){
		$index = $this->indexOf($value);
		if ($index != -1)
			$this->remove($index, 1);
	}
	
	
	
	/**
	 * Remove value
	 * @param mixed value
	 */
	public function removeItem($value){
		$index = $this->indexOf($value);
		if ($index != -1)
			$this->remove($index, 1);
	}
	
	
	
	/**
	 * Map
	 * @param func f
	 * @return Vector
	 */
	function map($f){
		$keys = array_keys($this->_arr);
		$arr2 = $this->createNewInstance();
		$arr2->_arr = array_map($f, $this->_arr, $keys);
		return $arr2;
	}
	
	
	
	/**
	 * Filter items
	 * @param func f
	 * @return Vector
	 */
	function filter($f){
		$arr2 = $this->createNewInstance();
		$arr2->_arr = array_filter($this->_arr, $f);
		return $arr2;
	}
	
	
	
	/**
	 * Reduce
	 * @param func f
	 * @param mixed init_value
	 * @return init_value
	 */
	function reduce($f, $init_value){
		return array_reduce($this->_arr, $f, $init_value);
	}
	
	
	
	/**
	 * Call function for each item
	 * @param func f
	 */
	function each($f){
		array_walk($this->_arr, $f);
	}
	
	
	
	/**
	 * Returns new concated Vector
	 * @param Vector v
	 * @return Vector
	 */
	function concat($v){
		$arr2 = $this->createNewInstance();
		$arr2->_arr = array_merge($this->_arr, $v->_arr);
		return $arr2;
	}
	
	
	
	/**
	 * Returns Vector
	 * @param int offset begin
	 * @param int length count
	 * @return Vector<T>
	 */
	function slice($offset = 0, $length = null){
		$arr2 = $this->createNewInstance();
		$arr2->_arr = array_slice($this->_arr, $offset, $length);
		return $arr2;
	}
	
	
	
	/**
	 * Returns copy of the Vector
	 * @return Vector<T>
	 */
	function copy(){
		$arr2 = $this->createNewInstance();
		if ($this->_arr == null) $arr2->_arr = [];
		else $arr2->_arr = array_slice($this->_arr, 0);
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
	}
	
	
	
	/**
	 * Sort vector
	 * @param callback f - Sort user function
	 */
	public function sort($f = null){
		if ($f == null){
			asort($this->_arr);
		}
		else{
			usort($this->_arr, $f);
		}
	}
	
	
	
	/**
	 * Swap item1 to item2
	 * @params int index1 - item1 position
	 * @params int index2 - item2 position. If index2 = -1, insert as last item
	 */
	public function swap($index1, $index2){
		if ($index2 < 0){
			$index2 += $this->count();
		}
		$item1 = $this->item($index1);
		if ($index2 == -1){
			$this->remove($index1, 1);
			$this->push($item1);
		}
		else if ($index1 > $index2){
			$item2 = $this->item($index2);
			$this->insert($index1, $item2);
			$this->remove($index1 + 1, 1);			
			$this->insert($index2, $item1);
			$this->remove($index2 + 1, 1);
		}
		else if (index1 < index2){
			$item2 = $this->item($index2);
			$this->insert($index2, $item1);
			$this->remove($index2 + 1, 1);
			$this->insert($index1, $item2);
			$this->remove($index1 + 1, 1);			
		}
	}
	
	
	
	/**
	 * Remove dublicate values
	 */
	public function removeDublicates(){
		$arr = [];
		for ($i=0; $i<$this->count();$i++){			
			$value = $this->item($i);
			$pos = array_search($value, $arr, true);
			if ($pos === false){
				$arr[] = $value;
			}
		}
		$this->_arr = $arr;
	}
}