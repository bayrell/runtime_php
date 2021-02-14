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
class rtl
{
	const LOG_FATAL=0;
	const LOG_CRITICAL=2;
	const LOG_ERROR=4;
	const LOG_WARNING=6;
	const LOG_INFO=8;
	const LOG_DEBUG=10;
	const LOG_DEBUG2=12;
	const STATUS_PLAN=0;
	const STATUS_DONE=1;
	const STATUS_PROCESS=100;
	const STATUS_FAIL=-1;
	const ERROR_NULL=0;
	const ERROR_OK=1;
	const ERROR_PROCCESS=100;
	const ERROR_FALSE=-100;
	const ERROR_UNKNOWN=-1;
	const ERROR_INDEX_OUT_OF_RANGE=-2;
	const ERROR_KEY_NOT_FOUND=-3;
	const ERROR_STOP_ITERATION=-4;
	const ERROR_FILE_NOT_FOUND=-5;
	const ERROR_ITEM_NOT_FOUND=-5;
	const ERROR_OBJECT_DOES_NOT_EXISTS=-5;
	const ERROR_OBJECT_ALLREADY_EXISTS=-6;
	const ERROR_ASSERT=-7;
	const ERROR_REQUEST=-8;
	const ERROR_RESPONSE=-9;
	const ERROR_CSRF_TOKEN=-10;
	const ERROR_RUNTIME=-11;
	const ERROR_VALIDATION=-12;
	const ERROR_PARSE_SERIALIZATION_ERROR=-14;
	const ERROR_ASSIGN_DATA_STRUCT_VALUE=-15;
	const ERROR_AUTH=-16;
	const ERROR_DUPLICATE=-17;
	const ERROR_API_NOT_FOUND=-18;
	const ERROR_API_WRONG_FORMAT=-19;
	const ERROR_API_WRONG_APP_NAME=-20;
	const ERROR_FATAL=-99;
	const ERROR_HTTP_CONTINUE=-100;
	const ERROR_HTTP_SWITCH=-101;
	const ERROR_HTTP_PROCESSING=-102;
	const ERROR_HTTP_OK=-200;
	const ERROR_HTTP_BAD_GATEWAY=-502;
	static $_memorize_cache=null;
	static $_memorize_not_found=null;
	static $_memorize_hkey=null;
	static $_global_context=null;
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
	static function is_instanceof($ctx, $obj, $class_name)
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
	static function is_implements($ctx, $obj, $interface_name)
	{
		$class_name = get_class($obj);
		return self::class_implements($class_name, $interface_name);
	}
	/**
	 * Returns true if class exists
	 * @return bool
	 */
	static function class_exists($ctx, $class_name)
	{
		$class_name = static::find_class($class_name);
		return class_exists($class_name);
	}
	/**
	 * Returns true if class exists
	 * @return bool
	 */
	static function class_implements($ctx, $class_name, $interface_name)
	{
		$class_name = self::find_class($class_name);
		$interface_name = self::find_class($interface_name);
		$arr = @class_implements($class_name, true);
		if ($arr == false){
			return false;
		}
		foreach ($arr as $name)
		{
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
	static function getInterfaces($ctx, $class_name)
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
	static function method_exists($ctx, $class_name, $method_name)
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
	static function newInstance($ctx, $class_name, $args=null)
	{
		$class_name = static::find_class($class_name);
		if ($args == null)
			return new $class_name($ctx);
		$r = new \ReflectionClass($class_name);
		$arr = $args->_arr;
		array_unshift($arr, $ctx);
		return $r->newInstanceArgs($arr);
	}
	/**
	 * Returns callback
	 * @return fn
	 */
	static function method($ctx, $obj, $method_name)
	{
		return new \Runtime\Callback($obj, $method_name);
	}
	/**
	 * Returns callback
	 * @return fn
	 */
	static function apply($ctx, $f, $args)
	{
		$is_ctx = false;
		$is_ctx = true;
		$arr = ($args != null) ? (($args instanceof \Runtime\Collection) ? $args->_getArr() : $args) : [];
		if ($is_ctx) array_unshift($arr, $ctx);
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
	static function applyAsync($ctx, $f, $args)
	{
		$arr = ($args != null) ? (($args instanceof \Runtime\Collection) ? $args->_getArr() : $args) : [];
		if (isset($ctx)) array_unshift($arr, $ctx);
		if ($f instanceof \Runtime\Callback)
		{
			return $f->invokeArgs($arr);
		}
		if (gettype($f) == "string") $f = static::find_class($f);
		return call_user_func_array($f, $arr);
	}
	/**
	 * Apply method
	 * @return var
	 */
	static function methodApply($ctx, $class_name, $method_name, $args=null)
	{
		$f = \Runtime\rtl::method($ctx, $class_name, $method_name);
		return \Runtime\rtl::apply($ctx, $f, $args);
	}
	static function applyMethod($ctx, $class_name, $method_name, $args=null)
	{
		return static::methodApply($ctx, $class_name, $method_name, $args);
	}
	/**
	 * Apply method async
	 * @return var
	 */
	static function methodApplyAsync($ctx, $class_name, $method_name, $args=null)
	{
		$f = \Runtime\rtl::method($ctx, $class_name, $method_name);
		return \Runtime\rtl::applyAsync($ctx, $f, $args);
	}
	static function applyMethodAsync($ctx, $class_name, $method_name, $args=null)
	{
		return static::methodApplyAsync($ctx, $class_name, $method_name, $args);
	}
	/**
	 * Returns value
	 */
	static function get($ctx, $item, $key, $def_val=null)
	{
		return static::attr($ctx, $item, $key, $def_val);
	}
	/**
	 * Returns callback
	 * @return var
	 */
	static function attr($ctx, $item, $path, $def_val=null)
	{
		if ($path === null)
		{
			return $def_val;
		}
		if ($item === null) return $def_val;
		if (gettype($path) == "array") $path = \Runtime\Collection::from($path);
		else if (static::isScalarValue($ctx, $path)) $path = \Runtime\Collection::from([$path]);
		if (!($path instanceof \Runtime\Collection)) return $def_val;
		if (count($path->_arr) == 0) 
		{
			return $item;
		}
		$key = $path->first($ctx);
		$path = $path->removeFirstIm($ctx);
		$val = $def_val;
		if ($item instanceof \Runtime\Dict or $item instanceof \Runtime\Collection)
		{
			$item = $item->get($ctx, $key, $def_val);
			$val = static::attr($ctx, $item, $path, $def_val);
			return $val;
		}
		else if ($item instanceof \Runtime\BaseStruct)
		{
			$item = $item->get($ctx, $key, $def_val);
			$val = static::attr($ctx, $item, $path, $def_val);
			return $val;
		}
		return $val;
	}
	/**
	 * Update current item
	 * @return var
	 */
	static function setAttr($ctx, $item, $attrs, $new_value)
	{
		if ($attrs == null)
		{
			return $item;
		}
		if (gettype($attrs) == "string") $attrs = \Runtime\Collection::from([$attrs]);
		else if (gettype($attrs) == "array") $attrs = \Runtime\Collection::from($attrs);
		$f = function ($ctx, $attrs, $data, $new_value, $f)
		{
			if ($attrs->count($ctx) == 0)
			{
				return $new_value;
			}
			if ($data == null)
			{
				$data = \Runtime\Dict::from([]);
			}
			$new_data = null;
			$attr_name = $attrs->first($ctx);
			if ($data instanceof \Runtime\BaseStruct)
			{
				$attr_data = $data->get($ctx, $attr_name, null);
				$res = $f($ctx, $attrs->removeFirstIm($ctx), $attr_data, $new_value, $f);
				$new_data = $data->copy($ctx, (new \Runtime\Map($ctx))->setValue($ctx, $attr_name, $res));
			}
			else if ($data instanceof \Runtime\Dict)
			{
				$attr_data = $data->get($ctx, $attr_name, null);
				$res = $f($ctx, $attrs->removeFirstIm($ctx), $attr_data, $new_value, $f);
				$new_data = $data->setIm($ctx, $attr_name, $res);
			}
			else if ($data instanceof \Runtime\Collection)
			{
				$attr_data = $data->get($ctx, $attr_name, null);
				$res = $f($ctx, $attrs->removeFirstIm($ctx), $attr_data, $new_value, $f);
				$new_data = $data->setIm($ctx, $attr_name, $res);
			}
			return $new_data;
		};
		$new_item = $f($ctx, $attrs, $item, $new_value, $f);
		return $new_item;
	}
	/**
	 * Returns value
	 * @param var value
	 * @param var def_val
	 * @param var obj
	 * @return var
	 */
	static function to($v, $o)
	{
		$e = $o->e;
		if ($e == "mixed" || $e == "primitive" || $e == "var" || $e == "fn" || $e == "callback")
		{
			return $v;
		}
		if ($e == "bool")
		{
			return static::toBool(null, $v);
		}
		else if ($e == "string")
		{
			return static::toString(null, $v);
		}
		else if ($e == "int")
		{
			return static::toInt(null, $v);
		}
		else if ($e == "float")
		{
			return static::toFloat(null, $v);
		}
		else if (\Runtime\rtl::is_instanceof(null, $v, $e))
		{
			return $v;
		}
		return $v;
	}
	/**
	 * Convert monad by type
	 */
	static function m_to($ctx, $type_value, $def_value=null)
	{
		return function ($ctx, $m) use (&$type_value,&$def_value)
		{
			return new \Runtime\Monad($ctx, ($m->err == null) ? (static::convert($ctx, $m->val, $type_value, $def_value)) : ($def_value));
		};
	}
	/**
	 * Convert monad to default value
	 */
	static function m_def($ctx, $def_value=null)
	{
		return function ($ctx, $m) use (&$def_value)
		{
			return ($m->err != null || $m->val === null) ? (new \Runtime\Monad($ctx, $def_value)) : ($m);
		};
	}
	/**
	 * Returns value if value instanceof type_value, else returns def_value
	 * @param var value
	 * @param string type_value
	 * @param var def_value
	 * @param var type_template
	 * @return var
	 */
	static function convert($ctx, $v, $t, $d=null)
	{
		if ($v === null)
		{
			return $d;
		}
		if ($t == "mixed" || $t == "primitive" || $t == "var" || $t == "fn" || $t == "callback")
		{
			return $v;
		}
		if ($t == "bool" || $t == "boolean")
		{
			return static::toBool($ctx, $v);
		}
		else if ($t == "string")
		{
			return static::toString($ctx, $v);
		}
		else if ($t == "int")
		{
			return static::toInt($ctx, $v);
		}
		else if ($t == "float" || $t == "double")
		{
			return static::toFloat($ctx, $v);
		}
		else if (static::is_instanceof($ctx, $v, $t))
		{
			return $v;
		}
		return static::toObject($ctx, $v, $t, $d);
	}
	/**
	 * Returns true if value instanceof tp
	 * @param var value
	 * @param string tp
	 * @return bool
	 */
	static function checkValue($ctx, $value, $tp)
	{
		if ($tp == "int")
		{
			return \Runtime\rtl::isInt($ctx, $value);
		}
		if ($tp == "float" || $tp == "double")
		{
			return \Runtime\rtl::isDouble($ctx, $value);
		}
		if ($tp == "string")
		{
			return \Runtime\rtl::isString($ctx, $value);
		}
		if ($tp == "bool" || $tp == "boolean")
		{
			return \Runtime\rtl::isBoolean($ctx, $value);
		}
		if (\Runtime\rtl::is_instanceof($ctx, $value, $tp))
		{
			return true;
		}
		return false;
	}
	/**
	 * Return true if value is empty
	 * @param var value
	 * @return bool
	 */
	static function isEmpty($ctx, $value)
	{
		return !static::exists($ctx, $value) || $value === null || $value === "" || $value === false || $value === 0;
	}
	/**
	 * Return true if value is exists
	 * @param var value
	 * @return bool
	 */
	static function exists($ctx, $value)
	{
		return isset($value);
	}
	/**
	 * Returns true if value is scalar value
	 * @return bool 
	 */
	static function isScalarValue($ctx, $value)
	{
		if ($value == null)
		{
			return true;
		}
		if (\Runtime\rtl::isString($ctx, $value))
		{
			return true;
		}
		if (\Runtime\rtl::isNumber($ctx, $value))
		{
			return true;
		}
		if (\Runtime\rtl::isBoolean($ctx, $value))
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
	static function isBoolean($ctx, $value)
	{
		if ($value === false || $value === true)
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
	static function isBool($ctx, $value)
	{
		return static::isBoolean($ctx, $value);
	}
	/**
	 * Return true if value is number
	 * @param var value
	 * @return bool
	 */
	static function isInt($ctx, $value)
	{
		return is_int($value);
	}
	/**
	 * Return true if value is number
	 * @param var value
	 * @return bool
	 */
	static function isDouble($ctx, $value)
	{
		return is_int($value) or is_float($value);
	}
	/**
	 * Return true if value is number
	 * @param var value
	 * @return bool
	 */
	static function isNumber($ctx, $value)
	{
		return is_int($value) or is_float($value);
	}
	/**
	 * Return true if value is string
	 * @param var value
	 * @return bool
	 */
	static function isString($ctx, $value)
	{
		return is_string($value);
	}
	/**
	 * Return true if value is function
	 * @param var value
	 * @return bool
	 */
	static function isFn($ctx, $value)
	{
		return is_callable($value);
		return false;
	}
	/**
	 * Convert value to string
	 * @param var value
	 * @return string
	 */
	static function toString($ctx, $value)
	{
		$t = gettype($value);
		if ($value === null) return "";
		if ($value instanceof \Runtime\RawString) return $value->toString();
		if ($value instanceof \Runtime\Interfaces\StringInterface) return $value->toString();
		if (is_int($value) or is_float($value) or is_string($value) or is_int($value)) return (string)$value;
		if ($value === true) return "1";
		if ($value === false) return "";
		return "";
	}
	/**
	 * Convert value to string
	 * @param var value
	 * @return string
	 */
	static function toStr($value)
	{
		return static::toString(null, $value);
		return static::toString($value);
	}
	/**
	 * Convert value to int
	 * @param var value
	 * @return int
	 */
	static function toInt($ctx, $val)
	{
		$res = (int)$val;
		$s_res = (string)$res;
		$s_val = (string)$val;
		if ($s_res == $s_val)
			return $res;
		return 0;
	}
	/**
	 * Convert value to boolean
	 * @param var value
	 * @return bool
	 */
	static function toBool($ctx, $val)
	{
		if ($val === false || $val === "false") return false;
		if ($val === true || $val === "true") return true;
		$res = (bool)$val;
		$s_res = (string)$res;
		$s_val = (string)$val;
		if ($s_res == $s_val)
			return $res;
		return false;
	}
	/**
	 * Convert value to float
	 * @param var value
	 * @return float
	 */
	static function toFloat($ctx, $val)
	{
		$res = floatval($val);
		$s_res = (string)$res;
		$s_val = (string)$val;
		if ($s_res == $s_val)
			return $res;
		return 0;
	}
	/**
	 * Convert to object
	 */
	static function toObject($ctx, $v, $t, $d=null)
	{
		if (static::is_instanceof($ctx, $v, $t))
		{
			return $v;
		}
		if ($t == "Runtime.Collection")
		{
			return \Runtime\Collection::from($v);
		}
		if ($t == "Runtime.Vector")
		{
			return \Runtime\Vector::from($v);
		}
		if ($t == "Runtime.Dict")
		{
			return \Runtime\Dict::from($v);
		}
		if ($t == "Runtime.Map")
		{
			return \Runtime\Map::from($v);
		}
		try
		{
			
			$newInstance = static::method($ctx, $t, "newInstance");
			return $newInstance($ctx, $v);
		}
		catch (\Exception $_ex)
		{
			if (true)
			{
				$e = $_ex;
			}
			else
			{
				throw $_ex;
			}
		}
		return $d;
	}
	/**
	 * Round up
	 * @param double value
	 * @return int
	 */
	static function ceil($ctx, $value)
	{
		return ceil($value);
	}
	/**
	 * Round down
	 * @param double value
	 * @return int
	 */
	static function floor($ctx, $value)
	{
		return floor($value);
	}
	/**
	 * Round down
	 * @param double value
	 * @return int
	 */
	static function round($ctx, $value)
	{
		return round($value);
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
		static::$_memorize_cache = [];
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
	 * Sleep in ms
	 */
	static function sleep($ctx, $time)
	{
		usleep( $time * 1000 );
	}
	/**
	 * Sleep in microseconds
	 */
	static function usleep($ctx, $time)
	{
		usleep($time);
	}
	/**
	 * Returns unique value
	 * @param bool flag If true returns as text. Default true
	 * @return string
	 */
	static function unique($ctx, $flag=true)
	{
		return uniqid();
	}
	/**
	 * Generate uuid
	 */
	static function uid($ctx)
	{
		$bytes = bin2hex(random_bytes(16));
		return substr($bytes, 0, 8) . "-" .
			substr($bytes, 8, 4) . "-" .
			substr($bytes, 12, 4) . "-" .
			substr($bytes, 16, 4) . "-" .
			substr($bytes, 20);
	}
	/**
	 * Generate timestamp based uuid
	 */
	static function time_uid($ctx)
	{
		$bytes = dechex(time()) . bin2hex(random_bytes(12));
		return substr($bytes, 0, 8) . "-" .
			substr($bytes, 8, 4) . "-" .
			substr($bytes, 12, 4) . "-" .
			substr($bytes, 16, 4) . "-" .
			substr($bytes, 20);
	}
	/**
	 * Returns random value x, where a <= x <= b
	 * @param int a
	 * @param int b
	 * @return int
	 */
	static function random($ctx, $a, $b)
	{
		if (PHP_VERSION_ID < 70000) return mt_rand($a, $b);
		return random_int($a, $b);
	}
	/**
	 * Returns current unix time in seconds
	 * @return int
	 */
	static function time($ctx)
	{
		return time();
	}
	/**
	 * Returns unix timestamp
	 */
	static function utime($ctx)
	{
		return microtime(true);
	}
	/**
	 * Debug
	 */
	function trace()
	{
		$trace = debug_backtrace();
		foreach ($trace as $index => $arr)
		{
			$file = isset($arr['file']) ? $arr['file'] : "";
			$line = isset($arr['line']) ? $arr['line'] : "";
			$function = isset($arr['function']) ? $arr['function'] : "";
			echo "${index}) ${file}:${line} ${function}\n";
		}
	}
	/**
	 * Returns global context
	 * @return Context
	 */
	static function getContext()
	{
		return self::$_global_context;
	}
	/**
	 * Set global context
	 * @param Context context
	 */
	static function setContext($context)
	{
		self::$_global_context = $context;
		return $context;
	}
	/* ============================= Runtime Utils Functions ============================= */
	/**
	 * Json encode data
	 * @param var data
	 * @return string
	 */
	static function json_encode($ctx, $data)
	{
		$f = static::method($ctx, "Runtime.RuntimeUtils", "json_encode");
		return $f($ctx, $data);
	}
	/**
	 * Json decode to primitive values
	 * @param string s Encoded string
	 * @return var
	 */
	static function json_decode($ctx, $obj)
	{
		$f = static::method($ctx, "Runtime.RuntimeUtils", "json_decode");
		return $f($ctx, $obj);
	}
	/**
	 * Returns parents class names
	 * @return Vector<string>
	 */
	static function getParents($ctx, $class_name)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getParents", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$res = new \Runtime\Vector($ctx);
		while ($class_name != "")
		{
			$res->pushValue($ctx, $class_name);
			$class_name = static::methodApply($ctx, $class_name, "getParentClassName");
		}
		$__memorize_value = $res->toCollection($ctx);
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getParents", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns class annotations
	 */
	static function getClassAnnotations($ctx, $class_name, $res=null)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getClassAnnotations", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		if ($res == null)
		{
			$res = \Runtime\Collection::from([]);
		}
		$info = static::methodApply($ctx, $class_name, "getClassInfo");
		$__v0 = new \Runtime\Monad($ctx, \Runtime\rtl::get($ctx, $info, "annotations"));
		$__v0 = $__v0->monad($ctx, \Runtime\rtl::m_to($ctx, "Runtime.Collection", \Runtime\Collection::from([])));
		$arr = $__v0->value($ctx);
		$__memorize_value = $res->concat($ctx, $arr);
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getClassAnnotations", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns class annotations with parents
	 */
	static function getClassAnnotationsWithParents($ctx, $class_name)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getClassAnnotationsWithParents", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$res = \Runtime\Dict::from([]);
		$parents = static::getParents($ctx, $class_name);
		for ($i = 0;$i < $parents->count($ctx);$i++)
		{
			$parent_class_name = \Runtime\rtl::get($ctx, $parents, $i);
			$res = static::getClassAnnotations($ctx, $parent_class_name, $res);
		}
		$__memorize_value = $res;
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getClassAnnotationsWithParents", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns field info
	 */
	static function getFieldInfo($ctx, $class_name, $field_name)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getFieldInfo", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$res = static::methodApply($ctx, $class_name, "getFieldInfoByName", \Runtime\Collection::from([$field_name]));
		$__memorize_value = $res;
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getFieldInfo", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns field info
	 */
	static function getFieldInfoWithParents($ctx, $class_name, $field_name)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getFieldInfoWithParents", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$parents = static::getParents($ctx, $class_name);
		for ($i = 0;$i < $parents->count($ctx);$i++)
		{
			$parent_class_name = \Runtime\rtl::get($ctx, $parents, $i);
			$res = static::methodApply($ctx, $parent_class_name, "getFieldInfoByName", \Runtime\Collection::from([$field_name]));
			if ($res != null)
			{
				$__memorize_value = $res;
				\Runtime\rtl::_memorizeSave("Runtime.rtl.getFieldInfoWithParents", func_get_args(), $__memorize_value);
				return $__memorize_value;
			}
		}
		$__memorize_value = null;
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getFieldInfoWithParents", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns fields of class
	 */
	static function getFields($ctx, $class_name, $flag=255)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getFields", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$names = new \Runtime\Vector($ctx);
		$parents = static::getParents($ctx, $class_name);
		for ($i = 0;$i < $parents->count($ctx);$i++)
		{
			$parent_class_name = \Runtime\rtl::get($ctx, $parents, $i);
			$item_fields = static::methodApply($ctx, $parent_class_name, "getFieldsList", \Runtime\Collection::from([$flag]));
			if ($item_fields != null)
			{
				$names->appendVector($ctx, $item_fields);
			}
		}
		$__memorize_value = $names->toCollection($ctx)->removeDuplicatesIm($ctx);
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getFields", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns fields annotations
	 */
	static function getFieldsAnnotations($ctx, $class_name, $res=null)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getFieldsAnnotations", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		if ($res == null)
		{
			$res = \Runtime\Dict::from([]);
		}
		$methods = static::methodApply($ctx, $class_name, "getFieldsList", \Runtime\Collection::from([255]));
		for ($i = 0;$i < $methods->count($ctx);$i++)
		{
			$method_name = \Runtime\rtl::get($ctx, $methods, $i);
			$info = static::methodApply($ctx, $class_name, "getFieldInfoByName", \Runtime\Collection::from([$method_name]));
			$annotations = \Runtime\rtl::get($ctx, $info, "annotations");
			$__v0 = new \Runtime\Monad($ctx, \Runtime\rtl::get($ctx, $res, $method_name));
			$__v0 = $__v0->monad($ctx, \Runtime\rtl::m_to($ctx, "Runtime.Collection", \Runtime\Collection::from([])));
			$arr = $__v0->value($ctx);
			$res = \Runtime\rtl::setAttr($ctx, $res, [$method_name], $arr->concat($ctx, $annotations));
		}
		$__memorize_value = $res;
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getFieldsAnnotations", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns fields annotations with parents
	 */
	static function getFieldsAnnotationsWithParents($ctx, $class_name)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getFieldsAnnotationsWithParents", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$res = \Runtime\Dict::from([]);
		$parents = static::getParents($ctx, $class_name);
		for ($i = 0;$i < $parents->count($ctx);$i++)
		{
			$parent_class_name = \Runtime\rtl::get($ctx, $parents, $i);
			$res = static::getFieldsAnnotations($ctx, $parent_class_name, $res);
		}
		$__memorize_value = $res;
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getFieldsAnnotationsWithParents", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns methods annotations
	 */
	static function getMethodsAnnotations($ctx, $class_name, $res=null)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getMethodsAnnotations", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		if ($res == null)
		{
			$res = \Runtime\Dict::from([]);
		}
		$methods = static::methodApply($ctx, $class_name, "getMethodsList", \Runtime\Collection::from([255]));
		for ($i = 0;$i < $methods->count($ctx);$i++)
		{
			$method_name = \Runtime\rtl::get($ctx, $methods, $i);
			$info = static::methodApply($ctx, $class_name, "getMethodInfoByName", \Runtime\Collection::from([$method_name]));
			$annotations = \Runtime\rtl::get($ctx, $info, "annotations");
			$__v0 = new \Runtime\Monad($ctx, \Runtime\rtl::get($ctx, $res, $method_name));
			$__v0 = $__v0->monad($ctx, \Runtime\rtl::m_to($ctx, "Runtime.Collection", \Runtime\Collection::from([])));
			$arr = $__v0->value($ctx);
			$res = \Runtime\rtl::setAttr($ctx, $res, [$method_name], $arr->concat($ctx, $annotations));
		}
		$__memorize_value = $res;
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getMethodsAnnotations", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns methods annotations with parents
	 */
	static function getMethodsAnnotationsWithParents($ctx, $class_name)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.rtl.getMethodsAnnotationsWithParents", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$res = \Runtime\Dict::from([]);
		$parents = static::getParents($ctx, $class_name);
		for ($i = 0;$i < $parents->count($ctx);$i++)
		{
			$parent_class_name = \Runtime\rtl::get($ctx, $parents, $i);
			$res = static::getMethodsAnnotations($ctx, $parent_class_name, $res);
		}
		$__memorize_value = $res;
		\Runtime\rtl::_memorizeSave("Runtime.rtl.getMethodsAnnotationsWithParents", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns module path. For backend only
	 */
	static function getModulePath($ctx, $module_name)
	{
		$class = "\\" . preg_replace("/\\./", "\\", $module_name . ".ModuleDescription");
		$reflector = new \ReflectionClass($class);
		$path = $reflector->getFileName();
		return dirname( dirname($path) );
		return "";
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
		if ($field_name == "LOG_FATAL") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "LOG_CRITICAL") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "LOG_ERROR") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "LOG_WARNING") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "LOG_INFO") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "LOG_DEBUG") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "LOG_DEBUG2") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "STATUS_PLAN") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "STATUS_DONE") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "STATUS_PROCESS") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "STATUS_FAIL") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_NULL") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_OK") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_PROCCESS") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_FALSE") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_UNKNOWN") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_INDEX_OUT_OF_RANGE") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_KEY_NOT_FOUND") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_STOP_ITERATION") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_FILE_NOT_FOUND") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_ITEM_NOT_FOUND") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_OBJECT_DOES_NOT_EXISTS") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_OBJECT_ALLREADY_EXISTS") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_ASSERT") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_REQUEST") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_RESPONSE") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_CSRF_TOKEN") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_RUNTIME") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_VALIDATION") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_PARSE_SERIALIZATION_ERROR") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_ASSIGN_DATA_STRUCT_VALUE") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_AUTH") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_DUPLICATE") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_API_NOT_FOUND") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_API_WRONG_FORMAT") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_API_WRONG_APP_NAME") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_FATAL") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_HTTP_CONTINUE") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_HTTP_SWITCH") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_HTTP_PROCESSING") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_HTTP_OK") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ERROR_HTTP_BAD_GATEWAY") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "_memorize_cache") return \Runtime\Dict::from([
			"t"=>"var",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "_memorize_not_found") return \Runtime\Dict::from([
			"t"=>"var",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "_memorize_hkey") return \Runtime\Dict::from([
			"t"=>"var",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "_global_context") return \Runtime\Dict::from([
			"t"=>"var",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		return null;
	}
	static function getMethodsList($ctx,$f=0)
	{
		$a = [];
		if (($f&4)==4) $a=[
			"getModulePath",
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}
rtl::$_memorize_not_found = (object) ['s' => 'memorize_key_not_found'];