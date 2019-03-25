<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2018 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.bayrell.org/licenses/APACHE-LICENSE-2.0.html
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
namespace Runtime;
use Runtime\Interfaces\StringInterface;
class rtl{
	static function isBrowser(){
		return false;
	}
	
	/**
	 * Find class instance by name. If class does not exists return null.
	 * @return var - class instance
	 */
	static function find_class($class_name){
		return "\\" . re::replace("\\.", "\\", $class_name);
	}
	/**
	 * Returns true if class instanceof class_name
	 * @return bool
	 */
	
	static function is_instanceof($obj, $class_name){
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
	
	static function is_implements($obj, $interface_name){
		$class_name = get_class($obj);
		$interface_name = self::find_class($interface_name);
		return self::class_implements($class_name, $interface_name);
	}
	/**
	 * Returns true if class exists
	 * @return bool
	 */
	
	static function class_exists($class_name){
		$class_name = static::find_class($class_name);
		return class_exists($class_name);
	}
	/**
	 * Returns true if class exists
	 * @return bool
	 */
	
	static function class_implements($class_name, $interface_name){
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
	 * Returns true if class exists
	 * @return bool
	 */
	
	static function method_exists($class_name, $method_name){
		$class_name = static::find_class($class_name);
		if (!class_exists($class_name)) return false;
		if (!method_exists($class_name, $method_name)) return false;
		return true;
	}
	/**
	 * Create object by class_name. If class name does not exists return null
	 * @return Object
	 */
	
	static function newInstance($class_name, $args = null){
		$class_name = static::find_class($class_name);
		if ($args == null)
			return new $class_name();
		$r = new \ReflectionClass($class_name);
		return $r->newInstanceArgs($args->_getArr());
	}
	/**
	 * Call method
	 * @return Object
	 */
	static function f($f){
		return $f;
	}
	/**
	 * Returns callback
	 * @return fun
	 */
	
	static function method($obj, $method_name){
		return new \Runtime\Callback($obj, $method_name);
	}
	/**
	 * Returns callback
	 * @return fun
	 */
	
	static function methodAwait($obj, $method_name){
		return new \Runtime\Callback($obj, $method_name);
	}
	/**
	 * Returns value if value instanceof type_value, else returns def_value
	 * @param var value
	 * @param string type_value
	 * @param var def_value
	 * @param var type_template
	 * @return var
	 */
	static function convert($value, $type_value, $def_value = null, $type_template = ""){
		if ($type_value == "mixed" || $type_value == "primitive" || $type_value == "var" || $type_value == "fun"){
			return $value;
		}
		if ($type_value == ""){
			return $def_value;
		}
		if ($value != null){
			if ($type_value == "int" || $type_value == "float" || $type_value == "double" || $type_value == "bool" || $type_value == "string"){
				try{
					if ($type_value == "int"){
						$val = rtl::toInt($value);
						return $val;
					}
					else if ($type_value == "float" || $type_value == "double"){
						$val = rtl::toFloat($value);
						return $val;
					}
					else if ($type_value == "bool"){
						$val = rtl::toBool($value);
						return $val;
					}
					else if ($type_value == "string"){
						$val = rtl::toString($value);
						return $val;
					}
				}catch(\Exception $_the_exception){
					if ($_the_exception instanceof \Exception){
						$e = $_the_exception;
					}
					else { throw $_the_exception; }
				}
			}
			else if (($type_value == "Runtime.Vector" || $type_value == "Runtime.Map" || $type_value == "Runtime.Collection" || $type_value == "Runtime.Dict") && $type_template != ""){
				
				if ($value instanceof \Runtime\Collection or $value instanceof \Runtime\Dict)
				{
					return $value->_correctItemsByType($type_template);
				}
				return null;
			}
			else if (rtl::is_instanceof($value, $type_value)){
				return $value;
			}
		}
		return $def_value;
	}
	/**
	 * Returns true if value instanceof tp
	 * @param var value
	 * @param string tp
	 * @return bool
	 */
	static function checkValue($value, $tp){
		if ($tp == "int"){
			return (new \Runtime\Callback(self::class, "isInt"))($value);
		}
		if ($tp == "float" || $tp == "double"){
			return (new \Runtime\Callback(self::class, "isDouble"))($value);
		}
		if ($tp == "string"){
			return (new \Runtime\Callback(self::class, "isString"))($value);
		}
		if ($tp == "bool" || $tp == "boolean"){
			return (new \Runtime\Callback(self::class, "isBoolean"))($value);
		}
		if (rtl::is_instanceof($value, $tp)){
			return true;
		}
		return false;
	}
	/**
	 * Clone var
	 * @param {var} value - Variable
	 * @return {var} result
	 */
	
	static function _clone($val){
		if ($val == null) return null;
		if (self::isScalarValue($val)) return $val;
		if ($val instanceof \Runtime\Interfaces\CloneableInterface){
			$class_name = get_class($val);
			$obj = new $class_name();
			$obj->assignObject($val);
			return $obj;
		}
		return clone $val;
	}
	/**
	 * Return true if value is exists
	 * @param var value
	 * @return boolean
	 */
	
	static function exists(&$value){ 
		return isset($value);
	}
	/**
	 * Returns true if value is scalar value
	 * @return boolean 
	 */
	static function isScalarValue($value){
		if ($value == null){
			return true;
		}
		if (rtl::isString($value)){
			return true;
		}
		if (rtl::isNumber($value)){
			return true;
		}
		if (rtl::isBoolean($value)){
			return true;
		}
		return false;
	}
	/**
	 * Return true if value is boolean
	 * @param var value
	 * @return boolean
	 */
	static function isBoolean($value){
		if ($value === false || $value === true){
			return true;
		}
		return false;
	}
	/**
	 * Return true if value is number
	 * @param var value
	 * @return boolean
	 */
	
	static function isInt($value){
		return is_int($value);
	}
	/**
	 * Return true if value is number
	 * @param var value
	 * @return boolean
	 */
	
	static function isDouble($value){
		return is_int($value) or is_float($value);
	}
	/**
	 * Return true if value is number
	 * @param var value
	 * @return boolean
	 */
	
	static function isNumber($value){
		return is_int($value) or is_float($value);
	}
	/**
	 * Return true if value is string
	 * @param var value
	 * @return boolean
	 */
	
	static function isString($value){
		return is_string($value);
	}
	/**
	 * Convert value to string
	 * @param var value
	 * @return string
	 */
	
	static function toString($value){
		if ($value instanceof StringInterface) return $value->toString();
		return (string)$value;
	}
	/**
	 * Convert value to int
	 * @param var value
	 * @return int
	 */
	
	static function toInt($val){
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
	
	static function toBool($val){
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
	
	static function toFloat($val){
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
	
	static function ceil($value){
		return ceil($value);
	}
	/**
	 * Round down
	 * @param double value
	 * @return int
	 */
	
	static function floor($value){
		return floor($value);
	}
	/**
	 * Round down
	 * @param double value
	 * @return int
	 */
	
	static function round($value){
		return round($value);
	}
	/**
	 * Convert module name to node js package
	 */
	
	static function convertNodeJSModuleName($name){
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
			if ($ch == $ch2 && $isAlphaNum){
				$previsbig = true;
			}
			else {
				$previsbig = false;
			}
			if (!$isAlphaNum && !$isNum){
				$previsbig = true;
			}
		}
		$res .= "-nodejs";
		return $res;
	}
	/* ================ Memorize functions ================ */
	static public $_memorize_cache = null;
	
	static $_memorize_not_found = null;
	static $_memorize_hkey = [];
	static function _memorizeValidHKey($hkey, $key)
	{
		if ( !isset(static::$_memorize_hkey[$hkey]) ) return false;
		if ( static::$_memorize_hkey[$hkey] == $key ) return true;
		return false;
	}
	/**
	 * Clear memorize cache
	 */
	static function _memorizeClear(){
		static::$_memorize_cache = null;
		
		static::$_memorize_hkey = [];
	}
	/**
	 * Returns cached value
	 */
	
	static function &_memorizeValue($name, $args)
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
				if (array_key_exists($hkey, $arr))
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
	
	static function unique(){
		return uniqid();
	}
	/**
	 * Round down
	 * @param double value
	 * @return int
	 */
	
	static function dump($value){
		var_dump($value);
	}
	/**
	 * Returns random value x, where a <= x <= b
	 * @param int a
	 * @param int b
	 * @return int
	 */
	
	static function random($a, $b){
		if (PHP_VERSION_ID < 70000) return mt_rand($a, $b);
		return random_int($a, $b);
	}
	/**
	 * Returns current unix time in seconds
	 * @return int
	 */
	
	static function time(){
		return time();
	}
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params MapInterface params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	static function translate($message, $params = null, $locale = "", $context = null){
		
		return self::callStaticMethod("Runtime.RuntimeUtils", "translate", [$message, $params, $locale, $context]);
	}
	/**
	 * Json encode data
	 * @param mixed data
	 * @return string
	 */
	static function json_encode($data){
		
		return self::callStaticMethod("Runtime.RuntimeUtils", "json_encode", [$data]);
	}
	/**
	 * Normalize UIStruct
	 * @param mixed data
	 * @return UIStruct
	 */
	static function normalizeUI($data){
		
		return self::callStaticMethod("Runtime.RuntimeUtils", "normalizeUI", [$data]);
	}
	/**
	 * Normalize UIStruct
	 * @param mixed data
	 * @return UIStruct
	 */
	static function normalizeUIVector($data){
		
		return self::callStaticMethod("Runtime.RuntimeUtils", "normalizeUIVector", [$data]);
	}
	/* =================== Deprecated =================== */
	/**
	 * Call method
	 * @return Object
	 */
	
	static function call($f, $args = null){
		if ($args == null) return call_user_func($f);
		if ($args instanceof \Runtime\Vector) $args = $args->_getArr();
		return call_user_func_array($f, $args);
	}
	/**
	 * Call method
	 * @return Object
	 */
	
	static function callMethod($obj, $method_name, $args = null){
		if ($args != null)
			return call_user_func_array([$obj, $method_name], $args->_getArr());
		return call_user_func([$obj, $method_name]);
	}
	/**
	 * Call method
	 * @return Object
	 */
	
	static function callStaticMethod($class_name, $method_name, $args=null){
		$class_name = static::find_class($class_name);
		if (!class_exists($class_name)){
			throw new \Exception($class_name . " not found ");
		}
		if (!method_exists($class_name, $method_name)){
			throw new \Exception("Method '" . $method_name . "' not found in " . $class_name);
		}
		if (gettype($args) == 'array')
			return call_user_func_array([$class_name, $method_name], $args);
		return call_user_func_array([$class_name, $method_name], ($args!=null)?$args->_getArr():[]);
	}
	/**
	 * Returns value if value instanceof type_value, else returns def_value
	 * @param var value
	 * @param string type_value
	 * @param var def_value
	 * @param var type_template
	 * @return var
	 */
	static function correct($value, $type_value, $def_value = null, $type_template = ""){
		return static::convert($value, $type_value, $def_value, $type_template);
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.rtl";}
	public static function getCurrentClassName(){return "Runtime.rtl";}
	public static function getParentClassName(){return "";}
}

rtl::$_memorize_not_found = (object) ['s' => 'memorize_key_not_found'];