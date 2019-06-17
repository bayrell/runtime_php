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
namespace Runtime\Interfaces;
use Runtime\Map;
use Runtime\Vector;
use Runtime\Interfaces\ContextInterface;
interface ModuleDescriptionInterface{
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleName();
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleVersion();
	/**
	 * Returns required modules
	 * @return Map<string>
	 */
	static function requiredModules();
	/**
	 * Returns module files load order
	 * @return Collection<string>
	 */
	static function getModuleFiles();
	/**
	 * Returns enities
	 */
	static function entities();
	/**
	 * Called then module registed in context
	 * @param ContextInterface context
	 */
	static function onRegister($context);
	/**
	 * Called then context read config
	 * @param ContextInterface context
	 * @param Map<mixed> config
	 */
	static function onReadConfig($context, $config);
	/**
	 * Init context
	 * @param ContextInterface context
	 */
	static function onInitContext($context);
}