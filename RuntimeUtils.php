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
class RuntimeUtils
{
	static $_global_context=null;
	static $_variables_names=null;
	const JSON_PRETTY=1;
	/**
	 * Returns global context
	 * @return Context
	 */
	static function getContext($__ctx)
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
	/* ========================== Class Introspection Functions ========================== */
	/**
	 * Returns parents class names
	 * @return Vector<string>
	 */
	static function getParents($__ctx, $class_name)
	{
		$res = new \Runtime\Vector($__ctx);
		$res->push($__ctx, $class_name);
		while ($class_name != "")
		{
			$f = \Runtime\rtl::method($__ctx, $class_name, "getParentClassName");
			$class_name = $f($__ctx);
			if ($class_name != "")
			{
				$res->push($__ctx, $class_name);
			}
		}
		return $res->toCollection($__ctx);
	}
	/**
	 * Returns Introspection of the class name
	 * @param string class_name
	 * @return Vector<IntrospectionInfo>
	 */
	static function getVariablesNames($__ctx, $class_name, $flag=2)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.RuntimeUtils.getVariablesNames", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		/* Get parents names */
		$class_names = \Runtime\RuntimeUtils::getParents($__ctx, $class_name);
		$names = $class_names->reduce($__ctx, function ($__ctx, $names, $item_class_name) use (&$flag)
		{
			$item_fields = null;
			$f = \Runtime\rtl::method($__ctx, $item_class_name, "getFieldsList");
			try
			{
				
				$item_fields = $f($__ctx, $flag);
			}
			catch (\Exception $_ex)
			{
				$e = $_ex;
			}
			if ($item_fields != null)
			{
				$names->appendVector($__ctx, $item_fields);
			}
			return $names;
		}, new \Runtime\Vector($__ctx));$__memorize_value = $names->toCollection($__ctx);
		\Runtime\rtl::_memorizeSave("Runtime.RuntimeUtils.getVariablesNames", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns Introspection of the class name
	 * @param string class_name
	 * @return Vector<IntrospectionInfo>
	 */
	static function getClassIntrospection($__ctx, $class_name)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.RuntimeUtils.getClassIntrospection", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$class_info = null;
		$fields = new \Runtime\Map($__ctx);
		$methods = new \Runtime\Map($__ctx);
		$info = null;
		/* Append annotations */
		$appendAnnotations = function ($__ctx, $arr, $name, $info)
		{
			if (!$arr->has($__ctx, $name))
			{
				$arr->set($__ctx, $name, new \Runtime\Vector($__ctx));
			}
			$v = $arr->item($__ctx, $name);
			$v->appendVector($__ctx, $info->annotations);
		};
		/* Get Class Info */
		try
		{
			
			$info = \Runtime\rtl::method($__ctx, $class_name, "getClassInfo")($__ctx);
			if ($info != null)
			{
				$class_info = $info->annotations;
			}
		}
		catch (\Exception $_ex)
		{
			$e = $_ex;
		}
		/* Get parents names */
		$class_names = \Runtime\RuntimeUtils::getParents($__ctx, $class_name);
		for ($i = 0;$i < $class_names->count($__ctx);$i++)
		{
			$item_class_name = $class_names->item($__ctx, $i);
			/* Get fields introspection */
			$item_fields = null;
			try
			{
				
				$item_fields = \Runtime\rtl::method($__ctx, $item_class_name, "getFieldsList")($__ctx, 3);
			}
			catch (\Exception $_ex)
			{
				$e = $_ex;
			}
			for ($j = 0;$j < $item_fields->count($__ctx);$j++)
			{
				$field_name = $item_fields->item($__ctx, $j);
				$info = \Runtime\rtl::method($__ctx, $item_class_name, "getFieldInfoByName")($__ctx, $field_name);
				$appendAnnotations($__ctx, $fields, $field_name, $info);
			}
			/* Get methods introspection */
			$item_methods = null;
			try
			{
				
				$item_methods = \Runtime\rtl::method($__ctx, $item_class_name, "getMethodsList")($__ctx);
			}
			catch (\Exception $_ex)
			{
				$e = $_ex;
			}
			for ($j = 0;$j < $item_methods->count($__ctx);$j++)
			{
				$method_name = $item_methods->item($__ctx, $j);
				$info = \Runtime\rtl::method($__ctx, $item_class_name, "getMethodInfoByName")($__ctx, $method_name);
				$appendAnnotations($__ctx, $methods, $method_name, $info);
			}
		}
		/* To Collection */
		$methods = $methods->map($__ctx, function ($__ctx, $item, $name)
		{
			return $item->toCollection($__ctx);
		});
		$fields = $fields->map($__ctx, function ($__ctx, $item, $name)
		{
			return $item->toCollection($__ctx);
		});$__memorize_value = new \Runtime\Annotations\IntrospectionClass($__ctx, \Runtime\Dict::from(["class_name"=>$class_name,"class_info"=>($class_info != null) ? $class_info->toCollection($__ctx) : null,"fields"=>$fields->toDict($__ctx),"methods"=>$methods->toDict($__ctx),"interfaces"=>\Runtime\rtl::getInterfaces($__ctx, $class_name)]));
		\Runtime\rtl::_memorizeSave("Runtime.RuntimeUtils.getClassIntrospection", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/* ============================= Serialization Functions ============================= */
	static function ObjectToNative($__ctx, $value, $force_class_name=false)
	{
		$value = \Runtime\RuntimeUtils::ObjectToPrimitive($__ctx, $value, $force_class_name);
		$value = \Runtime\RuntimeUtils::PrimitiveToNative($__ctx, $value);
		return $value;
	}
	static function NativeToObject($__ctx, $value)
	{
		$value = \Runtime\RuntimeUtils::NativeToPrimitive($__ctx, $value);
		$value = \Runtime\RuntimeUtils::PrimitiveToObject($__ctx, $value);
		return $value;
	}
	/**
	 * Returns object to primitive value
	 * @param var obj
	 * @return var
	 */
	static function ObjectToPrimitive($__ctx, $obj, $force_class_name=false)
	{
		if ($obj === null)
		{
			return null;
		}
		if (\Runtime\rtl::isScalarValue($__ctx, $obj))
		{
			return $obj;
		}
		if ($obj instanceof \Runtime\Collection)
		{
			return $obj->map($__ctx, function ($__ctx, $value) use (&$force_class_name)
			{
				return static::ObjectToPrimitive($__ctx, $value, $force_class_name);
			});
			/*
			Vector<var> res = new Vector();
			for (int i=0; i<obj.count(); i++)
			{
				var value = obj.item(i);
				value = self::ObjectToPrimitive( value, force_class_name );
				res.push(value);
			}
			return res.toCollection();
			*/
		}
		if ($obj instanceof \Runtime\Dict)
		{
			$obj = $obj->map($__ctx, function ($__ctx, $key, $value) use (&$force_class_name)
			{
				return static::ObjectToPrimitive($__ctx, $value, $force_class_name);
			});
			/*
			Map<var> res = new Map();
			Vector<string> keys = obj.keys();
			
			for (int i=0; i<keys.count(); i++)
			{
				string key = keys.item(i);
				var value = obj.item(key);
				value = self::ObjectToPrimitive( value, force_class_name );
				res.set(key, value);
			}
			
			delete keys;
			*/
			if ($force_class_name)
			{
				$obj = $obj->setIm($__ctx, "__class_name__", "Runtime.Dict");
			}
			return $obj->toDict($__ctx);
		}
		if ($obj instanceof \Runtime\Interfaces\SerializeInterface)
		{
			$values = new \Runtime\Map($__ctx);
			$names = static::getVariablesNames($__ctx, $obj->getClassName($__ctx), 1);
			for ($i = 0;$i < $names->count($__ctx);$i++)
			{
				$variable_name = $names->item($__ctx, $i);
				$value = $obj->takeValue($__ctx, $variable_name, null);
				$value = \Runtime\RuntimeUtils::ObjectToPrimitive($__ctx, $value, $force_class_name);
				$values->set($__ctx, $variable_name, $value);
			}
			$values->set($__ctx, "__class_name__", $obj->getClassName($__ctx));
			return $values->toDict($__ctx);
		}
		return null;
	}
	/**
	 * Returns object to primitive value
	 * @param SerializeContainer container
	 * @return var
	 */
	static function PrimitiveToObject($__ctx, $obj)
	{
		if ($obj === null)
		{
			return null;
		}
		if (\Runtime\rtl::isScalarValue($__ctx, $obj))
		{
			return $obj;
		}
		if ($obj instanceof \Runtime\Collection)
		{
			$res = new \Runtime\Vector($__ctx);
			for ($i = 0;$i < $obj->count($__ctx);$i++)
			{
				$value = $obj->item($__ctx, $i);
				$value = \Runtime\RuntimeUtils::PrimitiveToObject($__ctx, $value);
				$res->push($__ctx, $value);
			}
			return $res->toCollection($__ctx);
		}
		if ($obj instanceof \Runtime\Dict)
		{
			$res = new \Runtime\Map($__ctx);
			$keys = $obj->keys($__ctx);
			for ($i = 0;$i < $keys->count($__ctx);$i++)
			{
				$key = $keys->item($__ctx, $i);
				$value = $obj->item($__ctx, $key);
				$value = \Runtime\RuntimeUtils::PrimitiveToObject($__ctx, $value);
				$res->set($__ctx, $key, $value);
			}
			if (!$res->has($__ctx, "__class_name__"))
			{
				return $res;
			}
			if ($res->item($__ctx, "__class_name__") == "Runtime.Map" || $res->item($__ctx, "__class_name__") == "Runtime.Dict")
			{
				$res->remove($__ctx, "__class_name__");
				return $res->toDict($__ctx);
			}
			$class_name = $res->item($__ctx, "__class_name__");
			if (!\Runtime\rtl::class_exists($__ctx, $class_name))
			{
				return null;
			}
			if (!\Runtime\rtl::class_implements($__ctx, $class_name, "Runtime.Interfaces.SerializeInterface"))
			{
				return null;
			}
			/* New instance */
			$instance = \Runtime\rtl::newInstance($__ctx, $class_name, null);
			/* Assign values */
			$obj = new \Runtime\Map($__ctx);
			$names = static::getVariablesNames($__ctx, $class_name, 1);
			for ($i = 0;$i < $names->count($__ctx);$i++)
			{
				$variable_name = $names->item($__ctx, $i);
				if ($variable_name != "__class_name__")
				{
					$value = $res->get($__ctx, $variable_name, null);
					$obj->set($__ctx, $variable_name, $value);
					$instance->assignValue($__ctx, $variable_name, $value);
				}
			}
			if ($instance instanceof \Runtime\CoreStruct)
			{
				$instance->initData($__ctx, null, $obj);
			}
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
	static function json_encode($__ctx, $value, $flags=0, $convert=true)
	{
		if ($convert){
			$value = self::ObjectToPrimitive($__ctx, $value);
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
	static function json_decode($__ctx, $s)
	{
		$res = @json_decode($obj, false);
		if ($res === null || $res === false)
			return null;
		return self::NativeToObject($res);
	}
	/**
	 * Base64 encode
	 * @param string s
	 * @return string 
	 */
	static function base64_encode($__ctx, $s)
	{
		return base64_encode($s);
	}
	/**
	 * Base64 decode
	 * @param string s
	 * @return string 
	 */
	static function base64_decode($__ctx, $s)
	{
		return base64_decode($s);
	}
	/**
	 * Base64 encode
	 * @param string s
	 * @return string 
	 */
	static function base64_encode_url($__ctx, $s)
	{
		$s = base64_encode($s);
		$s = str_replace('+', '-', $s);
		$s = str_replace('/', '_', $s);
		$s = str_replace('=', '', $s);
		return $s;
	}
	/**
	 * Base64 decode
	 * @param string s
	 * @return string 
	 */
	static function base64_decode_url($__ctx, $s)
	{
		$c = 4 - strlen($s) % 4;
		if ($c < 4 && $c > 0) $s .= str_repeat('=', $c);
		$s = str_replace('-', '+', $s);
		$s = str_replace('_', '/', $s);
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
	static function randomString($__ctx, $length=16, $options="abc")
	{
		$s = "";
		if (\Runtime\rs::strpos($__ctx, $options, "a") >= 0)
		{
			$s .= \Runtime\rtl::toStr("abcdefghjkmnpqrstuvwxyz");
		}
		if (\Runtime\rs::strpos($__ctx, $options, "b") >= 0)
		{
			$s .= \Runtime\rtl::toStr("ABCDEFGHJKMNPQRSTUVWXYZ");
		}
		if (\Runtime\rs::strpos($__ctx, $options, "c") >= 0)
		{
			$s .= \Runtime\rtl::toStr("1234567890");
		}
		if (\Runtime\rs::strpos($__ctx, $options, "d") >= 0)
		{
			$s .= \Runtime\rtl::toStr("!@#$%^&?*_-+=~(){}[]<>|/,.:;\\");
		}
		if (\Runtime\rs::strpos($__ctx, $options, "e") >= 0)
		{
			$s .= \Runtime\rtl::toStr("`\"'");
		}
		$res = "";
		$c = \Runtime\rs::strlen($__ctx, $s);
		for ($i = 0;$i < $length;$i++)
		{
			$k = \Runtime\rtl::random($__ctx, 0, $c - 1);
			$res .= \Runtime\rtl::toStr($s[$k]);
		}
		return $res;
	}
	/**
	 * Returns true if value is primitive value
	 * @return boolean 
	 */
	static function isPrimitiveValue($__ctx, $value)
	{
		if (\Runtime\rtl::isScalarValue($__ctx, $value))
		{
			return true;
		}
		if ($value instanceof \Runtime\Vector)
		{
			return true;
		}
		if ($value instanceof \Runtime\Map)
		{
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
	static function bytesToString($__ctx, $arr, $charset="utf8")
	{
		$arr = array_map( function($byte){ return chr($byte); }, $arr->_getArr() );
		$s = implode("", $arr);
		return $s;
	}
	/**
	 * Convert string to bytes
	 * @param string s - incoming string
	 * @param charset - Result bytes charset. Default utf8
	 * @return Collection<byte> output collection
	 */
	static function toString($__ctx, $arr, $charset="utf8")
	{
		return static::bytesToString($__ctx, $arr, $charset);
	}
	/**
	 * Convert string to bytes
	 * @param string s - incoming string
	 * @param Vector<byte> arr - output vector
	 * @param charset - Result bytes charset. Default utf8
	 */
	static function stringToBytes($__ctx, $s, $arr, $charset="utf8")
	{
	}
	/**
	 * Convert string to bytes
	 * @param string s - incoming string
	 * @param charset - Result bytes charset. Default utf8
	 * @return Collection<byte> output collection
	 */
	static function toBytes($__ctx, $s, $charset="utf8")
	{
		return static::stringToBytes($__ctx, $s, $charset);
	}
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params Dict params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	static function translate($__ctx, $message, $params=null, $locale="", $context=null)
	{
		if ($context == null)
		{
			$context = \Runtime\RuntimeUtils::getContext($__ctx);
		}
		if ($context != null)
		{
			$context->translate($__ctx, $message, $params, $locale);
		}
		return $message;
	}
	/**
	 * Retuns css hash 
	 * @param string component class name
	 * @return string hash
	 */
	static function getCssHash($__ctx, $s)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.RuntimeUtils.getCssHash", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$r = "";
		$a = "1234567890abcdef";
		$sz = \Runtime\rs::strlen($__ctx, $s);
		$h = 0;
		for ($i = 0;$i < $sz;$i++)
		{
			$c = \Runtime\rs::ord($__ctx, \Runtime\rs::substr($__ctx, $s, $i, 1));
			$h = ($h << 2) + ($h >> 14) + $c & 65535;
		}
		$p = 0;
		while ($h != 0 || $p < 4)
		{
			$c = $h & 15;
			$h = $h >> 4;
			$r .= \Runtime\rtl::toStr(\Runtime\rs::substr($__ctx, $a, $c, 1));
			$p = $p + 1;
		}$__memorize_value = $r;
		\Runtime\rtl::_memorizeSave("Runtime.RuntimeUtils.getCssHash", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Normalize UIStruct
	 */
	static function normalizeUIVector($__ctx, $data)
	{
		if ($data instanceof \Runtime\Collection)
		{
			$res = new \Runtime\Vector($__ctx);
			for ($i = 0;$i < $data->count($__ctx);$i++)
			{
				$item = $data->item($__ctx, $i);
				if ($item instanceof \Runtime\Collection)
				{
					$new_item = static::normalizeUIVector($__ctx, $item);
					$res->appendVector($__ctx, $new_item);
				}
				else if ($item instanceof \Runtime\UIStruct)
				{
					$res->push($__ctx, $item);
				}
				else if (\Runtime\rtl::isString($__ctx, $item))
				{
					$res->push($__ctx, new \Runtime\UIStruct($__ctx, \Runtime\Dict::from(["kind"=>\Runtime\UIStruct::TYPE_RAW,"content"=>\Runtime\rtl::toString($__ctx, $item)])));
				}
			}
			return $res->toCollection($__ctx);
		}
		else if ($data instanceof \Runtime\UIStruct)
		{
			return new \Runtime\Collection($__ctx, static::normalizeUI($__ctx, $data));
		}
		else if (\Runtime\rtl::isString($__ctx, $data))
		{
			return new \Runtime\Collection($__ctx, static::normalizeUI($__ctx, $data));
		}
		return null;
	}
	/**
	 * Normalize UIStruct
	 */
	static function normalizeUI($__ctx, $data)
	{
		if ($data instanceof \Runtime\UIStruct)
		{
			$obj = \Runtime\Dict::from(["children"=>static::normalizeUIVector($__ctx, $data->children)]);
			if ($data->props != null && $data->props instanceof \Runtime\Map)
			{
				$obj->set($__ctx, "props", $data->props->toDict($__ctx));
			}
			return $data->copy($__ctx, $obj);
		}
		else if (\Runtime\rtl::isString($__ctx, $data))
		{
			return new \Runtime\UIStruct($__ctx, \Runtime\Dict::from(["kind"=>\Runtime\UIStruct::TYPE_RAW,"content"=>\Runtime\rtl::toString($__ctx, $data)]));
		}
		return null;
	}
	/* Lambda Functions */
	static function isInstance($__ctx, $class_name)
	{
		return function ($__ctx, $item) use (&$class_name)
		{
			return \Runtime\rtl::is_instance($__ctx, $item, $class_name);
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
			return $item1->takeValue($__ctx, $key, $def_value);
		};
	}
	/**
	 * Equal two struct by key
	 */
	static function equalItemKey($__ctx, $key)
	{
		return function ($__ctx, $item1, $value) use (&$key)
		{
			return $item1->takeValue($__ctx, $key) == $value;
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
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.RuntimeUtils",
			"name"=>"Runtime.RuntimeUtils",
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
}