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
use Runtime\CoreStruct;
use Runtime\Maybe;
use Runtime\rtl;
use Runtime\Collection;
class IntrospectionInfo extends CoreStruct{
	const ITEM_CLASS = "class";
	const ITEM_FIELD = "field";
	const ITEM_METHOD = "method";
	protected $__class_name;
	protected $__kind;
	protected $__name;
	protected $__annotations;
	/* lambda isInstanceOf(string class_name) => bool (CoreStruct item) => rtl::is_instanceof(item, class_name); */
	/**
	 * Returns true if has annotations by class_name
	 * @string class_name
	 * @return bool
	 */
	static function filterAnnotations($class_name, $info){
		if ($info->annotations == null){
			return null;
		}
		return $info->annotations->filter(function ($item) use (&$class_name){
			return rtl::is_instanceof($item, $class_name);
		})->toCollection();
	}
	/**
	 * Returns true if has annotations by class_name
	 * @string class_name
	 * @return bool
	 */
	function hasAnnotationOld($class_name){
		/*
		return Maybe::of(this.annotations)
			.map( 
				rtl::findFirst(
					bool (CoreStruct item) use (class_name)
					{
						return rtl::is_instanceof(item, class_name);
					}
				) 
			)
			.value() != null
		;
		
		*/
		/* return 
			( 
				pipe(this.annotations) >> 
				rtl::findFirst(self::isInstanceOf(class_name))
			).value() != null
		; 
		*/
		/* return Maybe.of(this.annotations).map( rtl::findFirst( self::isInstanceOf(class_name) ) ).value() != null; */
		/*
		if (this.annotations == null)
		{
			return false;
		}
		
		for (int i=0; i<this.annotations.count(); i++)
		{
			CoreStruct item = this.annotations.item(i);
			if (rtl::is_instanceof(item, class_name))
			{
				return true;
			}
		}
		
		return false;
		*/
	}
	/**
	 * Returns true if has annotations by class_name
	 * @string class_name
	 * @return bool
	 */
	function filterAnnotationsOld($class_name){
		/*
		return Maybe.of(this.annotations)
			.map( 
				rtl::filter(
					bool (CoreStruct item) use (class_name)
					{
						return rtl::is_instanceof(item, class_name);
					}
				) 
			)
			.value()
		;
		*/
		/* return Maybe.of(this.annotations).map( rtl::filter( self::isInstanceOf(class_name) ) ).value() != null; */
		/*
		if (this.annotations == null)
		{
			return null;
		}
		
		return this.annotations.filter(
			bool (CoreStruct item) use (class_name)
			{
				return rtl::is_instanceof(item, class_name);
			}
		);
		*/
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.IntrospectionInfo";}
	public static function getCurrentNamespace(){return "Runtime";}
	public static function getCurrentClassName(){return "Runtime.IntrospectionInfo";}
	public static function getParentClassName(){return "Runtime.CoreStruct";}
	protected function _init(){
		parent::_init();
		$this->__class_name = "";
		$this->__kind = "";
		$this->__name = "";
		$this->__annotations = null;
	}
	public function assignObject($obj){
		if ($obj instanceof IntrospectionInfo){
			$this->__class_name = $obj->__class_name;
			$this->__kind = $obj->__kind;
			$this->__name = $obj->__name;
			$this->__annotations = $obj->__annotations;
		}
		parent::assignObject($obj);
	}
	public function assignValue($variable_name, $value, $sender = null){
		if ($variable_name == "class_name")$this->__class_name = rtl::convert($value,"string","","");
		else if ($variable_name == "kind")$this->__kind = rtl::convert($value,"string","","");
		else if ($variable_name == "name")$this->__name = rtl::convert($value,"string","","");
		else if ($variable_name == "annotations")$this->__annotations = rtl::convert($value,"Runtime.Collection",null,"Runtime.CoreStruct");
		else parent::assignValue($variable_name, $value, $sender);
	}
	public function takeValue($variable_name, $default_value = null){
		if ($variable_name == "class_name") return $this->__class_name;
		else if ($variable_name == "kind") return $this->__kind;
		else if ($variable_name == "name") return $this->__name;
		else if ($variable_name == "annotations") return $this->__annotations;
		return parent::takeValue($variable_name, $default_value);
	}
	public static function getFieldsList($names, $flag=0){
		if (($flag | 3)==3){
			$names->push("class_name");
			$names->push("kind");
			$names->push("name");
			$names->push("annotations");
		}
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
	public static function getMethodsList($names){
	}
	public static function getMethodInfoByName($method_name){
		return null;
	}
	public function __get($key){ return $this->takeValue($key); }
	public function __set($key, $value){throw new \Runtime\Exceptions\AssignStructValueError($key);}
}