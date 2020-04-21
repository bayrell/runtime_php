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
class CoreStruct extends \Runtime\CoreObject implements \ArrayAccess, \Runtime\Interfaces\SerializeInterface
{
	function __construct($ctx, $obj=null)
	{
		parent::__construct($ctx);
		static::_assign($ctx, $this, null, $obj);
	}
	/**
	 * Assign
	 */
	static function _assign($ctx, $item, $old_item, $obj)
	{
		if ($obj == null)
		{
			$item->initData($ctx, $old_item, $obj);
			return ;
		}
		if ($obj instanceof \Runtime\Dict)
		{
			foreach ($obj->_map as $key => $value)
			{
				$k = "__".$key;
				if (property_exists($item, $k))
					$item->$k = $value;
			}
		}
		else if (gettype($obj) == "array")
		{
			foreach ($obj as $key => $value)
			{
				$k = "__".$key;
				if (property_exists($item, $k))
					$item->$k = $value;
			}
		}
		
		$item->initData($old_item, $obj);
	}
	/**
	 * Init struct data
	 */
	function initData($ctx, $old, $changed=null)
	{
	}
	/**
	 * Copy this struct with new values
	 * @param Map obj = null
	 * @return CoreStruct
	 */
	function copy($ctx, $obj=null)
	{
		if ($obj == null)
		{
			return $this;
		}
		$item = clone $this;		
		static::_assign($ctx, $item, $this, $obj);
		return $item;
		return $this;
	}
	/**
	 * Copy this struct with new values
	 * @param Map obj = null
	 * @return CoreStruct
	 */
	function clone($ctx, $fields=null)
	{
		if ($fields == null)
		{
			return $this;
		}
		$obj = new \Runtime\Map($ctx);
		$fields->each($ctx, function ($ctx, $field_name) use (&$obj)
		{
			$obj->set($ctx, $field_name, $this->takeValue($ctx, $field_name));
		});
		/* Return object */
		$res = \Runtime\rtl::newInstance($ctx, $this->getClassName($ctx), \Runtime\Collection::from([$obj->toDict($ctx)]));
		return $res;
	}
	/**
	 * Create new struct with new value
	 * @param string field_name
	 * @param fn f
	 * @return CoreStruct
	 */
	function map($ctx, $field_name, $f)
	{
		return $this->copy($ctx, (new \Runtime\Map($ctx))->set($ctx, $field_name, $f($ctx, $this->takeValue($ctx, $field_name)))->toDict($ctx));
	}
	/**
	 * Returns new instance
	 */
	static function newInstance($ctx, $items)
	{
		$class_name = static::class;
		return new $class_name($ctx, $items);
	}
	function __get($k){$k="__".$k;return isset($this->$k)?$this->$k:null;}
	function __set($k,$v){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	function offsetExists($k){$k="__".$k;return isset($this->$k);}
	function offsetGet($k){$k="__".$k;return isset($this->$k)?$this->$k:null;}
	function offsetSet($k,$v){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	function offsetUnset($k){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.CoreStruct";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.CoreStruct";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreObject";
	}
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.CoreStruct",
			"name"=>"Runtime.CoreStruct",
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
	static function getMethodsList($ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}