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

/* Lambda Functions */class lib
{
	/**
	 * Check object is istance
	 */
	static function isInstance($__ctx, $class_name)
	{
		return function ($__ctx, $item) use (&$class_name)
		{
			return \Runtime\rtl::is_instanceof($__ctx, $item, $class_name);
		};
	}
	/**
	 * Check object is implements interface
	 */
	static function isImplements($__ctx, $class_name)
	{
		return function ($__ctx, $item) use (&$class_name)
		{
			return \Runtime\rtl::is_implements($__ctx, $item, $class_name);
		};
	}
	/**
	 * Check class is implements interface
	 */
	static function classImplements($__ctx, $class_name)
	{
		return function ($__ctx, $item) use (&$class_name)
		{
			return \Runtime\rtl::class_implements($__ctx, $item, $class_name);
		};
	}
	/**
	 * Create struct
	 */
	static function createStruct($__ctx, $class_name)
	{
		return function ($__ctx, $data) use (&$class_name)
		{
			return \Runtime\rtl::newInstance($__ctx, $class_name, \Runtime\Collection::from([$data]));
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equal($__ctx, $value)
	{
		return function ($__ctx, $item) use (&$value)
		{
			return $item == $value;
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalNot($__ctx, $value)
	{
		return function ($__ctx, $item) use (&$value)
		{
			return $item != $value;
		};
	}
	/**
	 * Returns attr of item
	 */
	static function attr($__ctx, $key, $def_value)
	{
		return function ($__ctx, $item1) use (&$key,&$def_value)
		{
			return ($item1 != null) ? $item1->takeValue($__ctx, $key, $def_value) : $def_value;
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalAttr($__ctx, $key, $value)
	{
		return function ($__ctx, $item1) use (&$key,&$value)
		{
			return ($item1 != null) ? $item1->takeValue($__ctx, $key) == $value : false;
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalNotAttr($__ctx, $key, $value)
	{
		return function ($__ctx, $item1) use (&$key,&$value)
		{
			return ($item1 != null) ? $item1->takeValue($__ctx, $key) != $value : false;
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalMethod($__ctx, $method_name, $value)
	{
		return function ($__ctx, $item1) use (&$method_name,&$value)
		{
			if ($item1 == null)
			{
				return false;
			}
			$f = \Runtime\rtl::method($item1, $method_name);
			return $f($__ctx) == $value;
		};
	}
	/**
	 * Returns max id from items
	 */
	static function getMaxIdFromItems($__ctx, $items, $start=0)
	{
		return $items->reduce($__ctx, function ($__ctx, $value, $item)
		{
			return ($item->id > $value) ? $item->id : $value;
		}, $start);
	}
	/**
	 * Take dict
	 */
	static function takeDict($__ctx, $fields)
	{
		return function ($__ctx, $item) use (&$fields)
		{
			return $item->takeDict($__ctx, $fields);
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
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.lib",
			"name"=>"Runtime.lib",
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