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
class ModuleDescription implements \Runtime\Interfaces\ModuleDescriptionInterface, \Runtime\Interfaces\AssetsInterface
{
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleName($__ctx)
	{
		return "Runtime";
	}
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleVersion($__ctx)
	{
		return "0.8.0-alpha.9";
	}
	/**
	 * Returns required modules
	 * @return Map<string>
	 */
	static function requiredModules($__ctx)
	{
		return null;
	}
	/**
	 * Compatibility with older versions
	 */
	static function getRequiredModules($__ctx)
	{
		return static::requiredModules($__ctx);
	}
	/**
	 * Returns module files load order
	 * @return Collection<string>
	 */
	static function assets($__ctx)
	{
		return \Runtime\Collection::from(["Runtime/rtl","Runtime/rs","Runtime/re","Runtime/lib","Runtime/Collection","Runtime/Container","Runtime/CoreObject","Runtime/Dict","Runtime/Emitter","Runtime/RuntimeConstant","Runtime/RuntimeUtils","Runtime/Exceptions/RuntimeException","Runtime/Interfaces/CloneableInterface","Runtime/Interfaces/FactoryInterface","Runtime/Interfaces/LocalBusInterface","Runtime/Interfaces/ModuleDescriptionInterface","Runtime/Interfaces/RemoteBusInterface","Runtime/Interfaces/SerializeInterface","Runtime/Interfaces/StringInterface","Runtime/Interfaces/SubscribeInterface","Runtime/AsyncTask","Runtime/AsyncThread","Runtime/CoreStruct","Runtime/CoreProvider","Runtime/CoreEvent","Runtime/BusResult","Runtime/Map","Runtime/Message","Runtime/MessageRPC","Runtime/PathInfo","Runtime/ModuleDescription","Runtime/Reference","Runtime/Vector","Runtime/Exceptions/ApiException","Runtime/Exceptions/IndexOutOfRange","Runtime/Exceptions/KeyNotFound","Runtime/Exceptions/UnknownError","Runtime/DateTime","Runtime/Annotations/Entity","Runtime/Annotations/IntrospectionClass","Runtime/Annotations/IntrospectionInfo","Runtime/Annotations/LambdaChain","Runtime/Annotations/LambdaChainDeclare","Runtime/Annotations/Driver","Runtime/Annotations/Provider","Runtime/UIStruct","Runtime/Context","Runtime/ContextObject"]);
	}
	/**
	 * Returns enities
	 */
	static function entities($__ctx)
	{
		return \Runtime\Collection::from([new \Runtime\Annotations\Provider($__ctx, \Runtime\Dict::from(["name"=>"Runtime.Interfaces.LocalBusInterface","kind"=>"interface"])),new \Runtime\Annotations\Provider($__ctx, \Runtime\Dict::from(["name"=>"Runtime.Interfaces.RemoteBusInterface","kind"=>"interface"])),new \Runtime\Annotations\LambdaChainDeclare($__ctx, \Runtime\Dict::from(["name"=>"Runtime.Entities"]))]);
	}
	/**
	 * Returns enities
	 */
	static function resources($__ctx)
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
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.ModuleDescription",
			"name"=>"Runtime.ModuleDescription",
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