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
namespace Runtime\Exceptions;
use Runtime\rtl;
use Runtime\RuntimeConstant;
use Runtime\Exceptions\RuntimeException;
use Runtime\Interfaces\ContextInterface;
class KeyNotFound extends RuntimeException{
	function __construct($key, $context = null, $prev = null){
		/* RuntimeUtils::translate("ERROR_KEY_NOT_FOUND", null, "", context),  */
		parent::__construct("Key '" . rtl::toString($key) . "' not found", RuntimeConstant::ERROR_KEY_NOT_FOUND, $context, $prev);
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Exceptions.KeyNotFound";}
	public static function getCurrentClassName(){return "Runtime.Exceptions.KeyNotFound";}
	public static function getParentClassName(){return "Runtime.Exceptions.RuntimeException";}
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