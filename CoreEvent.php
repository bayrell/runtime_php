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
use Runtime\CoreObject;
use Runtime\CoreStruct;
use Runtime\rtl;
class CoreEvent extends CoreStruct{
	protected $__sender;
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.CoreEvent";}
	public static function getCurrentClassName(){return "Runtime.CoreEvent";}
	public static function getParentClassName(){return "Runtime.CoreStruct";}
	protected function _init(){
		parent::_init();
		$this->__sender = null;
	}
	public function assignObject($obj){
		if ($obj instanceof CoreEvent){
			$this->__sender = $obj->__sender;
		}
		parent::assignObject($obj);
	}
	public function assignValue($variable_name, $value, $sender = null){
		if ($variable_name == "sender")$this->__sender = rtl::convert($value,"Runtime.CoreObject",null,"");
		else parent::assignValue($variable_name, $value, $sender);
	}
	public function takeValue($variable_name, $default_value = null){
		if ($variable_name == "sender") return $this->__sender;
		return parent::takeValue($variable_name, $default_value);
	}
	public static function getFieldsList($names, $flag=0){
		if (($flag | 3)==3){
			$names->push("sender");
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