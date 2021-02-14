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
class BaseStruct extends \Runtime\BaseObject implements \Runtime\SerializeInterface
{
	function __construct($ctx, $obj=null)
	{
		parent::__construct($ctx);
		static::_assign($ctx, $this, null, $obj);
	}
	/**
	 * Returns field value
	 */
	static function _initDataGet($ctx, $old, $changed, $field_name)
	{
		return ($changed != null && $changed->has($ctx, $field_name)) ? (\Runtime\rtl::get($ctx, $changed, $field_name)) : (\Runtime\rtl::get($ctx, $old, $field_name));
	}
	/**
	 * Init struct data
	 */
	static function _initData($ctx, $old, $changed)
	{
		return $changed;
	}
	/**
	 * Assign
	 */
	static function _assign($ctx, $new_item, $old_item, $obj)
	{
		$obj = \Runtime\rtl::convert($ctx, $obj, "Runtime.Dict");
		$obj = $new_item::_initData($ctx, $old_item, $obj);
		if ($obj == null)
		{
			return ;
		}
		$check_types = false;
		$class_name = $new_item->getClassName($ctx);
		/* Enable check types */
		$check_types = true;
		if ($class_name == "Runtime.IntrospectionClass")
		{
			$check_types = false;
		}
		if ($class_name == "Runtime.IntrospectionInfo")
		{
			$check_types = false;
		}
		if ($obj instanceof \Runtime\Dict) $obj = $obj->_map;
		if (gettype($obj) == "array")
		{
			foreach ($obj as $key => $value)
			{
				$k = $new_item->__getKey($key);
				if (property_exists($new_item, $k))
				{
					if ($check_types)
					{
						$info = \Runtime\rtl::getFieldInfo($ctx, $class_name, $key);
						if ($info)
						{
							$value = \Runtime\rtl::convert($ctx, $value, $info->t, null);
						}
					}
					$new_item->$k = $value;
				}
			}
		}
	}
	/**
	 * Copy this struct with new values
	 * @param Map obj = null
	 * @return BaseStruct
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
	 * @return BaseStruct
	 */
	function clone($ctx, $obj=null)
	{
		return $this->copy($ctx, $obj);
	}
	/**
	 * Clone this struct with fields
	 * @param Collection fields = null
	 * @return BaseStruct
	 */
	function intersect($ctx, $fields=null)
	{
		if ($fields == null)
		{
			return \Runtime\Dict::from([]);
		}
		$obj = new \Runtime\Map($ctx);
		$fields->each($ctx, function ($ctx, $field_name) use (&$obj)
		{
			$obj->setValue($ctx, $field_name, $this->takeValue($ctx, $field_name));
		});
		/* Return object */
		$res = \Runtime\rtl::newInstance($ctx, $this->getClassName($ctx), \Runtime\Collection::from([$obj->toDict($ctx)]));
		return $res;
	}
	/**
	 * Create new struct with new value
	 * @param string field_name
	 * @param fn f
	 * @return BaseStruct
	 */
	function map($ctx, $field_name, $f)
	{
		return $this->copy($ctx, (new \Runtime\Map($ctx))->setValue($ctx, $field_name, $f($ctx, $this->takeValue($ctx, $field_name)))->toDict($ctx));
	}
	/**
	 * Returns new instance
	 */
	static function newInstance($ctx, $items)
	{
		$class_name = static::class;
		return new $class_name($ctx, $items);
	}
	/**
	 * Update struct
	 * @param Collection<string> path
	 * @param var value
	 * @return BaseStruct
	 */
	static function update($ctx, $item, $items)
	{
		return $item->copy($ctx, $items);
	}
	/**
	 * Update struct
	 * @param Collection<string> path
	 * @param var value
	 * @return BaseStruct
	 */
	static function setAttr($ctx, $item, $path, $value)
	{
		return \Runtime\rtl::setAttr($ctx, $item, $path, $value);
	}
	/**
	 * Returns struct as Dict
	 * @return Dict
	 */
	function takeDict($ctx)
	{
		$values = new \Runtime\Map($ctx);
		$names = \Runtime\rtl::getFields($ctx, $this->getClassName($ctx));
		for ($i = 0;$i < $names->count($ctx);$i++)
		{
			$variable_name = $names->item($ctx, $i);
			$value = $this->get($ctx, $variable_name, null);
			$values->setValue($ctx, $variable_name, $value);
		}
		return $values->toDict($ctx);
	}
	/**
	 * Returns struct as Dict
	 * @return Dict
	 */
	function toDict($ctx)
	{
		return $this->takeDict($ctx);
	}
	function get($ctx,$k,$v = nul){$k="__".$k;return isset($this->$k)?$this->$k:$v;}
	function __get($k){$k="__".$k;return isset($this->$k)?$this->$k:null;}
	function __getKey($k){return "__".$k;}
	function __set($k,$v){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	function offsetExists($k){$k="__".$k;return isset($this->$k);}
	function offsetGet($k){$k="__".$k;return isset($this->$k)?$this->$k:null;}
	function offsetSet($k,$v){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	function offsetUnset($k){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.BaseStruct";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.BaseStruct";
	}
	static function getParentClassName()
	{
		return "Runtime.BaseObject";
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