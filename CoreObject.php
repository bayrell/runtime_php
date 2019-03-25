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
use Runtime\rtl;
use Runtime\Map;
use Runtime\Vector;
class CoreObject{
	protected $_is_destroyed;
	/** 
	 * Constructor
	 */
	function __construct(){
		
		$this->_init();
	}
	
	protected function _init(){}
	
	function __destruct() {
		/*this._is_destroyed = true;*/
	}
	/**
	 * Returns instance of the value by variable name
	 * @param string variable_name
	 * @param string default_value
	 * @return var
	 */
	function takeValue($variable_name, $default_value = null){
		return $this->takeVirtualValue($variable_name, $default_value);
	}
	/**
	 * Returns virtual values
	 * @param string variable_name
	 * @param string default_value
	 * @return var
	 */
	function takeVirtualValue($variable_name, $default_value = null){
		return $default_value;
	}
	/**
	 * Assign and clone data from other object
	 * @param CoreObject obj
	 */
	function assignObject($obj){
	}
	/**
	 * Set new value instance by variable name
	 * @param string variable_name
	 * @param var value
	 */
	function assignValue($variable_name, $value){
	}
	/**
	 * Set new values instance by Map
	 * @param Map<mixed> map
	 * @return CoreObject
	 */
	function assignMap($values = null){
		if ($values == null){
			return ;
		}
		$names = new Vector();
		$this->getVariablesNames($names, 2);
		$names->each(function ($name) use (&$values){
			$this->assignValue($name, $values->get($name, null));
		});
		return $this;
	}
	/**
	 * Set new values instance by Map
	 * @param Map<mixed> map
	 * @return CoreObject
	 */
	function setMap($values = null){
		if ($values == null){
			return ;
		}
		$values->each(function ($key, $value){
			$this->assignValue($key, $value);
		});
		return $this;
	}
	/**
	 * Dump serializable object to Map
	 * @return Map<mixed>
	 */
	function takeMap($flag = 2){
		$values = new Map();
		$names = new Vector();
		$this->getVariablesNames($names, $flag);
		$names->each(function ($name) use (&$values){
			$values->set($name, $this->takeValue($name, null));
		});
		return $values;
	}
	/**
	 * Call static method of the current class
	 * @param string method_name
	 * @param Vector args
	 * @return mixed
	 */
	function callStaticMethod($method_name, $args = null){
		return rtl::callStaticMethod($this->getClassName(), $method_name, $args);
	}
	/**
	 * Returns field info by field_name
	 * @param string field_name
	 * @return IntrospectionInfo
	 */
	static function getFieldInfoByName($field_name){
	}
	/**
	 * Returns virtual field info by field_name
	 * @param string field_name
	 * @return IntrospectionInfo
	 */
	static function getVirtualFieldInfo($field_name){
		return null;
	}
	/**
	 * Returns public fields list
	 * @param Vector<string> names
	 */
	static function getFieldsList($names, $flag = 0){
	}
	/**
	 * Returns public virtual fields names
	 * @param Vector<string> names
	 */
	static function getVirtualFieldsList($names, $flag = 0){
	}
	/**
	 * Returns info of the public method by name
	 * @param string method_name
	 * @return IntrospectionInfo
	 */
	static function getMethodInfoByName($method_name){
		return null;
	}
	/**
	 * Returns list of the public methods
	 * @param Vector<string> methods
	 */
	static function getMethodsList($methods){
	}
	/**
	 * Returns names of variables to serialization
	 * @param Vector<string>
	 */
	function getVariablesNames($names, $flag = 0){
		rtl::callStaticMethod("Runtime.RuntimeUtils", "getVariablesNames", (new Vector())->push($this->getClassName())->push($names)->push($flag));
	}
	/**
	 * Returns info of the public variable by name
	 * @param string variable_name
	 * @return IntrospectionInfo
	 */
	function getFieldInfo($variable_name){
		$classes = rtl::callStaticMethod("Runtime.RuntimeUtils", "getParents", (new Vector())->push($this->getClassName()));
		for ($i = 0; $i < $classes->count(); $i++){
			$class_name = $classes->item($i);
			$info = rtl::callStaticMethod($class_name, "getFieldInfoByName", (new Vector())->push($variable_name));
			if ($info != null && $item->kind == $IntrospectionInfo::ITEM_FIELD){
				return $info;
			}
			try{
				$info = rtl::callStaticMethod($class_name, "getVirtualFieldInfo", (new Vector())->push($variable_name));
				if ($info != null && $item->kind == $IntrospectionInfo::ITEM_FIELD){
					return $info;
				}
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
		}
		return null;
	}
	/**
	 * Returns names of methods
	 * @param Vector<string>
	 */
	function getMethodsNames($names){
		$classes = rtl::callStaticMethod("Runtime.RuntimeUtils", "getParents", (new Vector())->push($this->getClassName()));
		for ($i = 0; $i < $classes->count(); $i++){
			$class_name = $classes->item($i);
			rtl::callStaticMethod($class_name, "getMethodsList", (new Vector())->push($names));
		}
	}
	/**
	 * Returns info of the public method by name
	 * @param string method_name
	 * @return IntrospectionInfo
	 */
	function getMethodInfo($method_name){
		$classes = rtl::callStaticMethod("Runtime.RuntimeUtils", "getParents", (new Vector())->push($this->getClassName()));
		for ($i = 0; $i < $classes->count(); $i++){
			$class_name = $classes->item($i);
			$info = rtl::callStaticMethod($class_name, "getMethodInfoByName", (new Vector())->push($method_name));
			if ($info != null && $item->kind == $IntrospectionInfo::ITEM_METHOD){
				return $info;
			}
		}
		return null;
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.CoreObject";}
	public static function getCurrentClassName(){return "Runtime.CoreObject";}
	public static function getParentClassName(){return "";}
}