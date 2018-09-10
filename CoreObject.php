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
	public function getClassName(){return "Runtime.CoreObject";}
	public static function getParentClassName(){return "";}
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
	 * Returns name of variables to serialization
	 * @return Vector<string>
	 */
	function getVariablesNames($names){
	}
	/**
	 * Returns instance of the value by variable name
	 * @param string variable_name
	 * @return var
	 */
	function takeValue($variable_name, $default_value = null){
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
	 * Set new value instance by variable name
	 * @param string variable_name
	 * @param var value
	 */
	function assignValues($values = null){
		if ($values == null){
			return ;
		}
		$names = new Vector();
		$this->getVariablesNames($names);
		$names->each(function ($name) use (&$values){
			$value = $values->get($name, null);
			$this->assignValue($name, $value);
		});
	}
	/**
	 * Set new value instance by variable name
	 * @param string variable_name
	 * @param var value
	 */
	function takeValues(){
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
	 * Assign all data from other object
	 * @param CoreObject obj
	 */
	function assign($obj){
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
}