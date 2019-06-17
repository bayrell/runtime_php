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
use Runtime\Dict;
use Runtime\Map;
use Runtime\rs;
use Runtime\rtl;
use Runtime\Collection;
use Runtime\Vector;
class LambdaChain extends CoreStruct{
	const POS_LOW = -100;
	const POS_NORMAL = 0;
	const POS_HIGHT = 100;
	protected $__name;
	protected $__value;
	protected $__pos;
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.LambdaChain";}
	public static function getCurrentNamespace(){return "Runtime";}
	public static function getCurrentClassName(){return "Runtime.LambdaChain";}
	public static function getParentClassName(){return "Runtime.CoreStruct";}
	protected function _init(){
		parent::_init();
		$this->__name = "";
		$this->__value = null;
		$this->__pos = 0;
	}
	public function assignObject($obj){
		if ($obj instanceof LambdaChain){
			$this->__name = $obj->__name;
			$this->__value = $obj->__value;
			$this->__pos = $obj->__pos;
		}
		parent::assignObject($obj);
	}
	public function assignValue($variable_name, $value, $sender = null){
		if ($variable_name == "name")$this->__name = rtl::convert($value,"string","","");
		else if ($variable_name == "value")$this->__value = rtl::convert($value,"fun",null,"");
		else if ($variable_name == "pos")$this->__pos = rtl::convert($value,"int",0,"");
		else parent::assignValue($variable_name, $value, $sender);
	}
	public function takeValue($variable_name, $default_value = null){
		if ($variable_name == "name") return $this->__name;
		else if ($variable_name == "value") return $this->__value;
		else if ($variable_name == "pos") return $this->__pos;
		return parent::takeValue($variable_name, $default_value);
	}
	public static function getFieldsList($names, $flag=0){
		if (($flag | 3)==3){
			$names->push("name");
			$names->push("value");
			$names->push("pos");
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