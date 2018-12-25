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
use Runtime\CoreStruct;
use Runtime\Map;
use Runtime\rtl;
use Runtime\Vector;
class UIStruct extends CoreStruct{
	const TYPE_ELEMENT = "element";
	const TYPE_COMPONENT = "component";
	const TYPE_STRING = "string";
	const TYPE_RAW = "raw";
	protected $__id;
	protected $__key;
	protected $__name;
	protected $__kind;
	protected $__content;
	protected $__props;
	protected $__children;
	/**
	 * Returns true if component
	 * @return bool
	 */
	function isComponent(){
		return $this->kind == static::TYPE_COMPONENT;
	}
	/**
	 * Returns true if string
	 * @return bool
	 */
	function isString(){
		return $this->kind == static::TYPE_STRING || $this->kind == static::TYPE_RAW;
	}
	/**
	 * Returns true if component and name == class_name
	 * @param string class_name
	 * @return bool
	 */
	function instanceOf($class_name){
		if ($this->is_component && $this->name == $class_name){
			return true;
		}
		return false;
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.UIStruct";}
	public static function getParentClassName(){return "Runtime.CoreStruct";}
	protected function _init(){
		parent::_init();
		$this->__id = "";
		$this->__key = "";
		$this->__name = "";
		$this->__kind = "element";
		$this->__content = "";
		$this->__props = null;
		$this->__children = null;
	}
	public function assignObject($obj){
		if ($obj instanceof UIStruct){
			$this->__id = rtl::_clone($obj->__id);
			$this->__key = rtl::_clone($obj->__key);
			$this->__name = rtl::_clone($obj->__name);
			$this->__kind = rtl::_clone($obj->__kind);
			$this->__content = rtl::_clone($obj->__content);
			$this->__props = rtl::_clone($obj->__props);
			$this->__children = rtl::_clone($obj->__children);
		}
		parent::assignObject($obj);
	}
	public function assignValue($variable_name, $value){
		if ($variable_name == "id") $this->__id = rtl::correct($value, "string", "", "");
		else if ($variable_name == "key") $this->__key = rtl::correct($value, "string", "", "");
		else if ($variable_name == "name") $this->__name = rtl::correct($value, "string", "", "");
		else if ($variable_name == "kind") $this->__kind = rtl::correct($value, "string", "element", "");
		else if ($variable_name == "content") $this->__content = rtl::correct($value, "string", "", "");
		else if ($variable_name == "props") $this->__props = rtl::correct($value, "Runtime.Map", null, "mixed");
		else if ($variable_name == "children") $this->__children = rtl::correct($value, "Runtime.Vector", null, "mixed");
		else parent::assignValue($variable_name, $value);
	}
	public function takeValue($variable_name, $default_value = null){
		if ($variable_name == "id") return $this->__id;
		else if ($variable_name == "key") return $this->__key;
		else if ($variable_name == "name") return $this->__name;
		else if ($variable_name == "kind") return $this->__kind;
		else if ($variable_name == "content") return $this->__content;
		else if ($variable_name == "props") return $this->__props;
		else if ($variable_name == "children") return $this->__children;
		return parent::takeValue($variable_name, $default_value);
	}
	public static function getFieldsList($names){
		$names->push("id");
		$names->push("key");
		$names->push("name");
		$names->push("kind");
		$names->push("content");
		$names->push("props");
		$names->push("children");
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
	public function __get($key){ return $this->takeValue($key); }
	public function __set($key, $value){}
}