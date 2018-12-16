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
	static function getName(){
		return "Runtime";
	}
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleVersion(){
		return "0.6.0";
	}
	/**
	 * Returns required modules
	 * @return Map<string, string>
	 */
	static function getRequiredModules($context){
		return null;
	}
	/**
	 * Called then module registed in context
	 * @param ContextInterface context
	 */
	static function onRegister($context){
		$context->registerManager("Runtime.Config", new Map());
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
	static function initContext($context){
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.ModuleDescription";}
	public static function getParentClassName(){return "";}
}