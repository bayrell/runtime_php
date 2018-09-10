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
use Runtime\Context;
use Runtime\Map;
use Runtime\rs;
use Runtime\rtl;
use Runtime\Vector;
use Runtime\Interfaces\ContextInterface;
use Runtime\Interfaces\FactoryInterface;
use Runtime\Interfaces\SerializeInterface;
class Utils{
	static protected $_global_context = null;
	public function getClassName(){return "Runtime.Utils";}
	public static function getParentClassName(){return "";}
	/**
	 * Returns global context
	 * @return ContextInterface
	 */
	static function globalContext(){
		
		return self::$_global_context;
	}
	/**
	 * Set global context
	 * @param ContextInterface context
	 */
	static function setGlobalContext($context){
		
		self::$_global_context = $context;
		return $context;
	}
	/**
	 * Returns global context
	 * @param Context context
	 */
	static function getGlobalContext(){
		return static::globalContext();
	}
	/**
	 * Register global Context
	 */
	static function createContext($modules = null){
		$context = new Context();
		if ($modules != null){
			$modules->each(function ($module) use (&$context){
				$context->registerModule($module);
			});
		}
		return $context;
	}
	/**
	 * Register global Context
	 */
	static function registerGlobalContext($modules = null){
		$context = static::createContext($modules);
		$context->init();
		static::setGlobalContext($context);
		return $context;
	}
	/**
	 * Returns parents class names
	 * @return Vector<string>
	 */
	static function getParents($class_name){
		$res = new Vector();
		while ($class_name != ""){
			$class_name = rtl::callStaticMethod($class_name, "getParentClassName");
			if ($class_name != ""){
				$res->push($class_name);
			}
		}
		return $res;
	}
	/**
	 * Returns true if class exists
	 * @return Vector<string>
	 */
	
	static function getInterfaces($class_name){
		$arr = array_values(class_implements(rtl::find_class($class_name)));
		$v = (new Vector())->_assignArr($arr);
		$v = $v->map(function ($s){
			return str_replace("\\", ".", $s);
		});
		return $v;
	}
	/**
	 * Returns true if value is primitive value
	 * @return boolean 
	 */
	static function isPrimitiveValue($value){
		if (rtl::isScalarValue($value)){
			return true;
		}
		if ($value instanceof Vector){
			return true;
		}
		if ($value instanceof Map){
			return true;
		}
		return false;
	}
	/**
	 * Get value from object
	 */
	static function get($obj, $key, $default_value = null){
		if ($obj instanceof Vector){
			return $obj->get($key, $default_value);
		}
		if ($obj instanceof Map){
			return $obj->get($key, $default_value);
		}
		return $default_value;
	}
	/**
	 * Set value to object
	 */
	static function set($obj, $key, $value = null){
		if ($obj instanceof Vector){
			$obj->set($key, $value);
		}
		if ($obj instanceof Map){
			$obj->set($key, $value);
		}
	}
	/**
	 * Call each
	 */
	static function each($obj, $f){
		if ($obj instanceof Vector){
			$obj->each($f);
		}
		if ($obj instanceof Map){
			$obj->each($f);
		}
	}
	/**
	 * Convert bytes to string
	 * @param Vector<byte> arr - vector of the bytes
	 * @string charset - charset of the bytes vector. Default utf8
	 * @return string
	 */
	function bytesToString($arr, $charset = "utf8"){
	}
	/**
	 * Convert string to bytes
	 * @param string s - incoming string
	 * @param Vector<byte> arr - output vector
	 * @param charset - Result bytes charset. Default utf8
	 */
	function stringToBytes($s, $arr, $charset = "utf8"){
	}
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params MapInterface params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	static function translate($message, $params = null, $locale = "", $context = null){
		if ($context == null){
			$context = static::globalContext();
		}
		if ($context != null){
			$args = (new Vector())->push($message)->push($params)->push($locale);
			return rtl::callMethod($context, "translate", $args);
		}
		return $message;
	}
	/**
	 * Compare 2 Vectors, Returns true if arr1 and arr2 have same class names
	 * @param Vector<string> arr1
	 * @param Vector<string> arr2
	 * @return bool
	 */
	static function equalsVectors($arr1, $arr2){
		for ($i = 0; $i < $arr1->count(); $i++){
			$item = $arr1->item($i);
			if ($arr2->indexOf($item) == -1){
				return false;
			}
		}
		for ($i = 0; $i < $arr2->count(); $i++){
			$item = $arr2->item($i);
			if ($arr1->indexOf($item) == -1){
				return false;
			}
		}
		return true;
	}
	/**
	 * Returns object to primitive value
	 * @param mixed obj
	 * @return mixed
	 */
	static function ObjectToPrimitive($obj){
		if ($obj === null){
			return null;
		}
		if (rtl::isScalarValue($obj)){
			return $obj;
		}
		if ($obj instanceof Vector){
			return $obj->map(function ($value){
				return static::ObjectToPrimitive($value);
			});
		}
		if ($obj instanceof Map){
			$obj = $obj->map(function ($key, $value){
				return static::ObjectToPrimitive($value);
			});
			return $obj;
		}
		if ($obj instanceof SerializeInterface){
			$names = new Vector();
			$values = new Map();
			$obj->getVariablesNames($names);
			$names->each(function ($variable_name) use (&$values, &$obj){
				$value = $obj->takeValue($variable_name, null);
				$value = static::ObjectToPrimitive($value);
				$values->set($variable_name, $value);
			});
			$values->set("__class_name__", $obj->getClassName());
			return $values;
		}
		return null;
	}
	/**
	 * Returns object to primitive value
	 * @param SerializeContainer container
	 * @return mixed
	 */
	static function PrimitiveToObject($obj, $context = null){
		if ($obj === null){
			return null;
		}
		if (rtl::isScalarValue($obj)){
			return $obj;
		}
		if ($context == null){
			$context = static::globalContext();
		}
		if ($obj instanceof Vector){
			return $obj->map(function ($value) use (&$context){
				return static::PrimitiveToObject($value, $context);
			});
		}
		if ($obj instanceof Map){
			$obj = $obj->map(function ($key, $value) use (&$context){
				return static::PrimitiveToObject($value, $context);
			});
			if (!$obj->has("__class_name__")){
				return $obj;
			}
			if ($obj->item("__class_name__") == "Runtime.Map"){
				$obj->remove("__class_name__");
				return $obj;
			}
			$class_name = $obj->item("__class_name__");
			if (!rtl::class_exists($class_name)){
				return null;
			}
			if (!rtl::class_implements($class_name, "Runtime.Interfaces.SerializeInterface")){
				return null;
			}
			$instance = rtl::newInstance($class_name, null);
			$names = new Vector();
			$instance->getVariablesNames($names);
			$names->each(function ($variable_name) use (&$instance, &$obj){
				if ($variable_name == "__class_name__"){
					return ;
				}
				$value = $obj->get($variable_name, null);
				$instance->assignValue($variable_name, $value);
			});
			return $instance;
		}
		return null;
	}
	/**
	 * Json encode serializable values
	 * @param serializable value
	 * @param SerializeContainer container
	 * @return string 
	 */
	
	static function json_encode($value, $convert = true){
		if ($convert){
			$value = self::ObjectToPrimitive($value);
		}
		return json_encode($value, JSON_UNESCAPED_UNICODE);
	}
	/**
	 * Json decode to primitive values
	 * @param string s Encoded string
	 * @return mixed 
	 */
	
	static function json_decode($obj){
		$res = @json_decode($obj, false);
		if (!$res)
			return null;
		return self::NativeToObject($res);
	}
	
	static function NativeToPrimitive($value){
		if ($value === null)
			return null;
			
		if (is_object($value)){
			$res = new \Runtime\Map($value);
			$res = $res->map(function ($key, $val){
				return self::NativeToPrimitive($val);
			});
			return $res;
		}
		
		if (is_array($value)){
			$arr = array_values($value);
			$res = (new \Runtime\Vector())->_assignArr($arr);
			$res = $res->map(function ($item){
				return self::NativeToPrimitive($item);
			});
			return $res;
		}
		
		return $value;
	}
	static function ObjectToNative($value){
		$value = static::ObjectToPrimitive($value);
		$value = static::PrimitiveToNative($value);
		return $value;
	}
	static function NativeToObject($value){
		$value = static::NativeToPrimitive($value);
		$value = static::PrimitiveToObject($value);
		return $value;
	}
	/*
	 * Generate password
	 *
	 * @param int length The lenght of the password
	 * @param string options What kinds of the char can be in password
	 *   a - lower case chars
	 *   b - upper case chars
	 *   c - numbers
	 *   d - special chars !@#$%^&?*_-+=~(){}[]<>|/,.:;\\
	 *   e - quotes `"'
	 */
	static function randomString($length = 16, $options = "abc"){
		$s = "";
		if (rs::strpos($options, "a") >= 0){
			$s .= "abcdefghjkmnpqrstuvwxyz";
		}
		if (rs::strpos($options, "b") >= 0){
			$s .= "ABCDEFGHJKMNPQRSTUVWXYZ";
		}
		if (rs::strpos($options, "c") >= 0){
			$s .= "1234567890";
		}
		if (rs::strpos($options, "d") >= 0){
			$s .= "!@#\$%^&?*_-+=~(){}[]<>|/,.:;\\";
		}
		if (rs::strpos($options, "e") >= 0){
			$s .= "`\"'";
		}
		$res = "";
		$c = rs::strlen($s);
		for ($i = 0; $i < $length; $i++){
			$k = rtl::random(0, $c - 1);
			$res .= mb_substr($s, $k, 1);
		}
		return $res;
	}
	/**
	 * Base64 encode
	 * @param string s
	 * @return string 
	 */
	
	static function base64_encode($s){
		return base64_encode($s);
	}
	/**
	 * Base64 decode
	 * @param string s
	 * @return string 
	 */
	
	static function base64_decode($s){
		return base64_decode($s);
	}
}