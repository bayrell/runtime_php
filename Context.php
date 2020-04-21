<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2020 "Ildar Bikmamatov" <support@bayrell.org>
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
	public $__enviroments;
	public $__settings;
	public $__modules;
	public $__entities;
	public $__drivers;
	public $__providers;
	public $__tags;
	public $__initialized;
	public $__started;
	public $__start_time;
	public $__logs;
	/**
	 * Returns app name
	 * @return string
	 */
	static function appName($ctx)
	{
		return "";
	}
	/**
	 * Returns context settings
	 * @return Dict<string>
	 */
	static function getSettings($ctx, $env)
	{
		return null;
	}
	/**
	 * Extends entities
	 */
	static function getEntities($ctx, $entities)
	{
		return null;
	}
	/**
	 * Returns enviroment by eky
	 */
	static function env($ctx, $key, $def_value="")
	{
		return function ($ctx, $c) use (&$key,&$def_value)
		{
			return ($c->enviroments != null) ? $c->enviroments->get($ctx, $key, $def_value) : $def_value;
		};
	}
	/**
	 * Returns settings
	 * @return Dict<string>
	 */
	static function config($ctx, $items, $d=null)
	{
		return function ($ctx, $c) use (&$items,&$d)
		{
			if ($c->settings == null)
			{
				return null;
			}
			$config = $c->settings->get($ctx, "config", null);
			return ($config != null) ? \Runtime\rtl::attr($ctx, $config, $items, $d) : null;
		};
	}
	/**
	 * Returns docker secret key
	 */
	static function secret($ctx, $key)
	{
		return function ($ctx, $c) use (&$key)
		{
			$secrets = $c->settings->get($ctx, "secrets", null);
			return ($secrets != null) ? $secrets->get($ctx, "key", "") : "";
		};
	}
	/**
	 * Create context
	 *
	 * @params Dict env
	 * @params Collection<string> modules
	 * @params Dict settings
	 * @return Context
	 */
	static function create($ctx, $env, $entities=null)
	{
		$settings = static::getSettings($ctx, $env);
		/* Context data */
		$obj = \Runtime\Dict::from(["enviroments"=>$env,"settings"=>$settings,"modules"=>($settings != null) ? $settings->get($ctx, "modules", null) : null,"entities"=>$entities]);
		/* Create context */
		$ctx = static::newInstance($ctx, $obj);
		return $ctx;
	}
	/**
	 * Init context
	 */
	static function init($ctx, $c)
	{
		if ($c->initialized)
		{
			return $c;
		}
		/* Extends modules */
		$modules = static::getRequiredModules($ctx, $c->modules);
		/* Get modules entities */
		$entities = static::getEntitiesFromModules($ctx, $modules);
		$entities = $entities->prependCollectionIm($ctx, static::getEntities($ctx, $c->env));
		/* Base path */
		$base_path = ($c->base_path != "") ? $c->base_path : \Runtime\rtl::attr($ctx, $c->env, \Runtime\Collection::from(["BASE_PATH"]), "", "string");
		/* Add entities */
		if ($c->entities != null)
		{
			$entities = $entities->appendCollectionIm($ctx, $c->entities);
		}
		$c = $c->copy($ctx, ["entities"=>$entities]);
		/* Extend entities */
		$__v0 = new \Runtime\Monad($ctx, $c);
		$__v0 = $__v0->callMethod($ctx, "chain", \Runtime\Collection::from(["Runtime.Entities", \Runtime\Collection::from([$c,$entities])]));
		$entities = $__v0->value($ctx);
		$entities = static::extendEntities($ctx, $c, $entities);
		$entities = static::extendEntitiesFromAnnotations($ctx, $entities);
		/* Get providers */
		$providers = static::getProvidersFromEntities($ctx, $entities);
		/* Register drivers */
		$drivers = static::getDriversFromEntities($ctx, $entities);
		return $c->copy($ctx, \Runtime\Dict::from(["modules"=>$modules,"entities"=>$entities,"providers"=>$providers,"drivers"=>$drivers,"base_path"=>$base_path,"initialized"=>true]));
	}
	/**
	 * Start context
	 */
	static function start($ctx, $c)
	{
		if ($c->started)
		{
			return $c;
		}
		$drivers = $c->drivers->keys($ctx);
		for ($i = 0;$i < $drivers->count($ctx);$i++)
		{
			$driver_name = $drivers->item($ctx, $i);
			$driver = $c->drivers->item($ctx, $driver_name);
			$driver->startDriver($ctx);
		}
		return $c->copy($ctx, \Runtime\Dict::from(["started"=>true]));
	}
	/* ---------------------- Driver -------------------- */
	/**
	 * Get driver
	 *
	 * @params string driver_name
	 * @return Runtime.anager
	 */
	static function getDriver($ctx, $driver_name)
	{
		return function ($ctx, $c) use (&$driver_name)
		{
			if ($c->drivers->has($ctx, $driver_name))
			{
				return $c->drivers->item($ctx, $driver_name);
			}
			return null;
		};
	}
	/* --------------------- Provider ------------------- */
	/**
	 * Create provider
	 *
	 * @params string provider_name
	 * @return CoreProvider
	 */
	static function createProvider($ctx, $provider_name, $params, $settings_name="default")
	{
		return function ($ctx, $c) use (&$provider_name,&$params,&$settings_name)
		{
			$provider = null;
			if ($c->providers->has($ctx, $provider_name))
			{
				$info = $c->providers->item($ctx, $provider_name);
				if ($info->kind == "interface")
				{
					throw new \Runtime\Exceptions\RuntimeException($ctx, "Provider " . \Runtime\rtl::toStr($provider_name) . \Runtime\rtl::toStr(" does not declared"));
				}
				$class_name = $info->value;
				if ($class_name == "")
				{
					$class_name = $info->name;
				}
				/* Set default params */
				if ($params == null)
				{
					$params = \Runtime\rtl::attr($ctx, $c->settings, \Runtime\Collection::from(["providers",$class_name,$settings_name]));
				}
				if ($params == null)
				{
					$params = \Runtime\Dict::from([]);
				}
				$provider = \Runtime\rtl::newInstance($ctx, $class_name, \Runtime\Collection::from([$params]));
				$provider = static::chain($ctx, $c, $class_name, \Runtime\Collection::from([$provider]));
				if ($provider_name != $class_name)
				{
					$provider = static::chain($ctx, $c, $provider_name, \Runtime\Collection::from([$provider]));
				}
			}
			else
			{
				throw new \Runtime\Exceptions\RuntimeException($ctx, "Provider " . \Runtime\rtl::toStr($provider_name) . \Runtime\rtl::toStr(" not found"));
			}
			return $provider;
		};
	}
	/**
	 * Returns provider
	 *
	 * @params string provider_name
	 * @return CoreProvider
	 */
	static function getProvider($ctx, $provider_name, $settings_name="default")
	{
		return function ($ctx, $c) use (&$provider_name,&$settings_name)
		{
			$provider = static::createProvider($ctx, $c, $provider_name, null, $settings_name);
			return $provider;
		};
	}
	/* ---------------------- Chain --------------------- */
	/**
	 * Apply Lambda Chain
	 */
	static function chain($ctx, $chain_name, $args)
	{
		return function ($ctx, $c) use (&$chain_name,&$args)
		{
			$entities = $c->entities->filter($ctx, function ($ctx, $item) use (&$chain_name)
			{
				return $item instanceof \Runtime\Annotations\LambdaChain && $item->name == $chain_name && $item->is_async == false;
			});
			$entities = $entities->sortIm($ctx, function ($a, $b)
			{
				return $a->pos > $b->pos;
			});
			for ($i = 0;$i < $entities->count($ctx);$i++)
			{
				$item = $entities->item($ctx, $i);
				$item_chain_name = $item->chain;
				if ($item_chain_name != "")
				{
					$res = $c->chain($ctx, $item_chain_name, $args);
					$args = $args->setIm($ctx, $args->count($ctx) - 1, $res);
				}
				else
				{
					$arr = \Runtime\rs::split($ctx, "::", $item->value);
					$class_name = $arr->get($ctx, 0, "");
					$method_name = $arr->get($ctx, 1, "");
					$f = \Runtime\rtl::method($ctx, $class_name, $method_name);
					$res = \Runtime\rtl::apply($ctx, $f, $args);
					$args = $args->setIm($ctx, $args->count($ctx) - 1, $res);
				}
			}
			$res = $args->last($ctx);
			return $res;
		};
	}
	/**
	 * Apply Lambda Chain Await
	 */
	static function chainAwait($ctx, $chain_name, $args)
	{
		return function ($ctx, $c) use (&$chain_name,&$args)
		{
			$entities = $c->entities->filter($ctx, function ($ctx, $item) use (&$chain_name)
			{
				return $item instanceof \Runtime\Annotations\LambdaChain && $item->name == $chain_name;
			});
			$entities = $entities->sortIm($ctx, function ($a, $b)
			{
				return $a->pos > $b->pos;
			});
			for ($i = 0;$i < $entities->count($ctx);$i++)
			{
				$item = $entities->item($ctx, $i);
				$item_chain_name = $item->chain;
				if ($item_chain_name != "")
				{
					$res = static::chainAwait($ctx, $item_chain_name, $args);
					$args = $args->setIm($ctx, $args->count($ctx) - 1, $res);
				}
				else
				{
					$arr = \Runtime\rs::split($ctx, "::", $item->value);
					$class_name = $arr->get($ctx, 0, "");
					$method_name = $arr->get($ctx, 1, "");
					$f = \Runtime\rtl::method($ctx, $class_name, $method_name);
					if ($item->is_async)
					{
						$res = \Runtime\rtl::apply($ctx, $f, $args);
						$args = $args->setIm($ctx, $args->count($ctx) - 1, $res);
					}
					else
					{
						$res = \Runtime\rtl::apply($ctx, $f, $args);
						$args = $args->setIm($ctx, $args->count($ctx) - 1, $res);
					}
				}
			}
			$res = $args->last($ctx);
			return $res;
		};
	}
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params string space - message space
	 * @params Map params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	static function translate($ctx, $message, $space, $params=null, $locale="")
	{
		return function ($ctx, $c) use (&$message,&$space,&$params,&$locale)
		{
			return $message;
		};
	}
	/* ----------------------- Bus ---------------------- */
	/**
	 * Send message
	 * @param Message msg
	 * @param Context ctx
	 * @return Message
	 */
	static function sendMessage($ctx, $msg)
	{
		return function ($ctx, $c) use (&$msg)
		{
			$provider = static::getProvider($ctx, $c, \Runtime\RuntimeConstant::BUS_INTERFACE, "default");
			$msg = $provider::sendMessage($ctx, $provider, $msg);
			return $msg;
		};
	}
	/* ---------------------- Logs ---------------------- */
	/**
	 * Log message
	 * @param string message
	 * @param int loglevel
	 */
	static function debug($ctx, $message, $loglevel=0)
	{
		return function ($ctx, $c) use (&$message,&$loglevel)
		{
			$this->logs->push($ctx, $message . \Runtime\rtl::toStr("\n"));
		};
	}
	/**
	 * Timer message
	 * @param string message
	 * @param int loglevel
	 */
	static function log_timer($ctx, $message, $loglevel=0)
	{
		return function ($ctx, $c) use (&$message,&$loglevel)
		{
			$__v0 = new \Runtime\Monad($ctx, $c);
			$__v0 = $__v0->callMethod($ctx, "utime", \Runtime\Collection::from([]));
			$time = $__v0->value($ctx);
			$time = $time - $c->start_time;
			$s = "[" . \Runtime\rtl::toStr(\Runtime\rtl::round($ctx, $time * 1000)) . \Runtime\rtl::toStr("]ms ") . \Runtime\rtl::toStr($message) . \Runtime\rtl::toStr("\n");
			$c->logs->push($ctx, $s);
			/*if (isset($_GET['aaa']) && $_GET['aaa'] == 'bbb') var_dump($s);*/
		};
	}
	/**
	 * Dump var to log
	 * @param var v
	 * @param int loglevel
	 */
	static function dump($ctx, $v, $loglevel=0)
	{
		return function ($ctx, $c) use (&$v,&$loglevel)
		{
			ob_start();
			var_dump($v);
			$content = ob_get_contents();
			ob_end_clean();
			$this->logs->push($content);
		};
	}
	/**
	 * Append logs message
	 * @param Collection<string> logs
	 */
	static function logAppend($ctx, $logs)
	{
		return function ($ctx, $c) use (&$logs)
		{
			/*this.logs.appendVector(logs);*/
		};
	}
	/**
	 * Return logs
	 */
	static function getLogs($ctx)
	{
		return function ($ctx, $c)
		{
			/*return this.logs.toCollection();*/
			return \Runtime\Collection::from([]);
		};
	}
	/* ---------------------- Tags ---------------------- */
	/**
	 * Set tag
	 */
	static function setTagIm($ctx, $tag_name, $value)
	{
		return function ($ctx, $c) use (&$tag_name,&$value)
		{
			return $c->copy($ctx, \Runtime\Dict::from(["tags"=>$c->tags->setIm($ctx, $c, $tag_name, $value)]));
		};
	}
	/**
	 * Returns tag
	 */
	static function getTag($ctx, $tag_name)
	{
		return function ($ctx, $c) use (&$tag_name)
		{
			return $c->tags->get($ctx, $c, $tag_name, null);
		};
	}
	/* ---------------------- Other --------------------- */
	/**
	 * Returns unix timestamp
	 */
	static function time($ctx)
	{
		return function ($ctx, $c)
		{
			return time();
		};
	}
	/**
	 * Returns unix timestamp
	 */
	static function utime($ctx)
	{
		return function ($ctx, $c)
		{
			return microtime(true);
		};
	}
	/* -------------------- Functions ------------------- */
	/**
	 * Returns required modules
	 * @param string class_name
	 * @return Collection<string>
	 */
	static function _getRequiredModules($ctx, $res, $cache, $modules, $filter=null)
	{
		if ($modules == null)
		{
			return ;
		}
		if ($filter)
		{
			$modules = $modules->filter($ctx, $filter);
		}
		for ($i = 0;$i < $modules->count($ctx);$i++)
		{
			$module_name = $modules->item($ctx, $i);
			if ($cache->get($ctx, $module_name, false) == false)
			{
				$cache->set($ctx, $module_name, true);
				$f = \Runtime\rtl::method($ctx, $module_name . \Runtime\rtl::toStr(".ModuleDescription"), "requiredModules");
				$sub_modules = $f($ctx);
				if ($sub_modules != null)
				{
					$sub_modules = $sub_modules->keys($ctx);
					static::_getRequiredModules($ctx, $res, $cache, $sub_modules);
				}
				$res->push($ctx, $module_name);
			}
		}
	}
	/**
	 * Returns all modules
	 * @param Collection<string> modules
	 * @return Collection<string>
	 */
	static function getRequiredModules($ctx, $modules)
	{
		$res = new \Runtime\Vector($ctx);
		$cache = new \Runtime\Map($ctx);
		static::_getRequiredModules($ctx, $res, $cache, $modules);
		$res = $res->removeDublicatesIm($ctx);
		return $res->toCollection($ctx);
	}
	/**
	 * Returns modules entities
	 */
	static function getEntitiesFromModules($ctx, $modules)
	{
		$entities = new \Runtime\Vector($ctx);
		for ($i = 0;$i < $modules->count($ctx);$i++)
		{
			$module_class_name = $modules->item($ctx, $i) . \Runtime\rtl::toStr(".ModuleDescription");
			$f = \Runtime\rtl::method($ctx, $module_class_name, "entities");
			$arr = $f($ctx);
			$entities->appendVector($ctx, $arr);
		}
		return $entities->toCollection($ctx);
	}
	/**
	 * Extend entities
	 */
	static function extendEntitiesFromAnnotations($ctx, $entities)
	{
		$e = $entities->toVector($ctx);
		for ($i = 0;$i < $entities->count($ctx);$i++)
		{
			$item1 = $entities->item($ctx, $i);
			$item1_class_name = $item1->getClassName($ctx);
			if ($item1_class_name == "Runtime.Annotations.Entity")
			{
				$class_name = ($item1->value != "") ? $item1->value : $item1->name;
				$info = \Runtime\RuntimeUtils::getClassIntrospection($ctx, $class_name);
				if ($info != null && $info->class_info)
				{
					for ($j = 0;$j < $info->class_info->count($ctx);$j++)
					{
						$item2 = $info->class_info->item($ctx, $j);
						$item2_class_name = $item2->getClassName($ctx);
						if ($item2 instanceof \Runtime\Annotations\Entity && $item2_class_name != "Runtime.Annotations.Entity")
						{
							$item2 = $item2->copy($ctx, \Runtime\Dict::from(["name"=>$class_name]));
							$e->push($ctx, $item2);
						}
					}
				}
			}
		}
		return $e->toCollection($ctx);
	}
	/**
	 * Returns providers from entities
	 */
	static function getProvidersFromEntities($ctx, $entities)
	{
		$arr = $entities->filter($ctx, function ($ctx, $item)
		{
			return $item instanceof \Runtime\Annotations\Provider;
		});
		$providers = new \Runtime\Map($ctx);
		for ($i = 0;$i < $arr->count($ctx);$i++)
		{
			$item = $arr->item($ctx, $i);
			$providers->set($ctx, $item->name, $item);
		}
		return $providers->toDict($ctx);
	}
	/**
	 * Register drivers
	 */
	static function getDriversFromEntities($ctx, $entities)
	{
		$arr = $entities->filter($ctx, function ($ctx, $item)
		{
			return $item instanceof \Runtime\Annotations\Driver;
		});
		$drivers = new \Runtime\Map($ctx);
		for ($i = 0;$i < $arr->count($ctx);$i++)
		{
			$item = $arr->item($ctx, $i);
			$driver_name = $item->name;
			$class_name = $item->value;
			if ($class_name == "")
			{
				$class_name = $item->name;
			}
			$driver = \Runtime\rtl::newInstance($ctx, $class_name, \Runtime\Collection::from([]));
			$driver = static::chain($ctx, $class_name, \Runtime\Collection::from([$driver]));
			if ($class_name != $driver_name)
			{
				$driver = static::chain($ctx, $driver_name, \Runtime\Collection::from([$driver]));
			}
			$drivers->set($ctx, $item->name, $driver);
		}
		return $drivers->toDict($ctx);
	}
	/**
	 * Extends entities
	 */
	static function extendEntities($ctx, $c, $entities)
	{
		return $entities;
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
		$this->__base_path = null;
		$this->__enviroments = null;
		$this->__settings = null;
		$this->__modules = null;
		$this->__entities = null;
		$this->__drivers = null;
		$this->__providers = null;
		$this->__tags = null;
		$this->__initialized = false;
		$this->__started = false;
		$this->__start_time = 0;
		$this->__logs = new \Runtime\Vector($ctx);
	}
	function assignObject($ctx,$o)
	{
		if ($o instanceof \Runtime\Context)
		{
			$this->__base_path = $o->__base_path;
			$this->__enviroments = $o->__enviroments;
			$this->__settings = $o->__settings;
			$this->__modules = $o->__modules;
			$this->__entities = $o->__entities;
			$this->__drivers = $o->__drivers;
			$this->__providers = $o->__providers;
			$this->__tags = $o->__tags;
			$this->__initialized = $o->__initialized;
			$this->__started = $o->__started;
			$this->__start_time = $o->__start_time;
			$this->__logs = $o->__logs;
		}
		parent::assignObject($ctx,$o);
	}
	function assignValue($ctx,$k,$v)
	{
		if ($k == "base_path")$this->__base_path = $v;
		else if ($k == "enviroments")$this->__enviroments = $v;
		else if ($k == "settings")$this->__settings = $v;
		else if ($k == "modules")$this->__modules = $v;
		else if ($k == "entities")$this->__entities = $v;
		else if ($k == "drivers")$this->__drivers = $v;
		else if ($k == "providers")$this->__providers = $v;
		else if ($k == "tags")$this->__tags = $v;
		else if ($k == "initialized")$this->__initialized = $v;
		else if ($k == "started")$this->__started = $v;
		else if ($k == "start_time")$this->__start_time = $v;
		else if ($k == "logs")$this->__logs = $v;
		else parent::assignValue($ctx,$k,$v);
	}
	function takeValue($ctx,$k,$d=null)
	{
		if ($k == "base_path")return $this->__base_path;
		else if ($k == "enviroments")return $this->__enviroments;
		else if ($k == "settings")return $this->__settings;
		else if ($k == "modules")return $this->__modules;
		else if ($k == "entities")return $this->__entities;
		else if ($k == "drivers")return $this->__drivers;
		else if ($k == "providers")return $this->__providers;
		else if ($k == "tags")return $this->__tags;
		else if ($k == "initialized")return $this->__initialized;
		else if ($k == "started")return $this->__started;
		else if ($k == "start_time")return $this->__start_time;
		else if ($k == "logs")return $this->__logs;
		return parent::takeValue($ctx,$k,$d);
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
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Context",
			"name"=>"Runtime.Context",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "base_path";
			$a[] = "enviroments";
			$a[] = "settings";
			$a[] = "modules";
			$a[] = "entities";
			$a[] = "drivers";
			$a[] = "providers";
			$a[] = "tags";
			$a[] = "initialized";
			$a[] = "started";
			$a[] = "start_time";
			$a[] = "logs";
		}
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "base_path") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "enviroments") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "settings") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "modules") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "entities") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "drivers") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "providers") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "tags") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "initialized") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "started") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "start_time") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "logs") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Context",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		return null;
	}
	static function getMethodsList($ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}