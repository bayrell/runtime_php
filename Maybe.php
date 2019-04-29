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
use Runtime\Container;
class Maybe extends Container{
	/** 
	 * Returns new instance of this
	 */
	static function of($x){
		return new Maybe($x);
	}
	/**
	 * Apply function and return new container
	 * @param fun f
	 * @return Container
	 */
	function map($f){
		return ($this->_value == null) ? ($this) : ($this->of($f($this->_value)));
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Maybe";}
	public static function getCurrentClassName(){return "Runtime.Maybe";}
	public static function getParentClassName(){return "Runtime.Container";}
	public static function getFieldsList($names, $flag=0){
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
	public static function getMethodsList($names){
	}
	public static function getMethodInfoByName($method_name){
		return null;
	}
}