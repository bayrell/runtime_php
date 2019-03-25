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
namespace Runtime\Exceptions;
use Runtime\rtl;
use Runtime\RuntimeConstant;
use Runtime\Exceptions\RuntimeException;
use Runtime\Interfaces\ContextInterface;
class AssignStructValueError extends RuntimeException{
	function __construct($name, $context = null, $prev = null){
		parent::__construct(rtl::translate("Can not set key '" . rtl::toString($name) . "' in immutable struct", null, "", $context), RuntimeConstant::ERROR_INDEX_OUT_OF_RANGE, $context, $prev);
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Exceptions.AssignStructValueError";}
	public static function getCurrentClassName(){return "Runtime.Exceptions.AssignStructValueError";}
	public static function getParentClassName(){return "Runtime.Exceptions.RuntimeException";}
}