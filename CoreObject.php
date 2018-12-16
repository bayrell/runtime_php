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
use Runtime\RuntimeUtils;
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
		$this->assignObjectAfter($obj);
	}
	/**
	 * Assign and clone data from other object
	 * @param CoreObject obj
	 */
	function assignObjectAfter($obj){
	}
	/**
	 * Set new value instance by variable name
	 * @param string variable_name
	 * @param var value
	 */
	function assignValue($variable_name, $value){
		$this->assignValueAfter($variable_name, $value);
	}
	/**
	 * Calls after assign new value
	 * @param string variable_name
	 * @param var value
	 */
	function assignValueAfter($variable_name, $value){
	}
	/**
	 * Calls after assign new value
	 * @param string variable_name
	 */
	function callAssignAfter($variable_name){
		$this->assignValueAfter($variable_name, $this->takeValue($variable_name));
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
		$this->getVariablesNames($names);
		$names->each(function ($name) use (&$values){
			$value = $values->get($name, null);
			$this->assignValue($name, $value);
		});
		return $this;
	}
	/**
	 * Dump serializable object to Map
	 * @return Map<mixed>
	 */
	function takeMap(){
		$values = new Map();
		$names = new Vector();
		$this->getVariablesNames($names);
		$names->each(function ($name) use (&$values){
			$value = $this->takeValue($name, null);
			$values->set($name, $value);
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
		$class_name = $this->getClassName();
		return rtl::callStaticMethod($class_name, $method_name, $args);
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
	static function getFieldsList($names){
	}
	/**
	 * Returns public virtual fields names
	 * @param Vector<string> names
	 */
	static function getVirtualFieldsList($names){
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
	function getVariablesNames($names){
		$classes = RuntimeUtils::getParents($this->getClassName());
		$classes->prepend($this->getClassName());
		$classes->removeDublicates();
		for ($i = 0; $i < $classes->count(); $i++){
			$class_name = $classes->item($i);
			rtl::callStaticMethod($class_name, "getFieldsList", (new Vector())->push($names));
			/*try{ rtl::callStaticMethod(class_name, "getFieldsList", [names]); } catch (var e) {}*/
			try{
				rtl::callStaticMethod($class_name, "getVirtualFieldsList", (new Vector())->push($names));
			}catch(\Exception $_the_exception){
				if ($_the_exception instanceof \Exception){
					$e = $_the_exception;
				}
				else { throw $_the_exception; }
			}
		}
		$names->removeDublicates();
	}
	/**
	 * Returns names of variables to serialization
	 * @param Vector<string>
	 */
	function getFieldsNames($names){
		$this->getVariablesNames($names);
	}
	/**
	 * Returns info of the public variable by name
	 * @param string variable_name
	 * @return IntrospectionInfo
	 */
	function getFieldInfo($variable_name){
		$classes = RuntimeUtils::getParents($this->getClassName());
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
		$classes = RuntimeUtils::getParents($this->getClassName());
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
		$classes = RuntimeUtils::getParents($this->getClassName());
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
	public static function getParentClassName(){return "";}
}