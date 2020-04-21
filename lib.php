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

/* Lambda Functions */class lib
{
	/**
	 * Check object is istance
	 */
	static function isInstance($ctx, $class_name)
	{
		return function ($ctx, $item) use (&$class_name)
		{
			return \Runtime\rtl::is_instanceof($ctx, $item, $class_name);
		};
	}
	/**
	 * Check object is implements interface
	 */
	static function isImplements($ctx, $class_name)
	{
		return function ($ctx, $item) use (&$class_name)
		{
			return \Runtime\rtl::is_implements($ctx, $item, $class_name);
		};
	}
	/**
	 * Check class is implements interface
	 */
	static function classImplements($ctx, $class_name)
	{
		return function ($ctx, $item) use (&$class_name)
		{
			return \Runtime\rtl::class_implements($ctx, $item, $class_name);
		};
	}
	/**
	 * Create struct
	 */
	static function createStruct($ctx, $class_name)
	{
		return function ($ctx, $data) use (&$class_name)
		{
			return \Runtime\rtl::newInstance($ctx, $class_name, \Runtime\Collection::from([$data]));
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equal($ctx, $value)
	{
		return function ($ctx, $item) use (&$value)
		{
			return $item == $value;
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalNot($ctx, $value)
	{
		return function ($ctx, $item) use (&$value)
		{
			return $item != $value;
		};
	}
	/**
	 * Returns attr of item
	 */
	static function attr($ctx, $key, $def_value)
	{
		return function ($ctx, $item1) use (&$key,&$def_value)
		{
			return ($item1 != null) ? $item1->takeValue($ctx, $key, $def_value) : $def_value;
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalAttr($ctx, $key, $value)
	{
		return function ($ctx, $item1) use (&$key,&$value)
		{
			return ($item1 != null) ? $item1->takeValue($ctx, $key) == $value : false;
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalNotAttr($ctx, $key, $value)
	{
		return function ($ctx, $item1) use (&$key,&$value)
		{
			return ($item1 != null) ? $item1->takeValue($ctx, $key) != $value : false;
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalMethod($ctx, $method_name, $value)
	{
		return function ($ctx, $item1) use (&$method_name,&$value)
		{
			if ($item1 == null)
			{
				return false;
			}
			$f = \Runtime\rtl::method($item1, $method_name);
			return $f($ctx) == $value;
		};
	}
	/**
	 * Returns max id from items
	 */
	static function getMaxIdFromItems($ctx, $items, $start=0)
	{
		return $items->reduce($ctx, function ($ctx, $value, $item)
		{
			return ($item->id > $value) ? $item->id : $value;
		}, $start);
	}
	/**
	 * Take dict
	 */
	static function takeDict($ctx, $fields)
	{
		return function ($ctx, $item) use (&$fields)
		{
			return $item->takeDict($ctx, $fields);
		};
	}
	/**
	 * Filter
	 */
	static function filter($ctx, $f)
	{
		return function ($ctx, $m) use (&$f)
		{
			return $m->filter($ctx, $f);
		};
	}
	/**
	 * To
	 */
	static function to($ctx, $type_value, $def_value=null)
	{
		return function ($ctx, $m) use (&$type_value,&$def_value)
		{
			return \Runtime\rtl::convert($m->value($ctx), $type_value, $def_value);
		};
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.lib";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.lib";
	}
	static function getParentClassName()
	{
		return "";
	}
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.lib",
			"name"=>"Runtime.lib",
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