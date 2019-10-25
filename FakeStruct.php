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
class FakeStruct extends \Runtime\CoreObject implements \ArrayAccess, \Runtime\Interfaces\SerializeInterface
{
	function __construct($__ctx, $obj=null)
	{
		parent::__construct($__ctx);
		if ($obj != null)
		{
			if (!($obj instanceof \Runtime\Dict))
			{
				$obj = \Runtime\Dict::create($obj);
			}
			foreach ($obj->_map as $key => $value)
			{
				$this->assignValue($__ctx, $key, $value);
			}
			$this->initData($__ctx, null, $obj);
		}
	}
	/**
	 * Init struct data
	 */
	function initData($__ctx, $old, $changed=null)
	{
	}
	/**
	 * Copy this struct with new values
	 * @param Map obj = null
	 * @return FakeStruct
	 */
	function copy($__ctx, $obj=null)
	{
		if ($obj == null){}
		else if ($obj instanceof \Runtime\Dict)
		{
			foreach ($obj->_map as $key => $value)
			{
				if (property_exists($this, $key))
					$this->$key = clone $value;
			}
		}
		else if (gettype($obj) == "array")
		{
			foreach ($obj as $key => $value)
			{
				if (property_exists($this, $key))
					$this->$key = clone $value;
			}
		}
		
		return $this;
		return $this;
	}
	/**
	 * Clone this struct with same values
	 * @param Map obj = null
	 * @return FakeStruct
	 */
	function clone($__ctx, $fields=null)
	{
		$obj = new \Runtime\Map($__ctx);
		if ($fields != null)
		{
			$fields->each($__ctx, function ($__ctx, $field_name) use (&$obj)
			{
				$obj->set($__ctx, $field_name, \Runtime\rtl::clone($__ctx, $this->takeValue($__ctx, $field_name)));
			});
		}
		else
		{
			$names = \Runtime\RuntimeUtils::getVariablesNames($__ctx, $this->getClassName($__ctx));
			for ($i = 0;$i < $names->count($__ctx);$i++)
			{
				$field_name = $names->item($__ctx, $i);
				$obj->set($__ctx, $field_name, \Runtime\rtl::clone($__ctx, $this->takeValue($__ctx, $field_name)));
			}
		}
		/* Return object */
		$res = static::newInstance($__ctx, $obj->toDict($__ctx));
		return $res;
	}
	/**
	 * Create new struct with new value
	 * @param string field_name
	 * @param fn f
	 * @return FakeStruct
	 */
	function map($__ctx, $field_name, $f)
	{
		return $this->copy($__ctx, (new \Runtime\Map($__ctx))->set($__ctx, $field_name, $f($__ctx, $this->takeValue($__ctx, $field_name)))->toDict($__ctx));
	}
	/**
	 * Returns new instance
	 */
	static function newInstance($__ctx, $items)
	{
		$class_name = static::class;
		return new $class_name($items);
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.FakeStruct";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.FakeStruct";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreObject";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.FakeStruct",
			"name"=>"Runtime.FakeStruct",
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
	
	function __get($k){$k="__".$k;return isset($this->$k)?$this->$k:null;}
	function __set($k,$v){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	function offsetExists($k){$k="__".$k;return isset($this->$k);}
	function offsetGet($k){$k="__".$k;return isset($this->$k)?$this->$k:null;}
	function offsetSet($k,$v){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
	function offsetUnset($k){throw new \Runtime\Exceptions\AssignStructValueError(null, $k);}
}