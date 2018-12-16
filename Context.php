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
use Runtime\rtl;
use Runtime\CoreObject;
use Runtime\Map;
use Runtime\Vector;
use Runtime\Interfaces\ContextInterface;
use Runtime\Interfaces\FactoryInterface;
use Runtime\Interfaces\ModuleDescriptionInterface;
class Context extends CoreObject implements ContextInterface{
	protected $_modules;
	protected $_values;
	protected $_managers;
	protected $_providers_names;
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->_modules = new Vector();
		$this->_providers_names = new Map();
		$this->_managers = new Map();
		$this->_values = new Map();
	}
	/**
	 * Destructor
	 */
	function __destruct(){
		parent::__destruct();
	}
	/**
	 * Returns registed modules
	 * @return Vector<string>
	 */
	function getModules(){
		return $this->_modules->slice();
	}
	/**
	 * Register module
	 */
	function registerModule($module_name){
		$module_description_class_name = rtl::toString($module_name) . ".ModuleDescription";
		if ($this->_modules->indexOf($module_description_class_name) != -1){
			return ;
		}
		$this->_modules->push($module_description_class_name);
		/* Call onRegister */
		$args = (new Vector())->push($this);
		rtl::callStaticMethod($module_description_class_name, "onRegister", $args);
		/* Register required Modules*/
		$modules = rtl::callStaticMethod($module_description_class_name, "getRequiredModules", $args);
		if ($modules != null){
			$keys = $modules->keys();
			$sz = $keys->count();
			for ($i = 0; $i < $sz; $i++){
				$module_name = $keys->item($i);
				$this->registerModule($module_name);
			}
		}
		return $this;
	}
	/**
	 * Register module
	 * @param string provider_name
	 * @param FactoryInterface factory
	 */
	function registerProviderFactory($provider_name, $factory){
		if (!$this->_providers_names->has($provider_name)){
			$this->_providers_names->set($provider_name, $factory);
		}
		return $this;
	}
	/**
	 * Register manager
	 * @param string manager_name
	 * @param FactoryInterface factory
	 */
	function registerManager($manager_name, $obj){
		if (!$this->_managers->has($manager_name)){
			$this->_managers->set($manager_name, $obj);
		}
		return $this;
	}
	/**
	 * Read config
	 */
	function readConfig($config){
		$args = new Vector();
		$args->push($this);
		$args->push($config);
		$sz = $this->_modules->count();
		for ($i = 0; $i < $sz; $i++){
			$module_description_class_name = $this->_modules->item($i);
			rtl::callStaticMethod($module_description_class_name, "onReadConfig", $args);
		}
		return $this;
	}
	/**
	 * Init context
	 */
	function init(){
		$args = new Vector();
		$args->push($this);
		$sz = $this->_modules->count();
		for ($i = 0; $i < $sz; $i++){
			$module_description_class_name = $this->_modules->item($i);
			rtl::callStaticMethod($module_description_class_name, "initContext", $args);
		}
		return $this;
	}
	/**
	 * Returns provider
	 *
	 * @params string provider_name
	 * @return CoreObject
	 */
	function createProvider($provider_name){
		if (!$this->_providers_names->has($provider_name)){
			return null;
		}
		$factory_obj = $this->_providers_names->item($provider_name);
		if ($factory_obj == null){
			return null;
		}
		$obj = $factory_obj->newInstance($this);
		return $obj;
	}
	/**
	 * Returns manager
	 *
	 * @params string manager_name
	 * @return CoreObject
	 */
	function getManager($manager_name){
		if ($this->_managers->has($manager_name)){
			return $this->_managers->item($manager_name);
		}
		return null;
	}
	/**
	 * Set application locale
	 * @params string locale
	 */
	function setLocale($locale){
		$this->_values->set("default.locale", $locale);
	}
	/**
	 * Get application locale
	 * @params string locale
	 */
	function getLocale(){
		return $this->_values->get("default.locale", "en", "string");
	}
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params Map params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	function translate($message, $params = null, $locale = ""){
		return $message;
	}
	/**
	 * Fork current context
	 * @return ContextInterface
	 */
	function fork(){
		$class_name = $this->getClassName();
		$obj = rtl::newInstance($class_name);
		/* Add modules */
		$this->_modules->each(function ($item) use (&$obj){
			$obj->_modules->push($item);
		});
		/* Add managers */
		$this->_managers->each(function ($key, $value) use (&$obj){
			$obj->_managers->set($key, $value);
		});
		/* Add provider names */
		$this->_providers_names->each(function ($key, $value) use (&$obj){
			$obj->_providers_names->set($key, $value);
		});
		return $obj;
	}
	/**
	 * Realease context resources
	 */
	function release(){
	}
	/**
	 * Returns context value
	 * @param string name
	 * @return mixed
	 */
	function getValue($name, $default_value = null, $type_value = "mixed", $type_template = ""){
		return $this->_values->get($name, $default_value, $type_value, $type_template);
	}
	/**
	 * Set context value
	 * @param string name
	 * @param mixed value
	 */
	function setValue($name, $value){
		$this->_values->set($name, $value);
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Context";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
	protected function _init(){
		parent::_init();
		$this->_modules = null;
		$this->_values = null;
		$this->_managers = null;
		$this->_providers_names = null;
	}
}