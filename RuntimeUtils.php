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
	static $_global_context=null;
	static $_variables_names=null;
	const JSON_PRETTY=1;
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
	/* ========================== Class Introspection Functions ========================== */
	/**
	 * Returns parents class names
	 * @return Vector<string>
	 */
	static function getParents($ctx, $class_name)
	{
		$res = new \Runtime\Vector($ctx);
		$res->push($ctx, $class_name);
		while ($class_name != "")
		{
			$f = \Runtime\rtl::method($ctx, $class_name, "getParentClassName");
			$class_name = $f($ctx);
			if ($class_name != "")
			{
				$res->push($ctx, $class_name);
			}
		}
		return $res->toCollection($ctx);
	}
	/**
	 * Returns Introspection of the class name
	 * @param string class_name
	 * @return Vector<IntrospectionInfo>
	 */
	static function getVariablesNames($ctx, $class_name, $flag=2)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.RuntimeUtils.getVariablesNames", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		/* Get parents names */
		$class_names = \Runtime\RuntimeUtils::getParents($ctx, $class_name);
		$names = $class_names->reduce($ctx, function ($ctx, $names, $item_class_name) use (&$flag)
		{
			$item_fields = null;
			$f = \Runtime\rtl::method($ctx, $item_class_name, "getFieldsList");
			try
			{
				
				$item_fields = $f($ctx, $flag);
			}
			catch (\Exception $_ex)
			{
				$e = $_ex;
				throw $_ex;
			}
			if ($item_fields != null)
			{
				$names->appendVector($ctx, $item_fields);
			}
			return $names;
		}, new \Runtime\Vector($ctx));
		$__memorize_value = $names->toCollection($ctx);
		\Runtime\rtl::_memorizeSave("Runtime.RuntimeUtils.getVariablesNames", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/**
	 * Returns Introspection of the class name
	 * @param string class_name
	 * @return Vector<IntrospectionInfo>
	 */
	static function getClassIntrospection($ctx, $class_name)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.RuntimeUtils.getClassIntrospection", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$class_info = null;
		$fields = new \Runtime\Map($ctx);
		$methods = new \Runtime\Map($ctx);
		$info = null;
		if (!\Runtime\rtl::class_exists($ctx, $class_name))
		{
			$__memorize_value = null;
			\Runtime\rtl::_memorizeSave("Runtime.RuntimeUtils.getClassIntrospection", func_get_args(), $__memorize_value);
			return $__memorize_value;
		}
		/* Append annotations */
		$appendAnnotations = function ($ctx, $arr, $name, $info)
		{
			if ($info == null)
			{
				return ;
			}
			if (!$arr->has($ctx, $name))
			{
				$arr->set($ctx, $name, new \Runtime\Vector($ctx));
			}
			$v = $arr->item($ctx, $name);
			$v->appendVector($ctx, $info->annotations);
		};
		/* Get Class Info */
		try
		{
			
			$info = \Runtime\rtl::method($ctx, $class_name, "getClassInfo")($ctx);
			if ($info != null)
			{
				$class_info = $info->annotations;
			}
		}
		catch (\Exception $_ex)
		{
			$e = $_ex;
			throw $_ex;
		}
		/* Get parents names */
		$class_names = \Runtime\RuntimeUtils::getParents($ctx, $class_name);
		for ($i = 0;$i < $class_names->count($ctx);$i++)
		{
			$item_class_name = $class_names->item($ctx, $i);
			/* Get fields introspection */
			$item_fields = null;
			try
			{
				
				$item_fields = \Runtime\rtl::method($ctx, $item_class_name, "getFieldsList")($ctx, 3);
			}
			catch (\Exception $_ex)
			{
				$e = $_ex;
				throw $_ex;
			}
			for ($j = 0;$j < $item_fields->count($ctx);$j++)
			{
				$field_name = $item_fields->item($ctx, $j);
				$info = \Runtime\rtl::method($ctx, $item_class_name, "getFieldInfoByName")($ctx, $field_name);
				$appendAnnotations($ctx, $fields, $field_name, $info);
			}
			/* Get methods introspection */
			$item_methods = null;
			try
			{
				
				$item_methods = \Runtime\rtl::method($ctx, $item_class_name, "getMethodsList")($ctx);
			}
			catch (\Exception $_ex)
			{
				$e = $_ex;
				throw $_ex;
			}
			for ($j = 0;$j < $item_methods->count($ctx);$j++)
			{
				$method_name = $item_methods->item($ctx, $j);
				$info = \Runtime\rtl::method($ctx, $item_class_name, "getMethodInfoByName")($ctx, $method_name);
				$appendAnnotations($ctx, $methods, $method_name, $info);
			}
		}
		/* To Collection */
		$methods = $methods->map($ctx, function ($ctx, $item, $name)
		{
			return $item->toCollection($ctx);
		});
		$fields = $fields->map($ctx, function ($ctx, $item, $name)
		{
			return $item->toCollection($ctx);
		});
		$__memorize_value = new \Runtime\Annotations\IntrospectionClass($ctx, \Runtime\Dict::from(["class_name"=>$class_name,"class_info"=>($class_info != null) ? $class_info->toCollection($ctx) : null,"fields"=>$fields->toDict($ctx),"methods"=>$methods->toDict($ctx),"interfaces"=>\Runtime\rtl::getInterfaces($ctx, $class_name)]));
		\Runtime\rtl::_memorizeSave("Runtime.RuntimeUtils.getClassIntrospection", func_get_args(), $__memorize_value);
		return $__memorize_value;
	}
	/* ============================= Serialization Functions ============================= */
	static function ObjectToNative($ctx, $value, $force_class_name=false)
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
	static function ObjectToPrimitive($ctx, $obj, $force_class_name=false)
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
			$obj = $obj->map($ctx, function ($ctx, $value, $key) use (&$force_class_name)
			{
				return static::ObjectToPrimitive($ctx, $value, $force_class_name);
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
				$obj = $obj->setIm($ctx, "__class_name__", "Runtime.Dict");
			}
			return $obj->toDict($ctx);
		}
		if ($obj instanceof \Runtime\Interfaces\SerializeInterface)
		{
			$values = new \Runtime\Map($ctx);
			$names = static::getVariablesNames($ctx, $obj->getClassName($ctx), 1);
			for ($i = 0;$i < $names->count($ctx);$i++)
			{
				$variable_name = $names->item($ctx, $i);
				$value = $obj->takeValue($ctx, $variable_name, null);
				$value = \Runtime\RuntimeUtils::ObjectToPrimitive($ctx, $value, $force_class_name);
				$values->set($ctx, $variable_name, $value);
			}
			$values->set($ctx, "__class_name__", $obj->getClassName($ctx));
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
				$res->push($ctx, $value);
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
				$res->set($ctx, $key, $value);
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
			if (!\Runtime\rtl::class_implements($ctx, $class_name, "Runtime.Interfaces.SerializeInterface"))
			{
				return null;
			}
			/* Assign values */
			$obj = new \Runtime\Map($ctx);
			$names = static::getVariablesNames($ctx, $class_name, 1);
			for ($i = 0;$i < $names->count($ctx);$i++)
			{
				$variable_name = $names->item($ctx, $i);
				if ($variable_name != "__class_name__")
				{
					$value = $res->get($ctx, $variable_name, null);
					$obj->set($ctx, $variable_name, $value);
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
			$res = \Runtime\Dict::from($value);
			$res = $res->map($ctx, function ($ctx, $val, $key){
				return self::NativeToPrimitive($ctx, $val);
			});
			return $res;
		}
		
		if (is_array($value))
		{
			if ( isset($value['__class_name__']) ){
				$res = \Runtime\Dict::from($value);
				$res = $res->map($ctx, function ($ctx, $val, $key){
					return self::NativeToPrimitive($ctx, $val);
				});
				return $res;
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
			$value = self::ObjectToPrimitive($ctx, $value);
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
	/**
	 * Base64 encode
	 * @param string s
	 * @return string 
	 */
	static function base64_encode($ctx, $s)
	{
		return base64_encode($s);
	}
	/**
	 * Base64 decode
	 * @param string s
	 * @return string 
	 */
	static function base64_decode($ctx, $s)
	{
		return base64_decode($s);
	}
	/**
	 * Base64 encode
	 * @param string s
	 * @return string 
	 */
	static function base64_encode_url($ctx, $s)
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
	static function base64_decode_url($ctx, $s)
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
	static function randomString($ctx, $length=16, $options="abc")
	{
		$s = "";
		if (\Runtime\rs::strpos($ctx, $options, "a") >= 0)
		{
			$s .= \Runtime\rtl::toStr("abcdefghjkmnpqrstuvwxyz");
		}
		if (\Runtime\rs::strpos($ctx, $options, "b") >= 0)
		{
			$s .= \Runtime\rtl::toStr("ABCDEFGHJKMNPQRSTUVWXYZ");
		}
		if (\Runtime\rs::strpos($ctx, $options, "c") >= 0)
		{
			$s .= \Runtime\rtl::toStr("1234567890");
		}
		if (\Runtime\rs::strpos($ctx, $options, "d") >= 0)
		{
			$s .= \Runtime\rtl::toStr("!@#$%^&?*_-+=~(){}[]<>|/,.:;\\");
		}
		if (\Runtime\rs::strpos($ctx, $options, "e") >= 0)
		{
			$s .= \Runtime\rtl::toStr("`\"'");
		}
		$res = "";
		$c = \Runtime\rs::strlen($ctx, $s);
		for ($i = 0;$i < $length;$i++)
		{
			$k = \Runtime\rtl::random($ctx, 0, $c - 1);
			$res .= \Runtime\rtl::toStr($s[$k]);
		}
		return $res;
	}
	/**
	 * Returns true if value is primitive value
	 * @return boolean 
	 */
	static function isPrimitiveValue($ctx, $value)
	{
		if (\Runtime\rtl::isScalarValue($ctx, $value))
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
	static function bytesToString($ctx, $arr, $charset="utf8")
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
	static function toString($ctx, $arr, $charset="utf8")
	{
		return static::bytesToString($ctx, $arr, $charset);
	}
	/**
	 * Convert string to bytes
	 * @param string s - incoming string
	 * @param Vector<byte> arr - output vector
	 * @param charset - Result bytes charset. Default utf8
	 */
	static function stringToBytes($ctx, $s, $arr, $charset="utf8")
	{
	}
	/**
	 * Convert string to bytes
	 * @param string s - incoming string
	 * @param charset - Result bytes charset. Default utf8
	 * @return Collection<byte> output collection
	 */
	static function toBytes($ctx, $s, $charset="utf8")
	{
		return static::stringToBytes($ctx, $s, $charset);
	}
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params Dict params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	static function translate($ctx, $message, $params=null, $locale="", $context=null)
	{
		if ($context == null)
		{
			$context = \Runtime\RuntimeUtils::getContext($ctx);
		}
		if ($context != null)
		{
			$context->translate($ctx, $message, $params, $locale);
		}
		return $message;
	}
	/**
	 * Retuns css hash 
	 * @param string component class name
	 * @return string hash
	 */
	static function getCssHash($ctx, $s)
	{
		$__memorize_value = \Runtime\rtl::_memorizeValue("Runtime.RuntimeUtils.getCssHash", func_get_args());
		if ($__memorize_value != \Runtime\rtl::$_memorize_not_found) return $__memorize_value;
		$r = "";
		$a = "1234567890abcdef";
		$sz = \Runtime\rs::strlen($ctx, $s);
		$h = 0;
		for ($i = 0;$i < $sz;$i++)
		{
			$c = \Runtime\rs::ord($ctx, \Runtime\rs::substr($ctx, $s, $i, 1));
			$h = ($h << 2) + ($h >> 14) + $c & 65535;
		}
		$p = 0;
		while ($h != 0 || $p < 4)
		{
			$c = $h & 15;
			$h = $h >> 4;
			$r .= \Runtime\rtl::toStr(\Runtime\rs::substr($ctx, $a, $c, 1));
			$p = $p + 1;
		}
		$__memorize_value = $r;
		\Runtime\rtl::_memorizeSave("Runtime.RuntimeUtils.getCssHash", func_get_args(), $__memorize_value);
		return $__memorize_value;
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
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.RuntimeUtils",
			"name"=>"Runtime.RuntimeUtils",
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
		if ($field_name == "_global_context") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.RuntimeUtils",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "_variables_names") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.RuntimeUtils",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "JSON_PRETTY") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.RuntimeUtils",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		return null;
	}
	static function getMethodsList($ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}