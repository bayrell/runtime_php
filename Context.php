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
use Runtime\rs;
use Runtime\rtl;
use Runtime\CoreObject;
use Runtime\LambdaChain;
use Runtime\Provider;
use Runtime\Dict;
use Runtime\Map;
use Runtime\Vector;
use Runtime\Interfaces\ContextInterface;
use Runtime\Interfaces\FactoryInterface;
use Runtime\Interfaces\ModuleDescriptionInterface;
class Context extends CoreObject implements ContextInterface{
	protected $base_path;
	protected $config;
	protected $_modules;
	protected $_entities;
	protected $_drivers;
	protected $_providers;
	protected $_providers_obj;
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->_entities = new Vector();
		$this->_modules = new Vector();
		$this->_providers = new Map();
		$this->_providers_obj = new Map();
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
		return $this->_modules->toCollection();
	}
	/**
	 * Returns providers names
	 */
	function getProviders(){
		return $this->_providers->keys()->toCollection();
	}
	/**
	 * Returns helper
	 *
	 * @params string provider_name
	 * @return CoreStruct
	 */
	function createProvider($provider_name){
		if ($this->_providers->has($provider_name)){
			$info = $this->_providers->item($provider_name);
			$obj = rtl::newInstance($info->value);
			if ($info->init){
				$f = $info->init;
				$obj = $f($this, $obj);
			}
			else {
				$f = rtl::method($info->value, "init");
				$obj = $f($this, $obj);
			}
			$obj = $this->chain($info->value, $obj);
			return $obj;
		}
		return null;
	}
	/**
	 * Returns helper
	 *
	 * @params string provider_name
	 * @return CoreStruct
	 */
	function getProvider($provider_name){
		if ($this->_providers_obj->has($provider_name)){
			return $this->_providers_obj->item($provider_name);
		}
		if ($this->_providers->has($provider_name)){
			$provider = $this->createProvider($provider_name);
			$this->_providers_obj->set($provider_name, $provider);
			return $provider;
		}
		return null;
	}
	/**
	 * Register module
	 */
	function registerModule($module_name){
		if ($this->_modules->indexOf($module_name) != -1){
			return ;
		}
		$module_description_class_name = rtl::toString($module_name) . ".ModuleDescription";
		if (!rtl::class_exists($module_description_class_name)){
			return $this;
		}
		$this->_modules->push($module_name);
		/* Register required Modules*/
		$modules = rtl::callStaticMethod($module_description_class_name, "requiredModules", (new Vector()));
		if ($modules != null){
			$keys = $modules->keys();
			$sz = $keys->count();
			for ($i = 0; $i < $sz; $i++){
				$module_name = $keys->item($i);
				$this->registerModule($module_name);
			}
		}
		$entities = rtl::callStaticMethod($module_description_class_name, "entities", (new Vector())->push($this));
		if ($entities != null){
			$this->_entities = $this->_entities->appendVector($entities);
		}
		rtl::callStaticMethod($module_description_class_name, "onRegister", (new Vector())->push($this));
		return $this;
	}
	/**
	 * Apply Lambda Chain
	 */
	function chain($filter_name, $obj = null){
		$entities = $this->_entities->filter(function ($item){
			return $item instanceof LambdaChain;
		});
		$entities = $entities->filter(function ($item) use (&$filter_name){
			return $item->name == $filter_name;
		});
		$entities = $entities->sortIm(function ($a, $b){
			return $a->pos > $b->pos;
		});
		for ($i = 0; $i < $entities->count(); $i++){
			$item = $entities->item($i);
			$f = $item->value;
			$obj = $f($this, $obj);
		}
		return $obj;
	}
	/**
	 * Read config
	 */
	function readConfig($config){
		$this->config = $config;
		/* Set base path */
		$runtime = $config->get("Runtime", null);
		if ($runtime != null && $runtime instanceof Dict){
			$base_path = $runtime->get("base_path", null, "string");
			if ($base_path != null){
				$this->base_path = $base_path;
			}
		}
		return $this;
		$args = new Vector();
		$args->push($this);
		$args->push($config);
		$sz = $this->_modules->count();
		for ($i = 0; $i < $sz; $i++){
			$module_name = $this->_modules->item($i);
			$module_description_class_name = rtl::toString($module_name) . ".ModuleDescription";
			rtl::callStaticMethod($module_description_class_name, "onReadConfig", $args);
		}
		return $this;
	}
	function getConfig(){
		return $this->config;
	}
	/**
	 * Init context
	 */
	function init(){
		/* Register providers */
		$providers = $this->_entities->filter(function ($item){
			return $item instanceof Provider;
		});
		for ($i = 0; $i < $providers->count(); $i++){
			$item = $providers->item($i);
			$this->_providers->set($item->name, $item);
		}
		/* Call onInitContext */
		$args = new Vector();
		$args->push($this);
		$sz = $this->_modules->count();
		for ($i = 0; $i < $sz; $i++){
			$module_name = $this->_modules->item($i);
			$module_description_class_name = rtl::toString($module_name) . ".ModuleDescription";
			rtl::callStaticMethod($module_description_class_name, "onInitContext", $args);
		}
		return $this;
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
		/* Add services */
		$this->_drivers->each(function ($key, $value) use (&$obj){
			$obj->_drivers->set($key, $value);
		});
		/* Add provider names */
		$this->_providers_names->each(function ($key, $value) use (&$obj){
			$obj->_providers_names->set($key, $value);
		});
		/* Add values */
		$this->_values->each(function ($key, $value) use (&$obj){
			$obj->_values->set($key, $value);
		});
		return $obj;
	}
	/**
	 * Realease context resources
	 */
	function release(){
	}
	/**
	 * Returns base path
	 * @return string
	 */
	function getBasePath(){
		return $this->base_path;
	}
	/**
	 * Call api
	 * @param string class_name
	 * @param string method_name
	 * @param ApiRequest request
	 * @return mixed The result of the api
	 */
	
	public function callApi($class_name, $interface_name, $method_name, $data)
	{
		$app = $this->getProvider("Core.Backend.BackendAppProvider");
		$app_class_name = $app->getClassName();
		$app_class_name = rtl::find_class($app_class_name);
		return call_user_func_array(
			[ $app_class_name, "callApi" ],
			[ $this, $app, $class_name, $interface_name, $method_name, $data ]
		);
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Context";}
	public static function getCurrentNamespace(){return "Runtime";}
	public static function getCurrentClassName(){return "Runtime.Context";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
	protected function _init(){
		parent::_init();
	}
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