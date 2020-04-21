<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2020 "Ildar Bikmamatov" <support@bayrell.org>
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
namespace Runtime\Annotations;
class Provider extends \Runtime\Annotations\Entity
{
	/* ======================= Class Init Functions ======================= */
	function assignObject($ctx,$o)
	{
		if ($o instanceof \Runtime\Annotations\Provider)
		{
		}
		parent::assignObject($ctx,$o);
	}
	function assignValue($ctx,$k,$v)
	{
		parent::assignValue($ctx,$k,$v);
	}
	function takeValue($ctx,$k,$d=null)
	{
		return parent::takeValue($ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.Annotations.Provider";
	}
	static function getCurrentNamespace()
	{
		return "Runtime.Annotations";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Annotations.Provider";
	}
	static function getParentClassName()
	{
		return "Runtime.Annotations.Entity";
	}
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Annotations.Provider",
			"name"=>"Runtime.Annotations.Provider",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		return null;
	}
	static function getMethodsList($ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}