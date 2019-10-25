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
class CoreObject
{
	function __construct($__ctx)
	{
		$this->_init($__ctx);
	}
	/**
	 * Init function
	 */
	function _init($__ctx)
	{
	}
	/**
	 * Returns instance of the value by variable name
	 * @param string variable_name
	 * @param string default_value
	 * @return var
	 */
	function takeValue($__ctx, $variable_name, $default_value=null)
	{
		return $this->takeVirtualValue($__ctx, $variable_name, $default_value);
	}
	/**
	 * Returns virtual values
	 * @param string variable_name
	 * @param string default_value
	 * @return var
	 */
	function takeVirtualValue($__ctx, $variable_name, $default_value=null)
	{
		return $default_value;
	}
	/**
	 * Set new value
	 * @param string variable_name
	 * @param var value
	 */
	function assignValue($__ctx, $variable_name, $value)
	{
		$this->assignVirtualValue($__ctx, $variable_name, $value);
	}
	/**
	 * Assign virtual value
	 * @param string variable_name
	 * @param var value
	 */
	function assignVirtualValue($__ctx, $variable_name, $value)
	{
	}
	/**
	 * Assign and clone data from other object
	 * @param CoreObject obj
	 */
	function assignObject($__ctx, $obj)
	{
	}
	/**
	 * Set new values instance by Map
	 * @param Map<var> map
	 * @return CoreObject
	 */
	function assignDict($__ctx, $values=null)
	{
		if ($values == null)
		{
			return null;
		}
		$f = \Runtime\rtl::method("Runtime.RuntimeUtils", "getVariablesNames");
		$names = $f($__ctx, $this->getClassName($__ctx), 2);
		for ($i = 0;$i < $names->count($__ctx);$i++)
		{
			$name = $names->item($__ctx, $i);
			$this->assignValue($__ctx, $name, $values->get($__ctx, $name, null));
		}
		return $this;
	}
	/**
	 * Set new values instance by Map
	 * @param Dict<var> map
	 * @return CoreObject
	 */
	function setDict($__ctx, $values=null)
	{
		if ($values == null)
		{
			return null;
		}
		$values->each($__ctx, \Runtime\rtl::method($this, "assignValue"));
		return $this;
	}
	/**
	 * Dump serializable object to Map
	 * @return Map<var>
	 */
	function takeDict($__ctx, $fields=null, $flag=2)
	{
		$values = new \Runtime\Map($__ctx);
		if ($fields == null)
		{
			$f = \Runtime\rtl::method("Runtime.RuntimeUtils", "getVariablesNames");
			$names = $f($__ctx, $this->getClassName($__ctx), $flag);
			for ($i = 0;$i < $names->count($__ctx);$i++)
			{
				$name = $names->item($__ctx, $i);
				$values->set($__ctx, $name, $this->takeValue($__ctx, $name, null));
			}
		}
		else
		{
			for ($i = 0;$i < $fields->count($__ctx);$i++)
			{
				$name = $fields->item($__ctx, $i);
				$values->set($__ctx, $name, $this->takeValue($__ctx, $name, null));
			}
		}
		return $values->toDict($__ctx);
	}
	function staticMethod($method_name)
	{
		return \Runtime\rtl::method(null, $this->getClassName(), $method_name);
	}
	function callStatic($__ctx, $method_name)
	{
		$args = func_get_args();
		$class_name = static::class;
		$method_name = array_shift($args);
		return call_user_func_array([$class_name, $method_name], $args);
		return null;
	}
	function callStaticParent($__ctx, $method_name)
	{
		$args = func_get_args();
		$class_name = static::class; 
		$class_name = $class_name::getParentClassName();
		$method_name = array_shift($args);
		return call_user_func_array([$class_name, $method_name], $args);
		return null;
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.CoreObject";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.CoreObject";
	}
	static function getParentClassName()
	{
		return "";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.CoreObject",
			"name"=>"Runtime.CoreObject",
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