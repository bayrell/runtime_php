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
use Runtime\Emitter;
use Runtime\Map;
use Runtime\rtl;
use Runtime\Vector;
use Runtime\Interfaces\ContextInterface;
use Runtime\Interfaces\ModuleDescriptionInterface;
class ModuleDescription implements ModuleDescriptionInterface{
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleName(){
		return "Runtime";
	}
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleVersion(){
		return "0.7.3";
	}
	/**
	 * Returns required modules
	 * @return Map<string>
	 */
	static function requiredModules(){
		return null;
	}
	/**
	 * Compatibility with older versions
	 */
	static function getRequiredModules(){
		return static::requiredModules();
	}
	/**
	 * Returns module files load order
	 * @return Collection<string>
	 */
	static function getModuleFiles(){
		return (new Vector())->push("Runtime.rs")->push("Runtime.re")->push("Runtime.rtl")->push("Runtime.Collection")->push("Runtime.Container")->push("Runtime.CoreObject")->push("Runtime.Dict")->push("Runtime.Emitter")->push("Runtime.RuntimeConstant")->push("Runtime.RuntimeUtils")->push("Runtime.Exceptions.RuntimeException")->push("Runtime.Interfaces.CloneableInterface")->push("Runtime.Interfaces.ContextInterface")->push("Runtime.Interfaces.FactoryInterface")->push("Runtime.Interfaces.ModuleDescriptionInterface")->push("Runtime.Interfaces.SerializeInterface")->push("Runtime.Interfaces.StringInterface")->push("Runtime.Interfaces.SubscribeInterface")->push("Runtime.AsyncTask")->push("Runtime.AsyncThread")->push("Runtime.Context")->push("Runtime.ContextObject")->push("Runtime.CoreStruct")->push("Runtime.CoreEvent")->push("Runtime.Map")->push("Runtime.Maybe")->push("Runtime.ModuleDescription")->push("Runtime.Reference")->push("Runtime.Vector")->push("Runtime.Exceptions.IndexOutOfRange")->push("Runtime.Exceptions.KeyNotFound")->push("Runtime.Exceptions.UnknownError")->push("Runtime.DateTime")->push("Runtime.IntrospectionInfo")->push("Runtime.LambdaChain")->push("Runtime.Provider")->push("Runtime.UIStruct");
	}
	/**
	 * Returns enities
	 */
	static function entities(){
		return null;
	}
	/**
	 * Register lambda filters
	 */
	static function lambdaFilters(){
		return null;
	}
	/**
	 * Called then module registed in context
	 * @param ContextInterface context
	 */
	static function onRegister($context){
	}
	/**
	 * Called then context read config
	 * @param ContextInterface context
	 * @param Map<mixed> config
	 */
	static function onReadConfig($context, $config){
	}
	/**
	 * Init context
	 * @param ContextInterface context
	 */
	static function onInitContext($context){
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.ModuleDescription";}
	public static function getCurrentNamespace(){return "Runtime";}
	public static function getCurrentClassName(){return "Runtime.ModuleDescription";}
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