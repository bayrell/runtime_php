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
class Context extends \Runtime\CoreStruct
{
	public $__base_path;
	public $__env;
	public $__settings;
	public $__modules;
	public $__entities;
	public $__drivers;
	public $__providers;
	public $__tags;
	public $__initialized;
	public $__started;
	/**
	 * Returns app name
	 * @return string
	 */
	static function appName()
	{
		return "";
	}
	/**
	 * Returns required modules
	 * @return Dict<string>
	 */
	static function getModules($env)
	{
		return null;
	}
	/**
	 * Extend entities
	 */
	static function getEntities($env)
	{
		return null;
	}
	/**
	 * Returns settings
	 * @return Dict<string>
	 */
	static function getSettings($env)
	{
		return null;
	}
	/**
	 * Create context
	 *
	 * @params Dict env
	 * @params Collection<string> modules
	 * @params Dict settings
	 * @return Context
	 */
	static function create($env, $modules=null, $settings=null)
	{
		/* Get modules */
		if ($modules == null)
		{
			$m = static::getModules($env);
			$modules = ($m != null) ? $m->keys(null) : \Runtime\Collection::from([]);
		}
		/* Get settings */
		if ($settings == null)
		{
			$settings = static::getSettings($env);
		}
		/* Extends modules */
		$modules = static::getRequiredModules($modules);
		/* Get modules entities */
		$entities = static::getModulesEntities($modules);
		$entities = $entities->prependCollectionIm(null, static::getEntities($env));
		/* Base path */
		$base_path = \Runtime\rtl::attr(null, $settings, \Runtime\Collection::from(["base_path"]), "", "string");
		if ($base_path == "")
		{
			$base_path = \Runtime\rtl::attr(null, $env, \Runtime\Collection::from(["BASE_PATH"]), "", "string");
		}
		/* Context data */
		$obj = \Runtime\Dict::from(["env"=>$env,"settings"=>$settings,"modules"=>$modules,"entities"=>$entities,"base_path"=>$base_path]);
		/* Create context */
		$context = static::newInstance(null, $obj);
		return $context;
	}
	/**
	 * Returns required modules
	 * @param string class_name
	 * @return Collection<string>
	 */
	static function _getRequiredModules($res, $cache, $modules, $filter=null)
	{
		if ($modules == null)
		{
			return ;
		}
		if ($filter)
		{
			$modules = $modules->filter(null, $filter);
		}
		for ($i = 0;$i < $modules->count(null);$i++)
		{
			$module_name = $modules->item(null, $i);
			if ($cache->get(null, $module_name, false) == false)
			{
				$cache->set(null, $module_name, true);
				$f = \Runtime\rtl::method(null, $module_name . \Runtime\rtl::toStr(".ModuleDescription"), "requiredModules");
				$sub_modules = $f(null);
				if ($sub_modules != null)
				{
					$sub_modules = $sub_modules->keys(null);
					static::_getRequiredModules($res, $cache, $sub_modules);
				}
				$res->push(null, $module_name);
			}
		}
	}
	/**
	 * Returns all modules
	 * @param Collection<string> modules
	 * @return Collection<string>
	 */
	static function getRequiredModules($modules)
	{
		$res = new \Runtime\Vector(null);
		$cache = new \Runtime\Map(null);
		static::_getRequiredModules($res, $cache, $modules);
		$res = $res->removeDublicatesIm(null);
		return $res->toCollection(null);
	}
	/**
	 * Returns modules entities
	 */
	static function getModulesEntities($modules)
	{
		$entities = new \Runtime\Vector(null);
		for ($i = 0;$i < $modules->count(null);$i++)
		{
			$module_class_name = $modules->item(null, $i) . \Runtime\rtl::toStr(".ModuleDescription");
			$f = \Runtime\rtl::method(null, $module_class_name, "entities");
			$arr = $f(null);
			$entities->appendVector(null, $arr);
		}
		return $entities->toCollection(null);
	}
	/**
	 * Extend entities
	 */
	static function extendEntities($entities, $context)
	{
		$e = $entities->toVector($context);
		for ($i = 0;$i < $entities->count($context);$i++)
		{
			$item1 = $entities->item($context, $i);
			$item1_class_name = $item1->getClassName();
			if ($item1_class_name == "Runtime.Annotations.Entity")
			{
				$class_name = ($item1->value != "") ? $item1->value : $item1->name;
				$info = \Runtime\RuntimeUtils::getClassIntrospection($context, $class_name);
				if ($info->class_info)
				{
					for ($j = 0;$j < $info->class_info->count($context);$j++)
					{
						$item2 = $info->class_info->item($context, $j);
						$item2_class_name = $item2->getClassName();
						if ($item2 instanceof \Runtime\Annotations\Entity && $item2_class_name != "Runtime.Annotations.Entity")
						{
							$item2 = $item2->copy($context, \Runtime\Dict::from(["name"=>$class_name]));
							$e->push($context, $item2);
						}
					}
				}
			}
		}
		return $e->toCollection($context);
	}
	/**
	 * Init context
	 */
	static function init($context)
	{
		if ($context->initialized)
		{
			return $context;
		}
		$entities = $context->entities;
		/* Extend entities */
		$entities = static::extendEntities($entities, $context);
		$entities = static::chain("Runtime.Entities", \Runtime\Collection::from([$context,$entities]), $context);
		/* Get providers */
		$providers = static::getProvidersFromEntities($context);
		/* Register drivers */
		$drivers = static::getDriversFromEntities($context);
		return $context->copy($context, \Runtime\Dict::from(["entities"=>$entities,"providers"=>$providers,"drivers"=>$drivers,"initialized"=>true]));
	}
	/**
	 * Start context
	 */
	static function start($context)
	{
		if ($context->started)
		{
			return $context;
		}
		$drivers = $context->drivers->keys($context);
		for ($i = 0;$i < $drivers->count($context);$i++)
		{
			$driver_name = $drivers->item($context, $i);
			$driver = $context->drivers->item($context, $driver_name);
			$driver->startDriver($context);
		}
		return $context->copy($context, \Runtime\Dict::from(["started"=>true]));
	}
	/**
	 * Returns providers from entities
	 */
	static function getProvidersFromEntities($context)
	{
		$arr = $context->entities->filter($context, function ($__ctx, $item)
		{
			return $item instanceof \Runtime\Annotations\Provider;
		});
		$providers = new \Runtime\Map($context);
		for ($i = 0;$i < $arr->count($context);$i++)
		{
			$item = $arr->item($context, $i);
			$providers->set($context, $item->name, $item);
		}
		return $providers->toDict($context);
	}
	/**
	 * Register drivers
	 */
	static function getDriversFromEntities($context)
	{
		$arr = $context->entities->filter($context, function ($__ctx, $item)
		{
			return $item instanceof \Runtime\Annotations\Driver;
		});
		$drivers = new \Runtime\Map($context);
		for ($i = 0;$i < $arr->count($context);$i++)
		{
			$item = $arr->item($context, $i);
			$driver_name = $item->name;
			$class_name = $item->value;
			if ($class_name == "")
			{
				$class_name = $item->name;
			}
			$driver = \Runtime\rtl::newInstance($context, $class_name, \Runtime\Collection::from([]));
			$driver = static::chain($context, $class_name, \Runtime\Collection::from([$context,$driver]));
			if ($class_name != $driver_name)
			{
				$driver = static::chain($context, $driver_name, \Runtime\Collection::from([$context,$driver]));
			}
			$drivers->set($context, $item->name, $driver);
		}
		return $drivers->toDict($context);
	}
	/* ---------------------- Driver -------------------- */
	/**
	 * Get driver
	 *
	 * @params string driver_name
	 * @return Runtime.anager
	 */
	static function getDriver($context, $driver_name)
	{
		if ($context->drivers->has($context, $driver_name))
		{
			return $context->drivers->item($context, $driver_name);
		}
		return null;
	}
	/* --------------------- Provider ------------------- */
	/**
	 * Create provider
	 *
	 * @params string provider_name
	 * @return CoreProvider
	 */
	static function createProvider($provider_name, $params=null, $context)
	{
		if ($params == null)
		{
			$params = \Runtime\Dict::from([]);
		}
		$provider = null;
		if ($context->providers->has($context, $provider_name))
		{
			$info = $context->providers->item($context, $provider_name);
			if ($info->kind == "interface")
			{
				throw new \Runtime\Exceptions\RuntimeException($context, "Provider " . \Runtime\rtl::toStr($provider_name) . \Runtime\rtl::toStr(" does not declared"));
			}
			$class_name = $info->value;
			if ($class_name == "")
			{
				$class_name = $info->name;
			}
			$provider = \Runtime\rtl::newInstance($context, $class_name, \Runtime\Collection::from([$params]));
			$provider = static::chain($context, $class_name, \Runtime\Collection::from([$context,$provider]));
			if ($provider_name != $class_name)
			{
				$provider = static::chain($context, $provider_name, \Runtime\Collection::from([$context,$provider]));
			}
		}
		else
		{
			throw new \Runtime\Exceptions\RuntimeException($context, "Provider " . \Runtime\rtl::toStr($provider_name) . \Runtime\rtl::toStr(" not found"));
		}
		return $provider;
	}
	/**
	 * Returns provider
	 *
	 * @params string provider_name
	 * @return CoreProvider
	 */
	static function getProvider($provider_name, $settings_name="default", $context)
	{
		$params = \Runtime\rtl::attr($context, $context->settings, \Runtime\Collection::from(["providers",$provider_name,$settings_name]));
		$provider = static::createProvider($provider_name, $params, $context);
		return $provider;
	}
	/* ---------------------- Chain --------------------- */
	/**
	 * Apply Lambda Chain
	 */
	static function chain($chain_name, $args, $context)
	{
		$entities = $context->entities->filter($context, function ($__ctx, $item) use (&$chain_name)
		{
			return $item instanceof \Runtime\Annotations\LambdaChain && $item->name == $chain_name && $item->is_async == false;
		});
		$entities = $entities->sortIm($context, function ($a, $b)
		{
			return $a->pos > $b->pos;
		});
		for ($i = 0;$i < $entities->count($context);$i++)
		{
			$item = $entities->item($context, $i);
			$item_chain_name = $item->chain;
			if ($item_chain_name != "")
			{
				$res = static::chain($item_chain_name, $args, $context);
				$args = $args->setIm($context, $args->count($context) - 1, $res);
			}
			else
			{
				$arr = \Runtime\rs::split($context, "::", $item->value);
				$class_name = $arr->get($context, 0, "");
				$method_name = $arr->get($context, 1, "");
				$f = \Runtime\rtl::method($context, $class_name, $method_name);
				$res = \Runtime\rtl::apply($context, $f, $args);
				$args = $args->setIm($context, $args->count($context) - 1, $res);
			}
		}
		$res = $args->last($context);
		return $res;
	}
	/**
	 * Apply Lambda Chain Await
	 */
	static function chainAwait($chain_name, $args, $context)
	{
		$entities = $context->entities->filter($context, function ($__ctx, $item) use (&$chain_name)
		{
			return $item instanceof \Runtime\Annotations\LambdaChain && $item->name == $chain_name;
		});
		$entities = $entities->sortIm($context, function ($a, $b)
		{
			return $a->pos > $b->pos;
		});
		for ($i = 0;$i < $entities->count($context);$i++)
		{
			$item = $entities->item($context, $i);
			$item_chain_name = $item->chain;
			if ($item_chain_name != "")
			{
				$res = static::chainAwait($item_chain_name, $args, $context);
				$args = $args->setIm($context, $args->count($context) - 1, $res);
			}
			else
			{
				$arr = \Runtime\rs::split($context, "::", $item->value);
				$class_name = $arr->get($context, 0, "");
				$method_name = $arr->get($context, 1, "");
				$f = \Runtime\rtl::method($context, $class_name, $method_name);
				if ($item->is_async)
				{
					$res = \Runtime\rtl::apply($context, $f, $args);
					$args = $args->setIm($context, $args->count($context) - 1, $res);
				}
				else
				{
					$res = \Runtime\rtl::apply($context, $f, $args);
					$args = $args->setIm($context, $args->count($context) - 1, $res);
				}
			}
		}
		$res = $args->last($context);
		return $res;
	}
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params Map params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	function translate($message, $params=null, $locale="")
	{
		return $message;
	}
	/* ----------------------- Bus ---------------------- */
	/**
	 * Local bus call
	 * @param string class_name
	 * @param string method_name
	 * @param ApiRequest request
	 * @return var The result of the api
	 */
	function busCall($class_name, $interface_name, $method_name, $data)
	{
		/*BusInterface provider = static::getProvider(this, "Runtime.Interfaces.LocalBusInterface");
		BusResult res = await provider::call(provider, this, class_name, interface_name, method_name, data);
		return res;*/
	}
	/**
	 * Local bus call
	 * @param string class_name
	 * @param string method_name
	 * @param ApiRequest request
	 * @return var The result of the api
	 */
	function busCallRoute($url, $data)
	{
		/*BusInterface provider = this.getProvider("Runtime.Interfaces.LocalBusInterface");
		BusResult res = await provider::callRoute(provider, this, url, data);
		return res;*/
	}
	/* ---------------------- Logs ---------------------- */
	/**
	 * Log message
	 * @param string message
	 * @param int loglevel
	 */
	function log($message, $loglevel=0)
	{
		/*this.logs.push(message ~ "\n");*/
	}
	/**
	 * Dump var to log
	 * @param var v
	 * @param int loglevel
	 */
	function dump($v, $loglevel=0)
	{
		ob_start();
		var_dump($v);
		$content = ob_get_contents();
		ob_end_clean();
		/*$this->logs->push($content);*/
	}
	/**
	 * Append logs message
	 * @param Collection<string> logs
	 */
	function logAppend($logs)
	{
		/*this.logs.appendVector(logs);*/
	}
	/**
	 * Return logs
	 */
	function getLogs()
	{
		/*return this.logs.toCollection();*/
		return \Runtime\Collection::from([]);
	}
	/* ---------------------- Tags ---------------------- */
	/**
	 * Set tag
	 */
	function setTagIm($tag_name, $value)
	{
		return $this->copy(\Runtime\Dict::from(["tags"=>$this->_tags->setIm($this, $tag_name, $value)]));
	}
	/**
	 * Returns tag
	 */
	function getTag($tag_name)
	{
		return $this->_tags->get($this, $tag_name, null);
	}
	/* ---------------------- Other --------------------- */
	/**
	 * Returns unix timestamp
	 */
	function time()
	{
		return time();
	}
	/* ======================= Class Init Functions ======================= */
	function _init($__ctx)
	{
		parent::_init($__ctx);
		$this->__base_path = null;
		$this->__env = null;
		$this->__settings = null;
		$this->__modules = null;
		$this->__entities = null;
		$this->__drivers = null;
		$this->__providers = null;
		$this->__tags = null;
		$this->__initialized = false;
		$this->__started = false;
	}
	function assignObject($__ctx,$o)
	{
		if ($o instanceof \Runtime\Context)
		{
			$this->__base_path = $o->__base_path;
			$this->__env = $o->__env;
			$this->__settings = $o->__settings;
			$this->__modules = $o->__modules;
			$this->__entities = $o->__entities;
			$this->__drivers = $o->__drivers;
			$this->__providers = $o->__providers;
			$this->__tags = $o->__tags;
			$this->__initialized = $o->__initialized;
			$this->__started = $o->__started;
		}
		parent::assignObject($__ctx,$o);
	}
	function assignValue($__ctx,$k,$v)
	{
		if ($k == "base_path")$this->__base_path = $v;
		else if ($k == "env")$this->__env = $v;
		else if ($k == "settings")$this->__settings = $v;
		else if ($k == "modules")$this->__modules = $v;
		else if ($k == "entities")$this->__entities = $v;
		else if ($k == "drivers")$this->__drivers = $v;
		else if ($k == "providers")$this->__providers = $v;
		else if ($k == "tags")$this->__tags = $v;
		else if ($k == "initialized")$this->__initialized = $v;
		else if ($k == "started")$this->__started = $v;
		else parent::assignValue($__ctx,$k,$v);
	}
	function takeValue($__ctx,$k,$d=null)
	{
		if ($k == "base_path")return $this->__base_path;
		else if ($k == "env")return $this->__env;
		else if ($k == "settings")return $this->__settings;
		else if ($k == "modules")return $this->__modules;
		else if ($k == "entities")return $this->__entities;
		else if ($k == "drivers")return $this->__drivers;
		else if ($k == "providers")return $this->__providers;
		else if ($k == "tags")return $this->__tags;
		else if ($k == "initialized")return $this->__initialized;
		else if ($k == "started")return $this->__started;
		return parent::takeValue($__ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.Context";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Context";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreStruct";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Context",
			"name"=>"Runtime.Context",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "base_path";
			$a[] = "env";
			$a[] = "settings";
			$a[] = "modules";
			$a[] = "entities";
			$a[] = "drivers";
			$a[] = "providers";
			$a[] = "tags";
			$a[] = "initialized";
			$a[] = "started";
		}
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