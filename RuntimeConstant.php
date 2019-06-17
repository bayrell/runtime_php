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
class RuntimeConstant{
	/* Log level */
	/**
	 * Fatal error. Application stoped
	 */
	const LOG_FATAL = 0;
	/**
	 * Critical error. Application damaged, but works
	 */
	const LOG_CRITICAL = 2;
	/**
	 * Any Application error or exception
	 */
	const LOG_ERROR = 4;
	/**
	 * Log warning. Developer should attention to this
	 */
	const LOG_WARNING = 6;
	/**
	 * Information about any event
	 */
	const LOG_INFO = 8;
	/**
	 * Debug level 1
	 */
	const LOG_DEBUG = 10;
	/**
	 * Debug level 2
	 */
	const LOG_DEBUG2 = 12;
	/* Status codes */
	const STATUS_PLAN = 0;
	const STATUS_DONE = 1;
	const STATUS_PROCESS = 100;
	const STATUS_FAIL = -1;
	/* Errors */
	const ERROR_NULL = 0;
	const ERROR_OK = 1;
	const ERROR_PROCCESS = 100;
	const ERROR_FALSE = -100;
	const ERROR_UNKNOWN = -1;
	const ERROR_INDEX_OUT_OF_RANGE = -2;
	const ERROR_KEY_NOT_FOUND = -3;
	const ERROR_STOP_ITERATION = -4;
	const ERROR_OBJECT_DOES_NOT_EXISTS = -5;
	const ERROR_OBJECT_ALLREADY_EXISTS = -6;
	const ERROR_ASSERT = -7;
	const ERROR_REQUEST = -8;
	const ERROR_RESPONSE = -9;
	const ERROR_CSRF_TOKEN = -10;
	const ERROR_RUNTIME = -11;
	const ERROR_VALIDATION = -12;
	const ERROR_PARSE_SERIALIZATION_ERROR = -14;
	const ERROR_ASSIGN_DATA_STRUCT_VALUE = -15;
	const ERROR_FILE_NOT_FOUND = -16;
	const ERROR_FATAL = -99;
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.RuntimeConstant";}
	public static function getCurrentNamespace(){return "Runtime";}
	public static function getCurrentClassName(){return "Runtime.RuntimeConstant";}
	public static function getParentClassName(){return "";}
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