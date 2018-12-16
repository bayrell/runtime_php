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
namespace Runtime\Interfaces;
use Runtime\CoreObject;
use Runtime\Map;
use Runtime\Vector;
use Runtime\Interfaces\ContextInterface;
interface SerializeInterface{
	/**
	 * Returns classname of the object
	 * @return string
	 */
	function getClassName();
	/**
	 * Returns name of variables to serialization
	 * @return Vector<string>
	 */
	function getVariablesNames($names);
	/**
	 * Assign and clone data from other object
	 * @param CoreObject obj
	 */
	function assignObject($obj);
	/**
	 * Set new value instance by variable name
	 * @param string variable_name
	 * @param var value
	 */
	function assignValue($variable_name, $value);
	/**
	 * Returns instance of the value by variable name
	 * @param string variable_name
	 * @return var
	 */
	function takeValue($variable_name, $default_value = null);
	/* ======================= Class Init Functions ======================= */
}