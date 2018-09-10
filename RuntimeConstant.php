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
class RuntimeConstant{
	const LOG_FATAL = 0;
	const LOG_CRITICAL = 2;
	const LOG_ERROR = 4;
	const LOG_WARNING = 6;
	const LOG_INFO = 8;
	const LOG_DEBUG = 10;
	const LOG_DEBUG2 = 12;
	const STATUS_DONE = 0;
	const STATUS_PLAN = 1;
	const STATUS_PROCESS = 2;
	const STATUS_FAIL = -1;
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
	public function getClassName(){return "Runtime.RuntimeConstant";}
	public static function getParentClassName(){return "";}
	/* Log level */
	/**
	 * Fatal error. Application stoped
	 */
	/**
	 * Critical error. Application damaged, but works
	 */
	/**
	 * Any Application error or exception
	 */
	/**
	 * Log warning. Developer should attention to this
	 */
	/**
	 * Information about any event
	 */
	/**
	 * Debug level 1
	 */
	/**
	 * Debug level 2
	 */
	/* Status codes */
	/* Errors */
}