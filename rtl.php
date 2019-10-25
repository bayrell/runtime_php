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
class rtl
{
	static $_memorize_cache=null;
	static $_memorize_not_found=null;
	static $_memorize_hkey=null;
	static function isBrowser()
	{
		return false;
	}
	/**
	 * Define props
	 */
	static function defProp($obj, $name)
	{
	}
	/**
	 * Define class
	 */
	static function defClass($obj)
	{
	}
	/**
	 * Find class instance by name. If class does not exists return null.
	 * @return var - class instance
	 */
	static function find_class($class_name)
	{
		return "\\" . preg_replace("/\\./", "\\", $class_name);
	}
	/**
	 * Returns true if class instanceof class_name
	 * @return bool
	 */
	static function is_instanceof($__ctx, $obj, $class_name)
	{
		$class_name = self::find_class($class_name);
		if ($obj == null) return false;
		if (gettype($obj) != "object") return false;
		if (is_subclass_of($obj, $class_name)){ return true;}
		return is_a($obj, $class_name);
	}
	/**
	 * Returns true if obj implements interface_name
	 * @return bool
	 */
	static function is_implements($__ctx, $obj, $interface_name)
	{
		$class_name = get_class($obj);
		return self::class_implements($class_name, $interface_name);
	}
	/**
	 * Returns true if class exists
	 * @return bool
	 */
	static function class_exists($__ctx, $class_name)
	{
		$class_name = static::find_class($class_name);
		return class_exists($class_name);
	}
	/**
	 * Returns true if class exists
	 * @return bool
	 */
	static function class_implements($__ctx, $class_name, $interface_name)
	{
		$class_name = self::find_class($class_name);
		$interface_name = self::find_class($interface_name);
		$arr = @class_implements($class_name, true);
		if ($arr == false){
			return false;
		}
		foreach ($arr as $name){
			if ($name == $interface_name or "\\" . $name == $interface_name)
				return true;
		}
		return false;
	}
	/**
	 * Returns interface of class
	 * @param string class_name
	 * @return Collection<string>
	 */
	static function getInterfaces($__ctx, $class_name)
	{
		$arr = array_values(class_implements(rtl::find_class($class_name)));
		$arr = array_map
		(
			function($s){ return str_replace("\\", ".", $s); },
			$arr
		);
		return \Runtime\Collection::from($arr);
	}
	/**
	 * Returns true if class exists
	 * @return bool
	 */
	static function method_exists($__ctx, $class_name, $method_name)
	{
		$class_name = static::find_class($class_name);
		if (!class_exists($class_name)) return false;
		if (!method_exists($class_name, $method_name)) return false;
		return true;
	}
	/**
	 * Create object by class_name. If class name does not exists return null
	 * @return Object
	 */
	static function newInstance($__ctx, $class_name, $args)
	{
		$class_name = static::find_class($class_name);
		if ($args == null)
			return new $class_name();
		$r = new \ReflectionClass($class_name);
		$arr = $args->_arr;
		array_unshift($arr, $__ctx);
		return $r->newInstanceArgs($arr);
	}
	/**
	 * Returns callback
	 * @return fn
	 */
	static function method($__ctx, $obj, $method_name)
	{
		return new \Runtime\Callback($obj, $method_name);
	}
	/**
	 * Returns callback
	 * @return fn
	 */
	static function apply($__ctx, $f, $args)
	{
		$arr = $args->_getArr();
		array_unshift($arr, $__ctx);
		if ($f instanceof \Runtime\Callback)
		{
			return $f->invokeArgs($arr);
		}
		if (gettype($f) == "string") $f = static::find_class($f);
		return call_user_func_array($f, $arr);
	}
	/**
	 * Call await method
	 * @return fn
	 */
	static function applyAwait($__ctx, $f, $args)
	{
		$arr = $args->_getArr();
		array_unshift($arr, $__ctx);
		if ($f instanceof \Runtime\Callback)
		{
			return $f->invokeArgs($arr);
		}
		return call_user_func_array($f, $arr);
	}
	/**
	 * Returns callback
	 * @return var
	 */
	static function attr($__ctx, $item, $path, $def_val=null)
	{
		if ($item == null) return $def_val;
		if (count($path->_arr) == 0) 
		{
			return $item;
		}
		$key = $path->first($__ctx);
		$path = $path->removeFirstIm($__ctx);
		$val = $def_val;
		if ($item instanceof \Runtime\Dict or $item instanceof \Runtime\Collection)
		{
			$item = $item->get($__ctx, $key, $def_val);
			$val = static::attr($__ctx, $item, $path, $def_val);
			return $val;
		}
		else if ($item instanceof \Runtime\CoreStruct)
		{
			$item = $item->takeValue($__ctx, $key, $def_val);
			$val = static::attr($__ctx, $item, $path, $def_val);
			return $val;
		}
		return $val;
	}
	/**
	 * Returns value
	 * @param var value
	 * @param var def_val
	 * @param var obj
	 * @return var
	 */
	static function to($v, $d, $o)
	{
		$t = $o->e;
		if ($t == "mixed" || $t == "primitive" || $t == "var" || $t == "fn" || $t == "callback")
		{
			return $v;
		}
		if ($t == "bool")
		{
			return static::toBool(null, $v, $d);
		}
		else if ($t == "string")
		{
			return static::toString($v, $d);
		}
		else if ($t == "int")
		{
			return static::toInt(null, $v, $d);
		}
		else if ($t == "float")
		{
			return static::toFloat(null, $v, $d);
		}
		else if (\Runtime\rtl::is_instanceof(null, $v, $t))
		{
			return $v;
		}
		return $d;
	}
	/**
	 * Returns value if value instanceof type_value, else returns def_value
	 * @param var value
	 * @param string type_value
	 * @param var def_value
	 * @param var type_template
	 * @return var
	 */
	static function convert($value, $type_value, $def_value=null, $type_template="")
	{
		return $value;
	}
	/**
	 * Returns true if value instanceof tp
	 * @param var value
	 * @param string tp
	 * @return bool
	 */
	static function checkValue($__ctx, $value, $tp)
	{
		if ($tp == "int")
		{
			return \Runtime\rtl::isInt($__ctx, $value);
		}
		if ($tp == "float" || $tp == "double")
		{
			return \Runtime\rtl::isDouble($__ctx, $value);
		}
		if ($tp == "string")
		{
			return \Runtime\rtl::isString($__ctx, $value);
		}
		if ($tp == "bool" || $tp == "boolean")
		{
			return \Runtime\rtl::isBoolean($__ctx, $value);
		}
		if (\Runtime\rtl::is_instanceof($__ctx, $value, $tp))
		{
			return true;
		}
		return false;
	}
	/**
	 * Return true if value is exists
	 * @param var value
	 * @return bool
	 */
	static function exists($__ctx, $value)
	{
		return isset($value);
	}
	/**
	 * Returns true if value is scalar value
	 * @return bool 
	 */
	static function isScalarValue($__ctx, $value)
	{
		if ($value == null)
		{
			return true;
		}
		if (\Runtime\rtl::isString($__ctx, $value))
		{
			return true;
		}
		if (\Runtime\rtl::isNumber($__ctx, $value))
		{
			return true;
		}
		if (\Runtime\rtl::isBoolean($__ctx, $value))
		{
			return true;
		}
		return false;
	}
	/**
	 * Return true if value is boolean
	 * @param var value
	 * @return bool
	 */
	static function isBoolean($__ctx, $value)
	{
		if ($value === false || $value === true)
		{
			return true;
		}
		return false;
	}
	/**
	 * Return true if value is number
	 * @param var value
	 * @return bool
	 */
	static function isInt($__ctx, $value)
	{
		return is_int($value);
	}
	/**
	 * Return true if value is number
	 * @param var value
	 * @return bool
	 */
	static function isDouble($__ctx, $value)
	{
		return is_int($value) or is_float($value);
	}
	/**
	 * Return true if value is number
	 * @param var value
	 * @return bool
	 */
	static function isNumber($__ctx, $value)
	{
		return is_int($value) or is_float($value);
	}
	/**
	 * Return true if value is string
	 * @param var value
	 * @return bool
	 */
	static function isString($__ctx, $value)
	{
		return is_string($value);
	}
	/**
	 * Convert value to string
	 * @param var value
	 * @return string
	 */
	static function toString($value)
	{
		if ($value instanceof StringInterface) return $value->toString();
		return (string)$value;
	}
	/**
	 * Convert value to string
	 * @param var value
	 * @return string
	 */
	static function toStr($value)
	{
		return static::toString($value);
	}
	/**
	 * Convert value to int
	 * @param var value
	 * @return int
	 */
	static function toInt($__ctx, $val)
	{
		$res = (int)$val;
		$s_res = (string)$res;
		$s_val = (string)$val;
		if ($s_res == $s_val)
			return $res;
		
		throw new \Exception("Error convert to int");
	}
	/**
	 * Convert value to boolean
	 * @param var value
	 * @return bool
	 */
	static function toBool($__ctx, $val)
	{
		if ($val === false || $val === "false") return false;
		if ($val === true || $val === "true") return true;
		$res = (bool)$val;
		$s_res = (string)$res;
		$s_val = (string)$val;
		if ($s_res == $s_val)
			return $res;
		throw new \Exception("Error convert to boolean");
	}
	/**
	 * Convert value to float
	 * @param var value
	 * @return float
	 */
	static function toFloat($__ctx, $val)
	{
		$res = floatval($val);
		$s_res = (string)$res;
		$s_val = (string)$val;
		if ($s_res == $s_val)
			return $res;
		throw new \Exception("Error convert to float");
	}
	/**
	 * Round up
	 * @param double value
	 * @return int
	 */
	static function ceil($__ctx, $value)
	{
		return ceil($value);
	}
	/**
	 * Round down
	 * @param double value
	 * @return int
	 */
	static function floor($__ctx, $value)
	{
		return floor($value);
	}
	/**
	 * Round down
	 * @param double value
	 * @return int
	 */
	static function round($__ctx, $value)
	{
		return round($value);
	}
	/* ====================== Chains ====================== */
	/**
	 * Apply async chain
	 */
	static function chainAwait($__ctx, $chain, $args)
	{
		for ($i = 0;$i < $chain->count($__ctx);$i++)
		{
			$chain_name = $chain->item($__ctx, $i);
			$args = \Runtime\rtl::apply($__ctx, $chain_name, $args);
		}
		return $args;
	}
	/**
	 * Apply chain
	 */
	static function chain($__ctx, $chain, $args)
	{
		for ($i = 0;$i < $chain->count($__ctx);$i++)
		{
			$chain_name = $chain->item($__ctx, $i);
			$args = \Runtime\rtl::apply($__ctx, $chain_name, $args);
		}
		return $args;
	}
	static function _memorizeValidHKey($hkey, $key)
	{
		if ( static::$_memorize_hkey == null ) static::$_memorize_hkey = [];
		if ( !isset(static::$_memorize_hkey[$hkey]) ) return false;
		if ( static::$_memorize_hkey[$hkey] == $key ) return true;
		return false;
	}
	/**
	 * Clear memorize cache
	 */
	static function _memorizeClear()
	{
		static::$_memorize_cache = null;
		static::$_memorize_hkey = [];
	}
	/**
	 * Returns cached value
	 */
	static function _memorizeValue($name, $args)
	{
		if (static::$_memorize_cache == null) return static::$_memorize_not_found;
		if (!isset(static::$_memorize_cache[$name])) return static::$_memorize_not_found;
		
		$arr = &static::$_memorize_cache[$name];
		$sz = count($args);
		for ($i=0; $i<$sz; $i++)
		{
			$key = &$args[$i];
			$hkey = null; 
			if (gettype($key) == 'object') $hkey = spl_object_hash($key); else $hkey = $key;
			if ($i == $sz - 1)
			{
				if (in_array($hkey, array_keys($arr)))
				{
					return $arr[$hkey];
				}
				return static::$_memorize_not_found;
			}
			else
			{
				if (!isset($arr[$hkey])) return static::$_memorize_not_found;
				$arr = &$arr[$hkey];
			}
		}
		
		return static::$_memorize_not_found;
	}
	/**
	 * Returns cached value
	 */
	static function _memorizeSave($name, $args, $value)
	{
		if (static::$_memorize_cache == null) static::$_memorize_cache = [];
		if (!isset(static::$_memorize_cache[$name])) static::$_memorize_cache[$name] = [];
		
		$arr = &static::$_memorize_cache[$name];
		$sz = count($args);
		for ($i=0; $i<$sz; $i++)
		{
			$key = &$args[$i];
			$hkey = null; 
			if (gettype($key) == 'object') $hkey = spl_object_hash($key); else $hkey = $key;
			if ($i == $sz - 1)
			{
				$arr[$hkey] = $value;
			}
			else
			{
				if (!isset($arr[$hkey])) $arr[$hkey] = [];
				else if (!static::_memorizeValidHKey($hkey, $key)) $arr[$hkey] = [];
				$arr = &$arr[$hkey];
			}
		}
	}
	/* ================ Dirty functions ================ */
	/**
	 * Returns unique value
	 * @param bool flag If true returns as text. Default true
	 * @return string
	 */
	static function unique($__ctx, $flag=true)
	{
		return uniqid();
	}
	/**
	 * Round down
	 * @param double value
	 * @return int
	 */
	static function dump($__ctx, $value)
	{
		var_dump($value);
	}
	/**
	 * Returns random value x, where a <= x <= b
	 * @param int a
	 * @param int b
	 * @return int
	 */
	static function random($__ctx, $a, $b)
	{
		if (PHP_VERSION_ID < 70000) return mt_rand($a, $b);
		return random_int($a, $b);
	}
	/**
	 * Returns current unix time in seconds
	 * @return int
	 */
	static function time($__ctx)
	{
		return time();
	}
	/**
	 * Clone var
	 * @param {var} value - Variable
	 * @return {var} result
	 */
	static function clone($__ctx, $val)
	{
		if ($val == null) return null;
		if (self::isScalarValue($val)) return $val;
		return clone $val;
	}
	/* =================== Deprecated =================== */
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params MapInterface params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	static function translate($__ctx, $message, $params=null, $locale="", $context=null)
	{
		return self::callStaticMethod("Runtime.RuntimeUtils", "translate", [$message, $params, $locale, $context]);
	}
	/**
	 * Json encode data
	 * @param var data
	 * @return string
	 */
	static function json_encode($__ctx, $data)
	{
		return self::callStaticMethod("Runtime.RuntimeUtils", "json_encode", [$data]);
	}
	/**
	 * Normalize UIStruct
	 * @param var data
	 * @return var
	 */
	static function normalizeUI($__ctx, $data)
	{
		return self::callStaticMethod("Runtime.RuntimeUtils", "normalizeUI", [$data]);
	}
	/**
	 * Normalize UIStruct
	 * @param var data
	 * @return var
	 */
	static function normalizeUIVector($__ctx, $data)
	{
		return self::callStaticMethod("Runtime.RuntimeUtils", "normalizeUIVector", [$data]);
	}
	/**
	 * Call method
	 * @return Object
	 */
	static function f($__ctx, $f)
	{
		return $f;
	}
	/**
	 * Returns value if value instanceof type_value, else returns def_value
	 * @param var value
	 * @param string type_value
	 * @param var def_value
	 * @param var type_template
	 * @return var
	 */
	static function correct($__ctx, $value, $def_value=null, $type_value, $type_template="")
	{
		return static::convert($__ctx, $value, $type_value, $def_value, $type_template);
	}
	/**
	 * Convert module name to node js package
	 */
	static function convertNodeJSModuleName($__ctx, $name)
	{
		$arr1 = "qazwsxedcrfvtgbyhnujmikolp";
		$arr2 = "0123456789";
		$res = "";
		$sz = mb_strlen($name);
		$previsbig = false;
		for ($i = 0; $i < $sz; $i++){
			$ch = mb_substr($name, $i, 1);
			$ch2 = mb_strtoupper($ch);
			$ch3 = mb_strtolower($ch);
			$isAlpha = mb_strpos($arr1, $ch3) !== false;
			$isNum = mb_strpos($arr2, $ch3) !== false;
			if ($i > 0 && $ch == $ch2 && !$previsbig && $isAlpha){
				$res .= "-";
			}
			$res .= $ch3;
			if ($ch == $ch2){
				$previsbig = true;
			}
			else {
				$previsbig = false;
			}
			if (!$isAlpha && !$isNum){
				$previsbig = true;
			}
		}
		$res .= "-nodejs";
		return $res;
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.rtl";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.rtl";
	}
	static function getParentClassName()
	{
		return "";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.rtl",
			"name"=>"Runtime.rtl",
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
rtl::$_memorize_not_found = (object) ['s' => 'memorize_key_not_found'];