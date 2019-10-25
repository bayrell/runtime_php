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
class RuntimeConstant
{
	const CHAIN_ENTITIES="Runtime.Entities";
	const LOCAL_BUS="Runtime.Interfaces.LocalBusInterface";
	const REMOTE_BUS="Runtime.Interfaces.RemoteBusInterface";
	const LOG_FATAL=0;
	const LOG_CRITICAL=2;
	const LOG_ERROR=4;
	const LOG_WARNING=6;
	const LOG_INFO=8;
	const LOG_DEBUG=10;
	const LOG_DEBUG2=12;
	const STATUS_PLAN=0;
	const STATUS_DONE=1;
	const STATUS_PROCESS=100;
	const STATUS_FAIL=-1;
	const ERROR_NULL=0;
	const ERROR_OK=1;
	const ERROR_PROCCESS=100;
	const ERROR_FALSE=-100;
	const ERROR_UNKNOWN=-1;
	const ERROR_INDEX_OUT_OF_RANGE=-2;
	const ERROR_KEY_NOT_FOUND=-3;
	const ERROR_STOP_ITERATION=-4;
	const ERROR_FILE_NOT_FOUND=-5;
	const ERROR_OBJECT_DOES_NOT_EXISTS=-5;
	const ERROR_OBJECT_ALLREADY_EXISTS=-6;
	const ERROR_ASSERT=-7;
	const ERROR_REQUEST=-8;
	const ERROR_RESPONSE=-9;
	const ERROR_CSRF_TOKEN=-10;
	const ERROR_RUNTIME=-11;
	const ERROR_VALIDATION=-12;
	const ERROR_PARSE_SERIALIZATION_ERROR=-14;
	const ERROR_ASSIGN_DATA_STRUCT_VALUE=-15;
	const ERROR_AUTH=-16;
	const ERROR_DUPLICATE=-17;
	const ERROR_FATAL=-99;
	const ERROR_HTTP_CONTINUE=-100;
	const ERROR_HTTP_SWITCH=-101;
	const ERROR_HTTP_PROCESSING=-102;
	const ERROR_HTTP_OK=-200;
	const ERROR_HTTP_BAD_GATEWAY=-502;
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.RuntimeConstant";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.RuntimeConstant";
	}
	static function getParentClassName()
	{
		return "";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.RuntimeConstant",
			"name"=>"Runtime.RuntimeConstant",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($__ctx,$field_name)
	{
		return null;
	}
	static function getMethodsList($__ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($__ctx,$field_name)
	{
		return null;
	}
}