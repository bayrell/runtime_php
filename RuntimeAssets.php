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
use Runtime\Map;
use Runtime\rtl;
use Runtime\Vector;
use Runtime\Interfaces\AssetsInterface;
use Runtime\Interfaces\ContextInterface;
class RuntimeAssets implements AssetsInterface{
	public function getClassName(){return "Runtime.RuntimeAssets";}
	public static function getParentClassName(){return "";}
	/**
	 * Returns required assets
	 * @return Map<string, string>
	 */
	static function getRequiredAssets($context){
		return null;
	}
	/**
	 * Returns sync loaded files
	 */
	static function assetsSyncLoad($context){
		return null;
	}
	/**
	 * Returns async loaded files
	 */
	static function assetsAsyncLoad($context){
		return (new Vector())->push((new Vector())->push("/assets/Runtime/rs.js")->push("/assets/Runtime/re.js")->push("/assets/Runtime/rtl.js")->push("/assets/Runtime/CoreObject.js")->push("/assets/Runtime/Emitter.js")->push("/assets/Runtime/Map.js")->push("/assets/Runtime/Utils.js")->push("/assets/Runtime/Vector.js")->push("/assets/Runtime/RuntimeConstant.js")->push("/assets/Runtime/Exceptions/RuntimeException.js")->push("/assets/Runtime/Interfaces/ContextInterface.js")->push("/assets/Runtime/Interfaces/FactoryInterface.js")->push("/assets/Runtime/Interfaces/ModuleDescriptionInterface.js")->push("/assets/Runtime/Interfaces/SerializeInterface.js")->push("/assets/Runtime/Interfaces/StringInterface.js")->push("/assets/Runtime/Interfaces/SubscribeInterface.js"))->push((new Vector())->push("/assets/Runtime/Context.js")->push("/assets/Runtime/ContextObject.js")->push("/assets/Runtime/ModuleDescription.js")->push("/assets/Runtime/SerializeContainer.js")->push("/assets/Runtime/VectorString.js")->push("/assets/Runtime/Exceptions/IndexOutOfRange.js")->push("/assets/Runtime/Exceptions/KeyNotFound.js")->push("/assets/Runtime/Exceptions/UnknownError.js"));
	}
}