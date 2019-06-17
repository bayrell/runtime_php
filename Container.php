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
class Container{
	protected $_value;
	/** 
	 * Constructor
	 */
	function __construct($x){
		$this->_value = $x;
	}
	/** 
	 * Returns new instance of this
	 */
	static function of($x){
		return new Container($x);
	}
	/**
	 * Apply function and return new container
	 * @param fun f
	 * @return Container
	 */
	function map($f){
		return $this->of($f($this->_value));
	}
	/**
	 * Return values of the container
	 * @return mixed
	 */
	function value(){
		return $this->_value;
	}
	/**
	 * Returns true if value is empty
	 */
	function isEmpty(){
		return $this->_value == null;
	}
	/**
	 * Returns true if is error
	 */
	function isError(){
		return false;
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Container";}
	public static function getCurrentNamespace(){return "Runtime";}
	public static function getCurrentClassName(){return "Runtime.Container";}
	public static function getParentClassName(){return "";}
	protected function _init(){
	}
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