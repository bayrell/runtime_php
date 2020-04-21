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
namespace Runtime;
class ModuleDescription implements \Runtime\Interfaces\ModuleDescriptionInterface, \Runtime\Interfaces\AssetsInterface
{
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleName($ctx)
	{
		return "Runtime";
	}
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleVersion($ctx)
	{
		return "0.9.0";
	}
	/**
	 * Returns required modules
	 * @return Map<string>
	 */
	static function requiredModules($ctx)
	{
		return null;
	}
	/**
	 * Compatibility with older versions
	 */
	static function getRequiredModules($ctx)
	{
		return static::requiredModules($ctx);
	}
	/**
	 * Returns module files load order
	 * @return Collection<string>
	 */
	static function assets($ctx)
	{
		return \Runtime\Collection::from(["Runtime/rtl","Runtime/rs","Runtime/re","Runtime/lib","Runtime/Collection","Runtime/CoreObject","Runtime/Dict","Runtime/RuntimeConstant","Runtime/RuntimeUtils","Runtime/Exceptions/RuntimeException","Runtime/Interfaces/AssetsInterface","Runtime/Interfaces/BusInterface","Runtime/Interfaces/ModuleDescriptionInterface","Runtime/Interfaces/SerializeInterface","Runtime/Interfaces/StringInterface","Runtime/CoreStruct","Runtime/CoreProvider","Runtime/CoreEvent","Runtime/Map","Runtime/Message","Runtime/MessageRPC","Runtime/PathInfo","Runtime/ModuleDescription","Runtime/Monad","Runtime/Reference","Runtime/Vector","Runtime/Exceptions/ApiException","Runtime/Exceptions/IndexOutOfRange","Runtime/Exceptions/KeyNotFound","Runtime/Exceptions/UnknownError","Runtime/DateTime","Runtime/Annotations/Entity","Runtime/Annotations/IntrospectionClass","Runtime/Annotations/IntrospectionInfo","Runtime/Annotations/LambdaChain","Runtime/Annotations/LambdaChainDeclare","Runtime/Annotations/Driver","Runtime/Annotations/Provider","Runtime/UIStruct","Runtime/Context","Runtime/AsyncAwait"]);
	}
	/**
	 * Returns enities
	 */
	static function entities($ctx)
	{
		return \Runtime\Collection::from([new \Runtime\Annotations\LambdaChainDeclare($ctx, \Runtime\Dict::from(["name"=>"Runtime.Entities"]))]);
	}
	/**
	 * Returns enities
	 */
	static function resources($ctx)
	{
		return null;
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.ModuleDescription";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.ModuleDescription";
	}
	static function getParentClassName()
	{
		return "";
	}
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.ModuleDescription",
			"name"=>"Runtime.ModuleDescription",
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