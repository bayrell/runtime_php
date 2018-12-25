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
class RuntimeUtils{
	/* ================================ Context Functions ================================ */
	static protected $_global_context = null;
	/**
	 * Returns global context
	 * @return ContextInterface
	 */
	static function getContext(){
		
		return self::$_global_context;
	}
	/**
	 * Set global context
	 * @param ContextInterface context
	 */
	static function setContext($context){
		
		self::$_global_context = $context;
		return $context;
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
		static::setContext($context);
		return $context;
	}
	/* ========================== Class Introspection Functions ========================== */
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
	 * Returns names of variables to serialization
	 * @param Vector<string>
	 */
	static function getVariablesNames($class_name, $names){
		$classes = static::getParents($class_name);
		$classes->prepend($class_name);
		$classes->each(function ($class_name) use (&$names){
			try{
				rtl::callStaticMethod($class_name, "getFieldsList", (new Vector())->push($names));
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
			try{
				rtl::callStaticMethod($class_name, "getVirtualFieldsList", (new Vector())->push($names));
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
		});
		$names->removeDublicates();
	}
	/**
	 * Returns Introspection of the class name
	 * @param string class_name
	 * @return Vector<IntrospectionInfo>
	 */
	static function getIntrospection($class_name){
		$res = new Vector();
		$class_names = static::getParents($class_name);
		$class_names->prepend($class_name);
		$class_names->each(function ($item_class_name) use (&$res){
			$names = new Vector();
			/* Get fields introspection */
			try{
				rtl::callStaticMethod($item_class_name, "getFieldsList", (new Vector())->push($names));
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
			$names->each(function ($field_name) use (&$res, &$item_class_name){
				$info = null;
				try{
					$info = rtl::callStaticMethod($item_class_name, "getFieldInfoByName", (new Vector())->push($field_name));
				}catch(\Exception $_the_exception){
					if ($_the_exception instanceof \Exception){
						$e = $_the_exception;
						$info = null;
					}
					else { throw $_the_exception; }
				}
				if ($info != null){
					$info->class_name = $item_class_name;
					$res->push($info);
				}
			});
			/* Get virtual fields introspection */
			$names->clear();
			try{
				rtl::callStaticMethod($item_class_name, "getVirtualFieldsList", (new Vector())->push($names));
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
			$names->each(function ($field_name) use (&$res, &$item_class_name){
				$info = null;
				try{
					$info = rtl::callStaticMethod($item_class_name, "getVirtualFieldInfo", (new Vector())->push($field_name));
				}catch(\Exception $_the_exception){
					if ($_the_exception instanceof \Exception){
						$e = $_the_exception;
						$info = null;
					}
					else { throw $_the_exception; }
				}
				if ($info != null){
					$info->class_name = $item_class_name;
					$res->push($info);
				}
			});
			/* Get methods introspection */
			$names->clear();
			try{
				rtl::callStaticMethod($item_class_name, "getMethodsList", (new Vector())->push($names));
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
			$names->each(function ($method_name) use (&$res, &$item_class_name){
				$info = null;
				try{
					$info = rtl::callStaticMethod($item_class_name, "getMethodInfoByName", (new Vector())->push($method_name));
				}catch(\Exception $_the_exception){
					if ($_the_exception instanceof \Exception){
						$e = $_the_exception;
						$info = null;
					}
					else { throw $_the_exception; }
				}
				if ($info != null){
					$info->class_name = $item_class_name;
					$res->push($info);
				}
			});
			/* Get class introspection */
			try{
				$info = rtl::callStaticMethod($item_class_name, "getClassInfo", (new Vector()));
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
					$info = null;
				}
				else { throw $_the_exception; }
			}
			if ($info != null){
				$info->class_name = $item_class_name;
				$res->push($info);
			}
		});
		return $res;
	}
	/* ============================= Serialization Functions ============================= */
	static function ObjectToNative($value, $force_class_name = false){
		$value = static::ObjectToPrimitive($value, $force_class_name);
		$value = static::PrimitiveToNative($value);
		return $value;
	}
	static function NativeToObject($value){
		$value = static::NativeToPrimitive($value);
		$value = static::PrimitiveToObject($value);
		return $value;
	}
	/**
	 * Returns object to primitive value
	 * @param mixed obj
	 * @return mixed
	 */
	static function ObjectToPrimitive($obj, $force_class_name = false){
		if ($obj === null){
			return null;
		}
		if (rtl::isScalarValue($obj)){
			return $obj;
		}
		if ($obj instanceof Vector){
			$res = new Vector();
			for ($i = 0; $i < $obj->count(); $i++){
				$value = $obj->item($i);
				$value = static::ObjectToPrimitive($value, $force_class_name);
				$res->push($value);
			}
			return $res;
		}
		if ($obj instanceof Map){
			$res = new Map();
			$keys = $obj->keys();
			for ($i = 0; $i < $keys->count(); $i++){
				$key = $keys->item($i);
				$value = $obj->item($key);
				$value = static::ObjectToPrimitive($value, $force_class_name);
				$res->set($key, $value);
			}
			if ($force_class_name){
				$res->set("__class_name__", "Runtime.Map");
			}
			return $res;
		}
		if ($obj instanceof SerializeInterface){
			$names = new Vector();
			$values = new Map();
			$obj->getVariablesNames($names);
			for ($i = 0; $i < $names->count(); $i++){
				$variable_name = $names->item($i);
				$value = $obj->takeValue($variable_name, null);
				$value = static::ObjectToPrimitive($value, $force_class_name);
				$values->set($variable_name, $value);
			}
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
	static function PrimitiveToObject($obj){
		if ($obj === null){
			return null;
		}
		if (rtl::isScalarValue($obj)){
			return $obj;
		}
		if ($obj instanceof Vector){
			$res = new Vector();
			for ($i = 0; $i < $obj->count(); $i++){
				$value = $obj->item($i);
				$value = static::PrimitiveToObject($value);
				$res->push($value);
			}
			return $res;
		}
		if ($obj instanceof Map){
			$res = new Map();
			$keys = $obj->keys();
			for ($i = 0; $i < $keys->count(); $i++){
				$key = $keys->item($i);
				$value = $obj->item($key);
				$value = static::PrimitiveToObject($value);
				$res->set($key, $value);
			}
			if (!$res->has("__class_name__")){
				return $res;
			}
			if ($res->item("__class_name__") == "Runtime.Map"){
				$res->remove("__class_name__");
				return $res;
			}
			$class_name = $res->item("__class_name__");
			if (!rtl::class_exists($class_name)){
				return null;
			}
			if (!rtl::class_implements($class_name, "Runtime.Interfaces.SerializeInterface")){
				return null;
			}
			$instance = rtl::newInstance($class_name, null);
			$names = new Vector();
			$instance->getVariablesNames($names);
			for ($i = 0; $i < $names->count(); $i++){
				$variable_name = $names->item($i);
				if ($variable_name != "__class_name__"){
					$value = $res->get($variable_name, null);
					$instance->assignValue($variable_name, $value);
				}
			}
			return $instance;
		}
		return null;
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
			if ( isset($value['__class_name__']) ){
				$res = new \Runtime\Map($value);
				$res = $res->map(function ($key, $val){
					return self::NativeToPrimitive($val);
				});
				return $res;
			}
			$arr = array_values($value);
			$res = (new \Runtime\Vector())->_assignArr($arr);
			$res = $res->map(function ($item){
				return self::NativeToPrimitive($item);
			});
			return $res;
		}
		
		return $value;
	}
	const JSON_PRETTY = 1;
	/**
	 * Json encode serializable values
	 * @param serializable value
	 * @param SerializeContainer container
	 * @return string 
	 */
	
	static function json_encode($value, $flags = 0, $convert = true){
		if ($convert){
			$value = self::ObjectToPrimitive($value);
		}
		$json_flags = JSON_UNESCAPED_UNICODE;
		if ( ($flags & 1) == 1 ) $json_flags = $json_flags | JSON_PRETTY_PRINT;
		return json_encode($value, $json_flags);
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
	/* ================================= Other Functions ================================= */
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
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.RuntimeUtils";}
	public static function getParentClassName(){return "";}
}