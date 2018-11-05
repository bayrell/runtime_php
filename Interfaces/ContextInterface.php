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
namespace Runtime\Interfaces;
use Runtime\CoreObject;
interface ContextInterface{
	/**
	 * Register module
	 */
	function registerModule($module_name);
	/**
	 * Register provider
	 * @param string provider_name
	 * @param FactoryInterface factory
	 */
	function registerProviderFactory($provider_name, $factory);
	/**
	 * Register manager
	 * @param string manager_name
	 * @param CoreObject obj
	 */
	function registerManager($manager_name, $obj);
	/**
	 * Returns provider
	 * @params string provider_name
	 * @return CoreObject
	 */
	function createProvider($provider_name);
	/**
	 * Returns manager
	 * @params string manager_name
	 * @return CoreObject
	 */
	function getManager($manager_name);
	/**
	 * Set application locale
	 * @params string locale
	 */
	function setLocale($locale);
	/**
	 * Get application locale
	 * @params string locale
	 */
	function getLocale();
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params MapInterface params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	/*string translate(string message, Map params = null, string locale = "");*/
	/**
	 * For current context
	 * @return ContextInterface
	 */
	function fork();
	/**
	 * Realease context resources
	 */
	function release();
	/**
	 * Returns context value
	 * @param string name
	 * @return mixed
	 */
	function getValue($name, $default_value = null, $type_value = "mixed", $type_template = "");
	/**
	 * Set context value
	 * @param string name
	 */
	function setValue($name, $value);
}