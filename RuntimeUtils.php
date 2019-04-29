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
use Runtime\Collection;
use Runtime\Context;
use Runtime\CoreStruct;
use Runtime\Dict;
use Runtime\Map;
use Runtime\rs;
use Runtime\rtl;
use Runtime\UIStruct;
use Runtime\Vector;
use Runtime\Interfaces\ContextInterface;
use Runtime\Interfaces\FactoryInterface;
use Runtime\Interfaces\SerializeInterface;
class RuntimeUtils{
	/* ================================ Context Functions ================================ */
	static protected $_global_context = null;
	static protected $_variables_names = null;
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
		$context = (new \Runtime\Callback(self::class, "createContext"))($modules);
		$context->init();
		(new \Runtime\Callback(self::class, "setContext"))($context);
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
			$f = rtl::method($class_name, "getParentClassName");
			$class_name = $f();
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
	static function getVariablesNames($class_name, $names, $flag = 0){
		$variables_names = RuntimeUtils::$_variables_names;
		if ($variables_names == null){
			RuntimeUtils::$_variables_names = new Map();
		}
		if (RuntimeUtils::$_variables_names->has($class_name)){
			$m = RuntimeUtils::$_variables_names;
			$v = $m->item($class_name);
			if ($v->has($flag)){
				$names->appendVector($v->item($flag));
				return ;
			}
		}
		$classes = (new \Runtime\Callback(self::class, "getParents"))($class_name);
		$classes->prepend($class_name);
		$classes->each(function ($class_name) use (&$names, &$flag){
			try{
				rtl::method($class_name, "getFieldsList")($names, $flag);
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
			try{
				rtl::method($class_name, "getVirtualFieldsList")($names, $flag);
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
		});
		$names = $names->removeDublicatesIm();
		$variables_names = RuntimeUtils::$_variables_names;
		if (!RuntimeUtils::$_variables_names->has($class_name)){
			RuntimeUtils::$_variables_names->set($class_name, (new Map()));
		}
		$v = RuntimeUtils::$_variables_names->item($class_name);
		$v->set($flag, $names->copy());
		RuntimeUtils::$_variables_names->set($class_name, $v);
		/*RuntimeUtils::_variables_names.set(class_name, names.copy());*/
	}
	/**
	 * Returns Introspection of the class name
	 * @param string class_name
	 * @return Vector<IntrospectionInfo>
	 */
	static function getIntrospection($class_name){
		$res = new Vector();
		$class_names = (new \Runtime\Callback(self::class, "getParents"))($class_name);
		$class_names->prepend($class_name);
		$class_names->each(function ($item_class_name) use (&$res){
			$names = new Vector();
			/* Get fields introspection */
			try{
				rtl::method($item_class_name, "getFieldsList")($names);
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
			$names->each(function ($field_name) use (&$res, &$item_class_name){
				$info = null;
				try{
					$info = rtl::method($item_class_name, "getFieldInfoByName")($field_name);
				}catch(\Exception $_the_exception){
					if ($_the_exception instanceof \Exception){
						$e = $_the_exception;
						$info = null;
					}
					else { throw $_the_exception; }
				}
				if ($info != null){
					$info = $info->copy( new Map([ "class_name" => $item_class_name ])  );
					$res->push($info);
				}
			});
			/* Get virtual fields introspection */
			$names->clear();
			try{
				rtl::method($item_class_name, "getVirtualFieldsList")($names);
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
			$names->each(function ($field_name) use (&$res, &$item_class_name){
				$info = null;
				try{
					$info = rtl::method($item_class_name, "getVirtualFieldInfo")($field_name);
				}catch(\Exception $_the_exception){
					if ($_the_exception instanceof \Exception){
						$e = $_the_exception;
						$info = null;
					}
					else { throw $_the_exception; }
				}
				if ($info != null){
					$info = $info->copy( new Map([ "class_name" => $item_class_name ])  );
					$res->push($info);
				}
			});
			/* Get methods introspection */
			$names->clear();
			try{
				rtl::method($item_class_name, "getMethodsList")($names);
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
			$names->each(function ($method_name) use (&$res, &$item_class_name){
				$info = null;
				try{
					$info = rtl::method($item_class_name, "getMethodInfoByName")($method_name);
				}catch(\Exception $_the_exception){
					if ($_the_exception instanceof \Exception){
						$e = $_the_exception;
						$info = null;
					}
					else { throw $_the_exception; }
				}
				if ($info != null){
					$info = $info->copy( new Map([ "class_name" => $item_class_name ])  );
					$res->push($info);
				}
			});
			/* Get class introspection */
			$info = null;
			try{
				$info = rtl::method($item_class_name, "getClassInfo")();
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
					$info = null;
				}
				else { throw $_the_exception; }
			}
			if ($info != null){
				$info = $info->copy( new Map([ "class_name" => $item_class_name ])  );
				$res->push($info);
			}
		});
		return $res->toCollection();
	}
	/* ============================= Serialization Functions ============================= */
	static function ObjectToNative($value, $force_class_name = false){
		$value = (new \Runtime\Callback(self::class, "ObjectToPrimitive"))($value, $force_class_name);
		$value = (new \Runtime\Callback(self::class, "PrimitiveToNative"))($value);
		return $value;
	}
	static function NativeToObject($value){
		$value = (new \Runtime\Callback(self::class, "NativeToPrimitive"))($value);
		$value = (new \Runtime\Callback(self::class, "PrimitiveToObject"))($value);
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
		if ($obj instanceof Collection){
			return $obj->map(function ($value) use (&$force_class_name){
				return static::ObjectToPrimitive($value, $force_class_name);
			});
		}
		if ($obj instanceof Dict){
			$obj = $obj->map(function ($key, $value) use (&$force_class_name){
				return static::ObjectToPrimitive($value, $force_class_name);
			});
			if ($force_class_name){
				$obj = $obj->setIm("__class_name__", "Runtime.Dict");
			}
			return $obj->toDict();
		}
		if ($obj instanceof SerializeInterface){
			$names = new Vector();
			$values = new Map();
			$obj->getVariablesNames($names, 1);
			for ($i = 0; $i < $names->count(); $i++){
				$variable_name = $names->item($i);
				$value = $obj->takeValue($variable_name, null);
				$value = (new \Runtime\Callback(self::class, "ObjectToPrimitive"))($value, $force_class_name);
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
		if ($obj instanceof Collection){
			$res = new Vector();
			for ($i = 0; $i < $obj->count(); $i++){
				$value = $obj->item($i);
				$value = (new \Runtime\Callback(self::class, "PrimitiveToObject"))($value);
				$res->push($value);
			}
			return $res->toCollection();
		}
		if ($obj instanceof Dict){
			$res = new Map();
			$keys = $obj->keys();
			for ($i = 0; $i < $keys->count(); $i++){
				$key = $keys->item($i);
				$value = $obj->item($key);
				$value = (new \Runtime\Callback(self::class, "PrimitiveToObject"))($value);
				$res->set($key, $value);
			}
			if (!$res->has("__class_name__")){
				return $res;
			}
			if ($res->item("__class_name__") == "Runtime.Map" || $res->item("__class_name__") == "Runtime.Dict"){
				$res->remove("__class_name__");
				return $res->toDict();
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
			$instance->getVariablesNames($names, 1);
			for ($i = 0; $i < $names->count(); $i++){
				$variable_name = $names->item($i);
				if ($variable_name != "__class_name__"){
					$value = $res->get($variable_name, null);
					$instance->assignValue($variable_name, $value);
				}
			}
			if ($instance instanceof CoreStruct){
				$instance->initData();
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
	static function bytesToString($arr, $charset = "utf8"){
	}
	/**
	 * Convert string to bytes
	 * @param string s - incoming string
	 * @param Vector<byte> arr - output vector
	 * @param charset - Result bytes charset. Default utf8
	 */
	static function stringToBytes($s, $arr, $charset = "utf8"){
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
			$context = (new \Runtime\Callback(self::class, "getContext"))();
		}
		if ($context != null){
			$args = (new Vector())->push($message)->push($params)->push($locale);
			return rtl::callMethod($context, "translate", $args);
		}
		return $message;
	}
	/**
	 * Retuns css hash 
	 * @param string component class name
	 * @return string hash
	 */
	static function getCssHash($s){
		$arr = "1234567890abcdef";
		$arr_sz = 16;
		$arr_mod = 65536;
		$sz = rs::strlen($s);
		$hash = 0;
		for ($i = 0; $i < $sz; $i++){
			$ch = rs::ord(mb_substr($s, $i, 1));
			$hash = ($hash << 2) + ($hash >> 14) + $ch & 65535;
		}
		$res = "";
		$pos = 0;
		$c = 0;
		while ($hash != 0 || $pos < 4){
			$c = $hash & 15;
			$hash = $hash >> 4;
			$res .= mb_substr($arr, $c, 1);
			$pos++;
		}
		return $res;
	}
	/**
	 * Normalize UIStruct
	 */
	static function normalizeUIVector($data){
		if ($data instanceof Collection){
			$res = new Vector();
			for ($i = 0; $i < $data->count(); $i++){
				$item = $data->item($i);
				if ($item instanceof Collection){
					$new_item = static::normalizeUIVector($item);
					$res->appendVector($new_item);
				}
				else if ($item instanceof UIStruct){
					$res->push($item);
				}
				else if (rtl::isString($item)){
					$res->push(new UIStruct((new Map())->set("kind", UIStruct::TYPE_RAW)->set("content", rtl::toString($item))));
				}
			}
			return $res->toCollection();
		}
		else if ($data instanceof UIStruct){
			return new Collection(static::normalizeUI($data));
		}
		else if (rtl::isString($data)){
			return new Collection(static::normalizeUI($data));
		}
		return null;
	}
	/**
	 * Normalize UIStruct
	 */
	static function normalizeUI($data){
		if ($data instanceof UIStruct){
			$obj = (new Map())->set("children", static::normalizeUIVector($data->children));
			if ($data->props != null && $data->props instanceof Map){
				$obj->set("props", $data->props->toDict());
			}
			return $data->copy($obj);
		}
		else if (rtl::isString($data)){
			return new UIStruct((new Map())->set("kind", UIStruct::TYPE_RAW)->set("content", rtl::toString($data)));
		}
		return null;
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.RuntimeUtils";}
	public static function getCurrentClassName(){return "Runtime.RuntimeUtils";}
	public static function getParentClassName(){return "";}
	public static function getFieldsList($names, $flag=0){
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
	public static function getMethodsList($names){
	}
	public static function getMethodInfoByName($method_name){
		return null;
	}
}