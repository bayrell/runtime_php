<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2020 "Ildar Bikmamatov" <support@bayrell.org>
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
class Vector extends \Runtime\Collection
{
	/**
	 * Returns new Instance
	 * @return Object
	 */
	static function Instance($ctx)
	{
		return new \Runtime\Vector($ctx);
	}
	/**
	 * Returns new Vector
	 * @param int offset
	 * @param int lenght
	 * @return Collection<T>
	 */
	function vectorSlice($ctx, $offset, $length=null)
	{
		$arr2 = static::Instance($ctx);
		$arr2->_arr = array_slice($this->_arr, $offset, $length);
		return $arr2;
	}
	/**
	 * Append value to the end of array
	 * @param T value
	 */
	function pushValue($ctx, $value)
	{
		$this->_arr[] = $value;
		return $this;
	}
	/**
	 * Insert first value size_to array
	 * @return T value
	 */
	function unshiftValue($ctx, $value)
	{
		array_unshift($this->_arr, $value);
		return $this;
	}
	/**
	 * Extract last value from array
	 * @return T value
	 */
	function popValue($ctx)
	{
		return array_pop($this->_arr);
	}
	/**
	 * Extract first value from array
	 * @return T value
	 */
	function shiftValue($ctx)
	{
		array_shift($this->_arr);
		return $this;
	}
	/**
	 * Insert value to position
	 * @param T value
	 * @param int pos - position
	 */
	function insertValue($ctx, $pos, $value)
	{
		array_splice($this->_arr, $pos, 0, [$value]);
		return $this;
	}
	/**
	 * Remove value from position
	 * @param int pos - position
	 * @param int count - count remove items
	 */
	function removePosition($ctx, $pos, $count=1)
	{
		array_splice($this->_arr, $pos, $count);
		return $this;
	}
	/**
	 * Remove value
	 */
	function removeValue($ctx, $value)
	{
		$index = $this->indexOf($ctx, $value);
		if ($index != -1)
		{
			$this->removePosition($ctx, $index, 1);
		}
		return $this;
	}
	/**
	 * Remove value
	 */
	function removeValues($ctx, $values)
	{
		for ($i = 0;$i < $values->count($ctx);$i++)
		{
			$this->removeValue($ctx, $values->item($ctx, $i));
		}
		return $this;
	}
	/**
	 * Remove range
	 * @param int pos_begin - start position
	 * @param int pos_end - end position
	 */
	function removeRangeValues($ctx, $pos_begin, $pos_end)
	{
		$this->remove($pos_begin, $pos_end - $pos_begin + 1);
		return $this;
	}
	/**
	 * Set value size_to position
	 * @param int pos - position
	 * @param T value 
	 */
	function setValue($ctx, $pos, $value)
	{
		if (!array_key_exists($pos, $this->_arr))
		{
			throw new IndexOutOfRange();
		}
		$this->_arr[$pos] = $value;
		return $this;
	}
	/**
	 * Clear all values from vector
	 */
	function clear($ctx)
	{
		$this->_arr = [];
		return $this;
	}
	/**
	 * Append value to the end of the vector
	 * @param T value
	 */
	function appendValue($ctx, $value)
	{
		$this->push($ctx, $value);
		return $this;
	}
	/**
	 * Insert first value to begin of the vector
	 * @return T value
	 */
	function prependValue($ctx, $value)
	{
		$this->unshift($ctx, $value);
		return $this;
	}
	/**
	 * Append vector to the end of the vector
	 * @param Vector<T> arr
	 */
	function appendVector($ctx, $arr)
	{
		if (!$arr) return $this;
		foreach ($arr->_arr as $key => $value)
		{
			$this->_arr[] = $value;
		}
		return $this;
	}
	/**
	 * Prepend vector to the begin of the vector
	 * @param Vector<T> arr
	 */
	function prependVector($ctx, $arr)
	{
		if (!$arr) return $this;
		foreach ($arr->_arr as $key => $value)
		{
			array_unshift($this->_arr, $value);
		}
		return $this;
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.Vector";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Vector";
	}
	static function getParentClassName()
	{
		return "Runtime.Collection";
	}
	static function getClassInfo($ctx)
	{
		return \Runtime\Dict::from([
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		return null;
	}
	static function getMethodsList($ctx,$f=0)
	{
		$a = [];
		if (($f&4)==4) $a=[
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}