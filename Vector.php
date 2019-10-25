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
class Vector extends \Runtime\Collection
{
	/**
	 * Returns new Instance
	 * @return Object
	 */
	static function Instance($__ctx)
	{
		return new \Runtime\Vector($__ctx);
	}
	/**
	 * Append value to the end of array
	 * @param T value
	 */
	function push($__ctx, $value)
	{
		$this->_arr[] = $value;
		return $this;
	}
	/**
	 * Insert first value size_to array
	 * @return T value
	 */
	function unshift($__ctx, $value)
	{
		array_unshift($this->_arr, $value);
		return $this;
	}
	/**
	 * Extract last value from array
	 * @return T value
	 */
	function pop($__ctx)
	{
		return array_pop($this->_arr);
	}
	/**
	 * Extract first value from array
	 * @return T value
	 */
	function shift($__ctx)
	{
		return array_shift($this->_arr);
	}
	/**
	 * Insert value to position
	 * @param T value
	 * @param int pos - position
	 */
	function insert($__ctx, $pos, $value)
	{
		array_splice($this->_arr, $pos, 0, [$value]);
		return $this;
	}
	/**
	 * Remove value from position
	 * @param int pos - position
	 * @param int count - count remove items
	 */
	function remove($__ctx, $pos, $count=1)
	{
		array_splice($this->_arr, $pos, $count);
		return $this;
	}
	/**
	 * Remove range
	 * @param int pos_begin - start position
	 * @param int pos_end - end position
	 */
	function removeRange($__ctx, $pos_begin, $pos_end)
	{
		$this->remove($pos_begin, $pos_end - $pos_begin + 1);
		return $this;
	}
	/**
	 * Set value size_to position
	 * @param int pos - position
	 * @param T value 
	 */
	function set($__ctx, $pos, $value)
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
	function clear($__ctx)
	{
		$this->_arr = [];
		return $this;
	}
	/**
	 * Append value to the end of the vector
	 * @param T value
	 */
	function append($__ctx, $value)
	{
		$this->push($__ctx, $value);
		return $this;
	}
	/**
	 * Insert first value to begin of the vector
	 * @return T value
	 */
	function prepend($__ctx, $value)
	{
		$this->unshift($__ctx, $value);
		return $this;
	}
	/**
	 * Append vector to the end of the vector
	 * @param Vector<T> arr
	 */
	function appendVector($__ctx, $arr)
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
	function prependVector($__ctx, $arr)
	{
		if (!$arr) return $this;
		foreach ($arr->_arr as $key => $value)
		{
			array_unshift($this->_arr, $value);
		}
		return $this;
	}
	/**
	 * Remove value
	 */
	function removeValue($__ctx, $value)
	{
		$index = $this->indexOf($__ctx, $value);
		if ($index != -1)
		{
			$this->remove($__ctx, $index, 1);
		}
		return $this;
	}
	/**
	 * Remove value
	 */
	function removeItem($__ctx, $value)
	{
		return $this->removeValue($__ctx, $value);
	}
	/**
	 * Remove value
	 */
	function removeItems($__ctx, $values)
	{
		for ($i = 0;$i < $values->count($__ctx);$i++)
		{
			$this->removeValue($__ctx, $values->item($__ctx, $i));
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
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Vector",
			"name"=>"Runtime.Vector",
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