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
class ApiException extends \Runtime\Exceptions\RuntimeException
{
	function __construct($__ctx, $message="", $code=-1, $context=null, $prev=null)
	{
		parent::__construct($__ctx, $message, $code, $context, $prev);
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.Exceptions.ApiException";
	}
	static function getCurrentNamespace()
	{
		return "Runtime.Exceptions";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Exceptions.ApiException";
	}
	static function getParentClassName()
	{
		return "Runtime.Exceptions.RuntimeException";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Exceptions.ApiException",
			"name"=>"Runtime.Exceptions.ApiException",
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