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
use Runtime\rtl;
use Runtime\Vector;
class IntrospectionInfo extends CoreStruct{
	const ITEM_CLASS = "class";
	const ITEM_FIELD = "field";
	const ITEM_METHOD = "method";
	public $class_name;
	public $kind;
	public $name;
	public $annotations;
	/**
	 * Returns true if has annotations by class_name
	 * @string class_name
	 * @return bool
	 */
	function hasAnnotation($class_name){
		if ($this->annotations == null){
			return false;
		}
		for ($i = 0; $i < $this->annotations->count(); $i++){
			$item = $this->annotations->item($i);
			if (rtl::is_instanceof($item, $class_name)){
				return true;
			}
		}
		return false;
	}
	/**
	 * Returns true if has annotations by class_name
	 * @string class_name
	 * @return bool
	 */
	function filterAnnotations($class_name){
		if ($this->annotations == null){
			return null;
		}
		return $this->annotations->filter(function ($item) use (&$class_name){
			return rtl::is_instanceof($item, $class_name);
		});
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.IntrospectionInfo";}
	public static function getParentClassName(){return "Runtime.CoreStruct";}
	protected function _init(){
		parent::_init();
		$this->class_name = "";
		$this->kind = "";
		$this->name = "";
		$this->annotations = null;
	}
	public function assignObject($obj){
		if ($obj instanceof IntrospectionInfo){
			$this->class_name = rtl::_clone($obj->class_name);
			$this->kind = rtl::_clone($obj->kind);
			$this->name = rtl::_clone($obj->name);
			$this->annotations = rtl::_clone($obj->annotations);
		}
		parent::assignObject($obj);
	}
	public function assignValue($variable_name, $value){
		if ($variable_name == "class_name") $this->class_name = rtl::correct($value, "string", "", "");
		else if ($variable_name == "kind") $this->kind = rtl::correct($value, "string", "", "");
		else if ($variable_name == "name") $this->name = rtl::correct($value, "string", "", "");
		else if ($variable_name == "annotations") $this->annotations = rtl::correct($value, "Runtime.Vector", null, "Runtime.CoreStruct");
		else parent::assignValue($variable_name, $value);
	}
	public function takeValue($variable_name, $default_value = null){
		if ($variable_name == "class_name") return $this->class_name;
		else if ($variable_name == "kind") return $this->kind;
		else if ($variable_name == "name") return $this->name;
		else if ($variable_name == "annotations") return $this->annotations;
		return parent::takeValue($variable_name, $default_value);
	}
	public static function getFieldsList($names){
		$names->push("class_name");
		$names->push("kind");
		$names->push("name");
		$names->push("annotations");
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
}