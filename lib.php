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
	 * Equal two struct by key
	 */
	static function equalAttr($ctx, $key, $value)
	{
		return function ($ctx, $item1) use (&$key,&$value)
		{
			return ($item1 != null) ? (\Runtime\rtl::attr($ctx, $item1, $key) == $value) : (false);
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalNotAttr($ctx, $key, $value)
	{
		return function ($ctx, $item1) use (&$key,&$value)
		{
			return ($item1 != null) ? (\Runtime\rtl::attr($ctx, $item1, $key) != $value) : (false);
		};
	}
	static function equalAttrNot($ctx, $key, $value)
	{
		return static::equalNotAttr($ctx, $key, $value);
	}
	/**
	 * Equal attrs
	 */
	static function equalAttrs($ctx, $search)
	{
		return function ($ctx, $item) use (&$search)
		{
			$fields = $search->keys($ctx);
			for ($i = 0;$i < $fields->count($ctx);$i++)
			{
				$field_name = \Runtime\rtl::get($ctx, $fields, $i);
				if (\Runtime\rtl::get($ctx, $search, $field_name) != \Runtime\rtl::get($ctx, $item, $field_name))
				{
					return false;
				}
			}
			return true;
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
	 * Returns key value of obj
	 */
	static function get($ctx, $key, $def_value)
	{
		return function ($ctx, $obj) use (&$key,&$def_value)
		{
			return \Runtime\rtl::attr($ctx, $obj, \Runtime\Collection::from([$key]), $def_value);
		};
	}
	/**
	 * Set value
	 */
	static function set($ctx, $key, $value)
	{
		return function ($ctx, $obj) use (&$key,&$value)
		{
			return \Runtime\rtl::setAttr($ctx, $obj, \Runtime\Collection::from([$key]), $value);
		};
	}
	/**
	 * Returns attr of item
	 */
	static function attr($ctx, $path, $def_value=null)
	{
		return function ($ctx, $obj) use (&$path,&$def_value)
		{
			return \Runtime\rtl::attr($ctx, $obj, $path, $def_value);
		};
	}
	/**
	 * Set dict attr
	 */
	static function setAttr($ctx, $path, $value)
	{
		return function ($ctx, $obj) use (&$path,&$value)
		{
			return \Runtime\rtl::setAttr($ctx, $obj, $path, $value);
		};
	}
	/**
	 * Returns max id from items
	 */
	static function getMaxIdFromItems($ctx, $items, $start=0)
	{
		return $items->reduce($ctx, function ($ctx, $value, $item)
		{
			return ($item->id > $value) ? ($item->id) : ($value);
		}, $start);
	}
	/**
	 * Copy object
	 */
	static function copy($ctx, $d)
	{
		return function ($ctx, $item) use (&$d)
		{
			return $item->copy($ctx, $d);
		};
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
	 * Map
	 */
	static function map($ctx, $f)
	{
		return function ($ctx, $m) use (&$f)
		{
			return $m->map($ctx, $f);
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
	 * Intersect
	 */
	static function intersect($ctx, $arr)
	{
		return function ($ctx, $m) use (&$arr)
		{
			return $m->intersect($ctx, $arr);
		};
	}
	/**
	 * Sort
	 */
	static function sort($ctx, $f)
	{
		return function ($ctx, $m) use (&$f)
		{
			return $m->sortIm($ctx, $f);
		};
	}
	/**
	 * Transition
	 */
	static function transition($ctx, $f)
	{
		return function ($ctx, $m) use (&$f)
		{
			return $m->transition($ctx, $f);
		};
	}
	/**
	 * Sort asc
	 */
	static function sortAsc($ctx, $a, $b)
	{
		return ($a > $b) ? (1) : (($a < $b) ? (-1) : (0));
	}
	/**
	 * Sort desc
	 */
	static function sortDesc($ctx, $a, $b)
	{
		return ($a > $b) ? (-1) : (($a < $b) ? (1) : (0));
	}
	/**
	 * Sort attr
	 */
	static function sortAttr($ctx, $field_name, $f)
	{
		return function ($ctx, $a, $b) use (&$field_name,&$f)
		{
			$a = \Runtime\rtl::get($ctx, $a, $field_name);
			$b = \Runtime\rtl::get($ctx, $b, $field_name);
			if ($f == "asc")
			{
				return ($a > $b) ? (1) : (($a < $b) ? (-1) : (0));
			}
			if ($f == "desc")
			{
				return ($a > $b) ? (-1) : (($a < $b) ? (1) : (0));
			}
			return $f($ctx, $a, $b);
		};
	}
	/**
	 * Convert monad by type
	 */
	static function to($ctx, $type_value, $def_value=null)
	{
		return function ($ctx, $m) use (&$type_value,&$def_value)
		{
			return new \Runtime\Monad($ctx, ($m->err == null) ? (\Runtime\rtl::convert($m->value($ctx), $type_value, $def_value)) : ($def_value));
		};
	}
	/**
	 * Convert monad by type
	 */
	static function default($ctx, $def_value=null)
	{
		return function ($ctx, $m) use (&$def_value)
		{
			return ($m->err != null || $m->val === null) ? (new \Runtime\Monad($ctx, $def_value)) : ($m);
		};
	}
	/**
	 * Set monad new value
	 */
	static function newValue($ctx, $value=null, $clear_error=false)
	{
		return function ($ctx, $m) use (&$value,&$clear_error)
		{
			return ($clear_error == true) ? (new \Runtime\Monad($ctx, $value)) : (($m->err == null) ? (new \Runtime\Monad($ctx, $value)) : ($m));
		};
	}
	/**
	 * Clear error
	 */
	static function clearError($ctx)
	{
		return function ($ctx, $m)
		{
			return new \Runtime\Monad($ctx, $m->val);
		};
	}
	/**
	 * Returns monad
	 */
	static function monad($ctx, $m)
	{
		return $m;
	}
	/**
	 * Get method from class
	 * @return fn
	 */
	static function method($ctx, $method_name)
	{
		return function ($ctx, $class_name) use (&$method_name)
		{
			return \Runtime\rtl::method($ctx, $class_name, $method_name);
		};
	}
	/**
	 * Apply function
	 * @return fn
	 */
	static function applyMethod($ctx, $method_name, $args=null)
	{
		return function ($ctx, $class_name) use (&$method_name,&$args)
		{
			$f = \Runtime\rtl::method($ctx, $class_name, $method_name);
			return \Runtime\rtl::apply($ctx, $f, $args);
		};
	}
	/**
	 * Apply async function
	 * @return fn
	 */
	static function applyMethodAsync($ctx, $method_name, $args=null)
	{
		return function ($ctx, $class_name) use (&$method_name,&$args)
		{
			$f = \Runtime\rtl::method($ctx, $class_name, $method_name);
			return \Runtime\rtl::applyAsync($ctx, $f, $args);
		};
	}
	/**
	 * Apply function
	 * @return fn
	 */
	static function apply($ctx, $f)
	{
		return function ($ctx, $value) use (&$f)
		{
			return $f($ctx, $value);
		};
	}
	/**
	 * Apply function
	 * @return fn
	 */
	static function applyAsync($ctx, $f)
	{
		return function ($ctx, $value) use (&$f)
		{
			return $f($ctx, $value);
		};
	}
	/**
	 * Log message
	 * @return fn
	 */
	static function log($ctx, $message="")
	{
		return function ($ctx, $value) use (&$message)
		{
			if ($message == "")
			{
				var_dump($value);
			}
			else
			{
				var_dump($message);
			}
			return $value;
		};
	}
	/**
	 * Function or
	 */
	static function or($ctx, $arr)
	{
		return function ($ctx, $item) use (&$arr)
		{
			for ($i = 0;$i < $arr->count($ctx);$i++)
			{
				$f = \Runtime\rtl::get($ctx, $arr, $i);
				$res = $f($ctx, $item);
				if ($res)
				{
					return true;
				}
			}
			return false;
		};
	}
	/**
	 * Function and
	 */
	static function and($ctx, $arr)
	{
		return function ($ctx, $item) use (&$arr)
		{
			for ($i = 0;$i < $arr->count($ctx);$i++)
			{
				$f = \Runtime\rtl::get($ctx, $arr, $i);
				$res = $f($ctx, $item);
				if (!$res)
				{
					return false;
				}
			}
			return true;
		};
	}
	/**
	 * Join
	 */
	static function join($ctx, $ch)
	{
		return function ($ctx, $items) use (&$ch)
		{
			return \Runtime\rs::join($ctx, $ch, $items);
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