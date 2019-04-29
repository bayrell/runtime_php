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
use Runtime\Collection;
use Runtime\Exceptions\IndexOutOfRange;

class Vector extends \Runtime\Collection
{
	
	
	/**
	 * Assign arr
	 */
	public function _assignArr(&$arr)
	{
		$this->_arr = $arr;
		return $this;
	}
	
	
	
	/**
	 * Append value to the end of array
	 * @param T value
	 */
	public function push($value)
	{
		$this->_arr[] = $value;
		return $this;
	}
	
	
	
	/**
	 * Insert first value size_to array
	 * @return T value
	 */
	public function unshift($value)
	{
		array_unshift($this->_arr, $value);
		return $this;
	}
	
	
	
	/**
	 * Extract last value from array
	 * @return T value
	 */
	public function pop()
	{
		return array_pop($this->_arr);
	}
	
	
	
	/**
	 * Extract first value from array
	 * @return T value
	 */
	public function shift()
	{
		return array_shift($this->_arr);
	}
	
	
	
	/**
	 * Insert value size_to position
	 * @param T value
	 * @param int pos - position
	 */
	public function insert($pos, $value)
	{
		array_splice($this->_arr, $pos, 0, [$value]);
		return $this;
	}
	
	
	
	/**
	 * Remove value from position
	 * @param int pos - position
	 * @param int count - count remove items
	 */
	public function remove($pos, $count = 1)
	{
		array_splice($this->_arr, $pos, $count);
		return $this;
	}
	
	
	
	/**
	 * Remove range
	 * @param int pos_begin - start position
	 * @param int pos_end - end position
	 */
	public function removeRange($pos_begin, $pos_end)
	{
		$this->remove($pos_begin, $pos_end - $pos_begin + 1);
		return $this;
	}
	
	
	
	/**
	 * Set value size_to position
	 * @param int pos - position
	 * @param T value 
	 */
	public function set($pos, $value)
	{
		if (!array_key_exists($pos, $this->_arr))
			throw new IndexOutOfRange();
		$this->_arr[$pos] = $value;
		return $this;
	}
	
	
	
	/**
	 * Clear all values from vector
	 */
	public function clear()
	{
		$this->_arr = [];
		return $this;
	}
	
	
	
	/**
	 * Append value to the end of array
	 * @param T value
	 */
	public function append($value)
	{
		$this->push($value);
		return $this;
	}
	
	
	
	/**
	 * Insert first value size_to array
	 * @return T value
	 */
	public function prepend($value)
	{
		$this->unshift($value);
		return $this;
	}
	
	
	
	/**
	 * Append vector to the end of the vector
	 * @param Vector<T> arr
	 */
	public function appendVector($arr)
	{
		if (!$arr) return $this;
		$arr->each(
			function($item){
				$this->append($item);
			}
		);
		return $this;
	}
	
	
	
	/**
	 * Prepend vector to the begin of the vector
	 * @param Vector<T> arr
	 */
	public function prependVector($arr)
	{
		if (!$arr) return $this;
		$arr->each(
			function($item){
				$this->prepend($item);
			}
		);
		return $this;
	}
	
	
	
	/**
	 * Remove value
	 * @param mixed value
	 */
	public function removeValue($value)
	{
		$index = $this->indexOf($value);
		if ($index != -1)
			$this->remove($index, 1);
		return $this;
	}
	
	
	
	/**
	 * Remove value
	 * @param mixed value
	 */
	public function removeItem($value)
	{
		return $this->removeValue($value);
	}
	
	
	
	/**
	 * Remove values
	 * @param mixed values
	 */
	public function removeItems($values)
	{
		for ($i=0; $i<$values->count(); $i++)
		{
			$this->removeItem( $values->item(i) );
		}
		return $this;
	}
	
	
	public function getClassName(){return "Runtime.Vector";}
	public static function getCurrentClassName(){return "Runtime.Vector";}
	public static function getParentClassName(){return "Runtime.Collection";}
	
}