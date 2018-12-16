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
		return (new Vector())->push((new Vector())->push("/assets/bayrell-runtime-es6/rs.js")->push("/assets/bayrell-runtime-es6/re.js")->push("/assets/bayrell-runtime-es6/rtl.js")->push("/assets/bayrell-runtime-es6/CoreObject.js")->push("/assets/bayrell-runtime-es6/Emitter.js")->push("/assets/bayrell-runtime-es6/Map.js")->push("/assets/bayrell-runtime-es6/Vector.js")->push("/assets/bayrell-runtime-es6/RuntimeConstant.js")->push("/assets/bayrell-runtime-es6/RuntimeUtils.js")->push("/assets/bayrell-runtime-es6/Exceptions/RuntimeException.js")->push("/assets/bayrell-runtime-es6/Interfaces/AssetsInterface.js")->push("/assets/bayrell-runtime-es6/Interfaces/CloneableInterface.js")->push("/assets/bayrell-runtime-es6/Interfaces/ContextInterface.js")->push("/assets/bayrell-runtime-es6/Interfaces/FactoryInterface.js")->push("/assets/bayrell-runtime-es6/Interfaces/ModuleDescriptionInterface.js")->push("/assets/bayrell-runtime-es6/Interfaces/SerializeInterface.js")->push("/assets/bayrell-runtime-es6/Interfaces/StringInterface.js")->push("/assets/bayrell-runtime-es6/Interfaces/SubscribeInterface.js"))->push((new Vector())->push("/assets/bayrell-runtime-es6/AsyncTask.js")->push("/assets/bayrell-runtime-es6/AsyncThread.js")->push("/assets/bayrell-runtime-es6/Context.js")->push("/assets/bayrell-runtime-es6/ContextObject.js")->push("/assets/bayrell-runtime-es6/CoreEvent.js")->push("/assets/bayrell-runtime-es6/ModuleDescription.js")->push("/assets/bayrell-runtime-es6/SerializeContainer.js")->push("/assets/bayrell-runtime-es6/VectorString.js")->push("/assets/bayrell-runtime-es6/Exceptions/IndexOutOfRange.js")->push("/assets/bayrell-runtime-es6/Exceptions/KeyNotFound.js")->push("/assets/bayrell-runtime-es6/Exceptions/UnknownError.js"));
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.RuntimeAssets";}
	public static function getParentClassName(){return "";}
}