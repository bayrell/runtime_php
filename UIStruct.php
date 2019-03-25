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
use Runtime\Dict;
use Runtime\Map;
use Runtime\rs;
use Runtime\rtl;
use Runtime\Collection;
use Runtime\Vector;
class UIStruct extends CoreStruct{
	const TYPE_ELEMENT = "element";
	const TYPE_COMPONENT = "component";
	const TYPE_STRING = "string";
	const TYPE_RAW = "raw";
	protected $__class_name;
	protected $__key;
	protected $__name;
	protected $__space;
	protected $__kind;
	protected $__content;
	protected $__controller;
	protected $__model;
	protected $__props;
	protected $__children;
	/**
	 * Returns true if component
	 * @return bool
	 */
	static function isComponent($ui){
		return $ui->kind == self::TYPE_COMPONENT;
	}
	/**
	 * Returns true if element
	 * @return bool
	 */
	static function isElement($ui){
		return $ui->kind == self::TYPE_ELEMENT;
	}
	/**
	 * Returns true if string
	 * @return bool
	 */
	static function isString($ui){
		return $ui->kind == self::TYPE_STRING || $ui->kind == self::TYPE_RAW;
	}
	/**
	 * Returns model
	 * @return CoreStruct
	 */
	static function getModel($ui){
		if ($ui->model != null){
			return $ui->model;
		}
		if ($ui->kind == self::TYPE_COMPONENT){
			$model_name = rtl::method($ui->name, "modelName")();
			$model = rtl::newInstance($model_name, (new Vector())->push($ui->props));
			return $model;
		}
		return null;
	}
	/**
	 * Returns key path
	 * @return string
	 */
	static function getKey($ui, $index){
		return ($ui->key !== "") ? ($ui->key) : ($index);
	}
	/**
	 * Returns key path
	 * @return string
	 */
	static function getKeyPath($ui, $key_path, $index){
		return ($key_path !== "") ? (rtl::toString($key_path) . "." . rtl::toString(static::getKey($ui, $index))) : (static::getKey($ui, $index));
	}
	/**
	 * Returns attrs
	 */
	static function getAttrs($ui){
		if ($ui->props != null){
			return $ui->props->filter(function ($key, $value){
				return rs::strpos($key, "@") != 0 || $key == "@class";
			});
		}
		return new Dict();
	}
	/**
	 * Returns props
	 */
	static function getProps($ui){
		if ($ui->props != null){
			return $ui->props->filter(function ($key, $value){
				return rs::strpos($key, "@") == 0 && rs::strpos($key, "@on") != 0 && $key != "@class";
			});
		}
		return new Dict();
	}
	/**
	 * Returns events
	 */
	static function getEvents($ui){
		if ($ui->props != null){
			return $ui->props->filter(function ($key, $value){
				return rs::strpos($key, "@on") == 0;
			});
		}
		return new Dict();
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.UIStruct";}
	public static function getCurrentClassName(){return "Runtime.UIStruct";}
	public static function getParentClassName(){return "Runtime.CoreStruct";}
	protected function _init(){
		parent::_init();
		$this->__class_name = "";
		$this->__key = "";
		$this->__name = "";
		$this->__space = "";
		$this->__kind = "element";
		$this->__content = "";
		$this->__controller = "";
		$this->__model = null;
		$this->__props = null;
		$this->__children = null;
	}
	public function assignObject($obj){
		if ($obj instanceof UIStruct){
			$this->__class_name = $obj->__class_name;
			$this->__key = $obj->__key;
			$this->__name = $obj->__name;
			$this->__space = $obj->__space;
			$this->__kind = $obj->__kind;
			$this->__content = $obj->__content;
			$this->__controller = $obj->__controller;
			$this->__model = $obj->__model;
			$this->__props = $obj->__props;
			$this->__children = $obj->__children;
		}
		parent::assignObject($obj);
	}
	public function assignValue($variable_name, $value, $sender = null){
		if ($variable_name == "class_name")$this->__class_name = rtl::convert($value,"string","","");
		else if ($variable_name == "key")$this->__key = rtl::convert($value,"string","","");
		else if ($variable_name == "name")$this->__name = rtl::convert($value,"string","","");
		else if ($variable_name == "space")$this->__space = rtl::convert($value,"string","","");
		else if ($variable_name == "kind")$this->__kind = rtl::convert($value,"string","element","");
		else if ($variable_name == "content")$this->__content = rtl::convert($value,"string","","");
		else if ($variable_name == "controller")$this->__controller = rtl::convert($value,"string","","");
		else if ($variable_name == "model")$this->__model = rtl::convert($value,"Runtime.CoreStruct",null,"");
		else if ($variable_name == "props")$this->__props = rtl::convert($value,"Runtime.Dict",null,"primitive");
		else if ($variable_name == "children")$this->__children = rtl::convert($value,"Runtime.Collection",null,"Runtime.UIStruct");
		else parent::assignValue($variable_name, $value, $sender);
	}
	public function takeValue($variable_name, $default_value = null){
		if ($variable_name == "class_name") return $this->__class_name;
		else if ($variable_name == "key") return $this->__key;
		else if ($variable_name == "name") return $this->__name;
		else if ($variable_name == "space") return $this->__space;
		else if ($variable_name == "kind") return $this->__kind;
		else if ($variable_name == "content") return $this->__content;
		else if ($variable_name == "controller") return $this->__controller;
		else if ($variable_name == "model") return $this->__model;
		else if ($variable_name == "props") return $this->__props;
		else if ($variable_name == "children") return $this->__children;
		return parent::takeValue($variable_name, $default_value);
	}
	public static function getFieldsList($names, $flag=0){
		if (($flag | 3)==3){
			$names->push("class_name");
			$names->push("key");
			$names->push("name");
			$names->push("space");
			$names->push("kind");
			$names->push("content");
			$names->push("controller");
			$names->push("model");
			$names->push("props");
			$names->push("children");
		}
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
	public function __get($key){ return $this->takeValue($key); }
	public function __set($key, $value){throw new \Runtime\Exceptions\AssignStructValueError($key);}
}