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
class RuntimeUtils
{
	static $_variables_names=null;
	const JSON_PRETTY=1;
	/* ============================= Serialization Functions ============================= */
	static function ObjectToNative($ctx, $value, $force_class_name=true)
	{
		$value1 = \Runtime\RuntimeUtils::ObjectToPrimitive($ctx, $value, $force_class_name);
		$value2 = \Runtime\RuntimeUtils::PrimitiveToNative($ctx, $value1);
		return $value2;
	}
	static function NativeToObject($ctx, $value)
	{
		$value1 = \Runtime\RuntimeUtils::NativeToPrimitive($ctx, $value);
		$value2 = \Runtime\RuntimeUtils::PrimitiveToObject($ctx, $value1);
		return $value2;
	}
	/**
	 * Returns object to primitive value
	 * @param var obj
	 * @return var
	 */
	static function ObjectToPrimitive($ctx, $obj, $force_class_name=true)
	{
		if ($obj === null)
		{
			return null;
		}
		if (\Runtime\rtl::isScalarValue($ctx, $obj))
		{
			return $obj;
		}
		if ($obj instanceof \Runtime\Collection)
		{
			return $obj->map($ctx, function ($ctx, $value) use (&$force_class_name)
			{
				return static::ObjectToPrimitive($ctx, $value, $force_class_name);
			});
		}
		if ($obj instanceof \Runtime\Dict)
		{
			$obj = $obj->map($ctx, function ($ctx, $value, $key) use (&$force_class_name)
			{
				return static::ObjectToPrimitive($ctx, $value, $force_class_name);
			});
			return $obj->toDict($ctx);
		}
		if ($obj instanceof \Runtime\Date)
		{
			return $obj;
		}
		if ($obj instanceof \Runtime\DateTime)
		{
			return $obj;
		}
		if ($obj instanceof \Runtime\BaseStruct)
		{
			$values = new \Runtime\Map($ctx);
			$names = \Runtime\rtl::getFields($ctx, $obj->getClassName($ctx));
			for ($i = 0;$i < $names->count($ctx);$i++)
			{
				$variable_name = $names->item($ctx, $i);
				$value = $obj->get($ctx, $variable_name, null);
				$value = \Runtime\RuntimeUtils::ObjectToPrimitive($ctx, $value, $force_class_name);
				$values->setValue($ctx, $variable_name, $value);
			}
			if ($force_class_name)
			{
				$values->setValue($ctx, "__class_name__", $obj->getClassName($ctx));
			}
			return $values->toDict($ctx);
		}
		return null;
	}
	/**
	 * Returns object to primitive value
	 * @param SerializeContainer container
	 * @return var
	 */
	static function PrimitiveToObject($ctx, $obj)
	{
		if ($obj === null)
		{
			return null;
		}
		if (\Runtime\rtl::isScalarValue($ctx, $obj))
		{
			return $obj;
		}
		if ($obj instanceof \Runtime\Collection)
		{
			$res = new \Runtime\Vector($ctx);
			for ($i = 0;$i < $obj->count($ctx);$i++)
			{
				$value = $obj->item($ctx, $i);
				$value = \Runtime\RuntimeUtils::PrimitiveToObject($ctx, $value);
				$res->pushValue($ctx, $value);
			}
			return $res->toCollection($ctx);
		}
		if ($obj instanceof \Runtime\Dict)
		{
			$res = new \Runtime\Map($ctx);
			$keys = $obj->keys($ctx);
			for ($i = 0;$i < $keys->count($ctx);$i++)
			{
				$key = $keys->item($ctx, $i);
				$value = $obj->item($ctx, $key);
				$value = \Runtime\RuntimeUtils::PrimitiveToObject($ctx, $value);
				$res->setValue($ctx, $key, $value);
			}
			if (!$res->has($ctx, "__class_name__"))
			{
				return $res->toDict($ctx);
			}
			if ($res->item($ctx, "__class_name__") == "Runtime.Map" || $res->item($ctx, "__class_name__") == "Runtime.Dict")
			{
				$res->remove($ctx, "__class_name__");
				return $res->toDict($ctx);
			}
			$class_name = $res->item($ctx, "__class_name__");
			if (!\Runtime\rtl::class_exists($ctx, $class_name))
			{
				return null;
			}
			if (!\Runtime\rtl::class_implements($ctx, $class_name, "Runtime.SerializeInterface"))
			{
				return null;
			}
			/* Assign values */
			$obj = new \Runtime\Map($ctx);
			$names = \Runtime\rtl::getFields($ctx, $class_name);
			for ($i = 0;$i < $names->count($ctx);$i++)
			{
				$variable_name = $names->item($ctx, $i);
				if ($variable_name != "__class_name__")
				{
					$value = $res->get($ctx, $variable_name, null);
					$obj->setValue($ctx, $variable_name, $value);
				}
			}
			/* New instance */
			$instance = \Runtime\rtl::newInstance($ctx, $class_name, \Runtime\Collection::from([$obj]));
			return $instance;
		}
		return null;
	}
	static function NativeToPrimitive($ctx, $value)
	{
		if ($value === null)
			return null;
			
		if (is_object($value))
		{
			if (isset($value->__class_name__) && $value['__class_name__'] == "Runtime.Date")
			{
				$res = \Runtime\Date::from($value);
				return $res;
			}
			else if (isset($value->__class_name__) && $value['__class_name__'] == "Runtime.DateTime")
			{
				$res = \Runtime\DateTime::from($value);
				return $res;
			}
			else
			{
				$res = \Runtime\Dict::from($value);
				$res = $res->map($ctx, function ($ctx, $val, $key){
					return self::NativeToPrimitive($ctx, $val);
				});
				return $res;
			}
		}
		
		if (is_array($value))
		{
			if ( isset($value['__class_name__']) )
			{
				if ($value['__class_name__'] == "Runtime.Date")
				{
					$res = \Runtime\Date::from($value);
					return $res;
				}
				else if ($value['__class_name__'] == "Runtime.DateTime")
				{
					$res = \Runtime\DateTime::from($value);
					return $res;
				}
				else
				{
					$res = \Runtime\Dict::from($value);
					$res = $res->map($ctx, function ($ctx, $val, $key){
						return self::NativeToPrimitive($ctx, $val);
					});
					return $res;
				}
			}
			$arr = array_values($value);
			$res = \Runtime\Collection::from($arr);
			$res = $res->map($ctx, function ($ctx, $item){
				return self::NativeToPrimitive($ctx, $item);
			});
			return $res;
		}
		
		return $value;
	}
	static function PrimitiveToNative($ctx, $value)
	{
		if ($value === null)
			return null;
		
		if ($value instanceof \Runtime\Date)
		{
			$value = $value->toDict($ctx)->setIm($ctx, "__class_name__", "Runtime.Date");
		}
		else if ($value instanceof \Runtime\DateTime)
		{
			$value = $value->toDict($ctx)->setIm($ctx, "__class_name__", "Runtime.DateTime");
		}
		
		if ($value instanceof \Runtime\Collection)
		{
			$arr = [];
			$value->each
			(
				$ctx,
				function ($ctx, $v) use (&$arr)
				{
					$arr[] = static::PrimitiveToNative($ctx, $v);
				}
			);
			return $arr;
		}
		
		if ($value instanceof \Runtime\Dict)
		{
			$arr = [];
			$value->each
			(
				$ctx,
				function ($ctx, $v, $k) use (&$arr)
				{
					$arr[$k] = static::PrimitiveToNative($ctx, $v);
				}
			);
			return $arr;
		}
		return $value;
	}
	/**
	 * Json encode serializable values
	 * @param serializable value
	 * @param SerializeContainer container
	 * @return string 
	 */
	static function json_encode($ctx, $value, $flags=0, $convert=true)
	{
		if ($convert){
			$value = self::ObjectToNative($ctx, $value);
		}
		$json_flags = JSON_UNESCAPED_UNICODE;
		if ( ($flags & 1) == 1 ) $json_flags = $json_flags | JSON_PRETTY_PRINT;
		return json_encode($value, $json_flags);
	}
	/**
	 * Json decode to primitive values
	 * @param string s Encoded string
	 * @return var 
	 */
	static function json_decode($ctx, $obj)
	{
		$res = @json_decode($obj, false);
		if ($res === null || $res === false)
			return null;
		return self::NativeToObject($ctx, $res);
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.RuntimeUtils";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.RuntimeUtils";
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
		if ($field_name == "_variables_names") return \Runtime\Dict::from([
			"t"=>"Runtime.Map",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "JSON_PRETTY") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
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