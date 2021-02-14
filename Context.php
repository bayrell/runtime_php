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
class Context extends \Runtime\BaseStruct
{
	public $__base_path;
	public $__enviroments;
	public $__settings;
	public $__modules;
	public $__entities;
	public $__cli_args;
	public $__drivers;
	public $__initialized;
	public $__started;
	public $__start_time;
	public $__tz;
	public $__app_name;
	public $__entry_point;
	public $__main_module;
	public $__main_class;
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
	function env($ctx, $key, $def_value="")
	{
		$__v0 = new \Runtime\Monad($ctx, $this);
		$__v0 = $__v0->attr($ctx, "enviroments");
		$__v0 = $__v0->call($ctx, \Runtime\lib::get($ctx, $key, $def_value));
		return $__v0->value($ctx);
	}
	/**
	 * Returns settings
	 * @return Dict<string>
	 */
	function config($ctx, $items, $d=null)
	{
		$__v0 = new \Runtime\Monad($ctx, $this);
		$__v0 = $__v0->attr($ctx, "settings");
		$__v0 = $__v0->call($ctx, \Runtime\lib::get($ctx, "config", null));
		$__v0 = $__v0->call($ctx, \Runtime\lib::attr($ctx, $items, $d));
		return $__v0->value($ctx);
	}
	/**
	 * Returns docker secret key
	 */
	function secret($ctx, $key)
	{
		$__v0 = new \Runtime\Monad($ctx, $this);
		$__v0 = $__v0->attr($ctx, "settings");
		$__v0 = $__v0->call($ctx, \Runtime\lib::get($ctx, "secrets", null));
		$__v0 = $__v0->call($ctx, \Runtime\lib::get($ctx, $key, ""));
		return $__v0->value($ctx);
	}
	/**
	 * Create context
	 *
	 * @params Dict env
	 * @params Collection<string> modules
	 * @params Dict settings
	 * @return Context
	 */
	static function create($ctx, $env=null)
	{
		$settings = \Runtime\Dict::from([]);
		/* Context data */
		$obj = \Runtime\Dict::from(["enviroments"=>$env,"settings"=>$settings,"modules"=>\Runtime\Collection::from([])]);
		/* Create context */
		$ctx = static::newInstance($ctx, $obj);
		return $ctx;
	}
	/**
	 * Set main module
	 */
	static function setMainModule($ctx, $c, $main_module)
	{
		$settings = \Runtime\Dict::from([]);
		$main_module_class_name = "";
		/* Get settings */
		if ($main_module)
		{
			$main_module_class_name = $main_module . \Runtime\rtl::toStr(".ModuleDescription");
			if (\Runtime\rtl::method_exists($ctx, $main_module_class_name, "appSettings"))
			{
				$f = \Runtime\rtl::method($ctx, $main_module_class_name, "appSettings");
				$settings = $f($ctx, $c->enviroments);
			}
		}
		/* Add main module */
		if ($main_module)
		{
			$c = \Runtime\rtl::setAttr($ctx, $c, ["modules"], $c->modules->pushIm($ctx, $main_module));
		}
		/* Set main module */
		$c = \Runtime\rtl::setAttr($ctx, $c, ["main_module"], $main_module);
		$c = \Runtime\rtl::setAttr($ctx, $c, ["main_class"], $main_module_class_name);
		/* Set entry point */
		$c = \Runtime\rtl::setAttr($ctx, $c, ["entry_point"], $main_module_class_name);
		/* Set new settings */
		$c = \Runtime\rtl::setAttr($ctx, $c, ["settings"], $settings);
		return $c;
	}
	/**
	 * Set app name
	 */
	static function setAppName($ctx, $c, $app_name)
	{
		return $c->copy($ctx, \Runtime\Dict::from(["app_name"=>$app_name]));
	}
	/**
	 * Set main class
	 */
	static function setMainClass($ctx, $c, $main_class)
	{
		return $c->copy($ctx, \Runtime\Dict::from(["main_class"=>$main_class,"entry_point"=>$main_class]));
	}
	/**
	 * Set entry point
	 */
	static function setEntryPoint($ctx, $c, $entry_point)
	{
		return $c->copy($ctx, \Runtime\Dict::from(["entry_point"=>$entry_point]));
	}
	/**
	 * Init context
	 */
	static function appInit($ctx, $c)
	{
		$ctx = $c;
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
		$base_path = ($c->base_path != "") ? ($c->base_path) : (\Runtime\rtl::attr($ctx, $c->env, \Runtime\Collection::from(["BASE_PATH"]), "", "string"));
		/* Add entities */
		if ($c->entities != null)
		{
			$entities = $entities->appendCollectionIm($ctx, $c->entities);
		}
		$c = \Runtime\rtl::setAttr($ctx, $c, ["entities"], $entities);
		/* Extend entities */
		$__v0 = new \Runtime\Monad($ctx, $c->chain($ctx, "Runtime.Entities", \Runtime\Collection::from([$c,$entities])));
		$__v0 = $__v0->attr($ctx, 1);
		$entities = $__v0->value($ctx);
		$entities = static::extendEntities($ctx, $c, $entities);
		$entities = static::getRequiredEntities($ctx, $entities);
		/* Add lambda chains */
		$entities = $entities->concat($ctx, static::getSubEntities($ctx, $entities, "Runtime.LambdaChainClass", "Runtime.LambdaChain"));
		return $c->copy($ctx, \Runtime\Dict::from(["modules"=>$modules,"entities"=>$entities,"base_path"=>$base_path,"initialized"=>true]));
	}
	/**
	 * Start context
	 */
	static function appStart($ctx, $c)
	{
		$ctx = $c;
		if ($c->started)
		{
			return $c;
		}
		/* Get drivers from entity */
		$drivers = $c->entities->filter($ctx, function ($ctx, $item)
		{
			return $item instanceof \Runtime\Driver;
		});
		/* Create drivers */
		for ($i = 0;$i < $drivers->count($ctx);$i++)
		{
			$driver_entity = $drivers->item($ctx, $i);
			$driver_name = $driver_entity->name;
			$class_name = $driver_entity->value;
			if ($class_name == "")
			{
				$class_name = $driver_entity->name;
			}
			$driver = \Runtime\rtl::newInstance($ctx, $class_name, \Runtime\Collection::from([$driver_name,$driver_entity]));
			$__v0 = new \Runtime\Monad($ctx, $ctx->chain($ctx, $class_name, \Runtime\Collection::from([$driver])));
			$__v0 = $__v0->attr($ctx, 0);
			$driver = $__v0->value($ctx);
			if ($class_name != $driver_name)
			{
				$__v0 = new \Runtime\Monad($ctx, $ctx->chain($ctx, $driver_name, \Runtime\Collection::from([$driver])));
				$__v0 = $__v0->attr($ctx, 0);
				$driver = $__v0->value($ctx);
			}
			if ($driver == null)
			{
				throw new \Runtime\Exceptions\RuntimeException($ctx, "Driver '" . \Runtime\rtl::toStr($class_name) . \Runtime\rtl::toStr("' not found"));
			}
			$c->drivers->setValue($ctx, $driver_name, $driver);
		}
		/* Start drivers */
		$keys = $c->drivers->keys($ctx);
		for ($i = 0;$i < $keys->count($ctx);$i++)
		{
			$driver_name = \Runtime\rtl::get($ctx, $keys, $i);
			$driver = \Runtime\rtl::get($ctx, $c->drivers, $driver_name);
			$driver->startDriver($ctx);
		}
		return $c->copy($ctx, \Runtime\Dict::from(["started"=>true]));
	}
	/**
	 * Init
	 */
	static function init($ctx, $c)
	{
		$main_class = $c->main_class;
		/* Init app */
		if ($main_class != "" && \Runtime\rtl::method_exists($ctx, $main_class, "appInit"))
		{
			$appInit = \Runtime\rtl::method($ctx, $main_class, "appInit");
			$c = $appInit($ctx, $c);
		}
		else
		{
			$c = $c::appInit($ctx, $c);
		}
		return $c;
	}
	/**
	 * Start
	 */
	static function start($ctx, $c)
	{
		$main_class = $c->main_class;
		/* Start app */
		if ($main_class != "" && \Runtime\rtl::method_exists($ctx, $main_class, "appStart"))
		{
			$appStart = \Runtime\rtl::method($ctx, $main_class, "appStart");
			$c = $appStart($ctx, $c);
		}
		else
		{
			$c = $c::appStart($ctx, $c);
		}
		return $c;
	}
	/**
	 * Run entry point
	 */
	static function run($ctx, $c)
	{
		$ctx = $c;
		$entry_point = $c->entry_point;
		/* Run entrypoint */
		if ($entry_point != "")
		{
			$appRun = \Runtime\rtl::method($ctx, $entry_point, "appRun");
			$appRun($c);
		}
		return $c;
	}
	/**
	 * Add driver
	 */
	function addDriver($ctx, $obj)
	{
		$this->drivers->setValue($ctx, $obj->getObjectName($ctx), $obj);
		return $this;
	}
	/**
	 * Add driver
	 */
	function getDriver($ctx, $name)
	{
		return $this->drivers->get($ctx, $name, null);
	}
	/* ---------------------- Chain --------------------- */
	/**
	 * Apply Lambda Chain
	 */
	function chain($ctx, $chain_name, $args)
	{
		$entities = $this->entities->filter($ctx, function ($ctx, $item) use (&$chain_name)
		{
			return $item instanceof \Runtime\LambdaChain && $item->name == $chain_name && $item->is_async == false;
		});
		$entities = $entities->sortIm($ctx, function ($ctx, $a, $b)
		{
			return $a->pos > $b->pos;
		});
		for ($i = 0;$i < $entities->count($ctx);$i++)
		{
			$item = $entities->item($ctx, $i);
			$item_chain_name = $item->chain;
			if ($item_chain_name != "")
			{
				$args = $this->chain($ctx, $item_chain_name, $args);
			}
			else
			{
				$arr = \Runtime\rs::split($ctx, "::", $item->value);
				$class_name = $arr->get($ctx, 0, "");
				$method_name = $arr->get($ctx, 1, "");
				$f = \Runtime\rtl::method($ctx, $class_name, $method_name);
				$args = \Runtime\rtl::apply($ctx, $f, $args);
			}
		}
		return $args;
	}
	/**
	 * Apply Lambda Chain Await
	 */
	function chainAsync($ctx, $chain_name, $args)
	{
		$entities = $this->entities->filter($ctx, function ($ctx, $item) use (&$chain_name)
		{
			return $item instanceof \Runtime\LambdaChain && $item->name == $chain_name;
		});
		$entities = $entities->sortIm($ctx, function ($ctx, $a, $b)
		{
			return $a->pos > $b->pos;
		});
		for ($i = 0;$i < $entities->count($ctx);$i++)
		{
			$item = $entities->item($ctx, $i);
			$item_chain_name = $item->chain;
			if ($item_chain_name != "")
			{
				$args = $this->chainAsync($ctx, $item_chain_name, $args);
			}
			else
			{
				$arr = \Runtime\rs::split($ctx, "::", $item->value);
				$class_name = $arr->get($ctx, 0, "");
				$method_name = $arr->get($ctx, 1, "");
				$f = \Runtime\rtl::method($ctx, $class_name, $method_name);
				if ($item->is_async)
				{
					$args = \Runtime\rtl::applyAsync($ctx, $f, $args);
				}
				else
				{
					$args = \Runtime\rtl::apply($ctx, $f, $args);
				}
			}
		}
		return $args;
	}
	/**
	 * Translate message
	 * @params string space - message space
	 * @params string message - message need to be translated
	 * @params Map params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	function translate($ctx, $space, $message, $params=null, $locale="")
	{
		$message = ($params == null) ? ($message) : ($params->reduce($ctx, function ($ctx, $message, $value, $key)
		{
			return \Runtime\rs::replace($ctx, "%" . \Runtime\rtl::toStr($key) . \Runtime\rtl::toStr("%"), $value, $message);
		}, $message));
		return $message;
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
				$cache->setValue($ctx, $module_name, true);
				$f = \Runtime\rtl::method($ctx, $module_name . \Runtime\rtl::toStr(".ModuleDescription"), "requiredModules");
				$sub_modules = $f($ctx);
				if ($sub_modules != null)
				{
					$sub_modules = $sub_modules->keys($ctx);
					static::_getRequiredModules($ctx, $res, $cache, $sub_modules);
				}
				$res->pushValue($ctx, $module_name);
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
		$res = $res->removeDuplicates($ctx);
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
			if (\Runtime\rtl::method_exists($ctx, $module_class_name, "entities"))
			{
				$f = \Runtime\rtl::method($ctx, $module_class_name, "entities");
				$arr = $f($ctx);
				$entities->appendVector($ctx, $arr);
			}
		}
		return $entities->toCollection($ctx);
	}
	/**
	 * Extend entities
	 */
	static function getRequiredEntities($ctx, $entities)
	{
		$e = $entities->toVector($ctx);
		for ($i = 0;$i < $entities->count($ctx);$i++)
		{
			$item1 = $entities->item($ctx, $i);
			$item1_class_name = $item1->getClassName($ctx);
			if ($item1_class_name == "Runtime.Entity")
			{
				$class_name = ($item1->value != "") ? ($item1->value) : ($item1->name);
				$annotations = \Runtime\rtl::getClassAnnotations($ctx, $class_name);
				for ($j = 0;$j < $annotations->count($ctx);$j++)
				{
					$item2 = $annotations->item($ctx, $j);
					$item2_class_name = $item2->getClassName($ctx);
					if ($item2 instanceof \Runtime\Entity && $item2_class_name != "Runtime.Entity")
					{
						$item2 = $item2->copy($ctx, \Runtime\Dict::from(["name"=>$class_name]));
						$e->pushValue($ctx, $item2);
					}
				}
			}
		}
		return $e->toCollection($ctx);
	}
	/**
	 * Returns sub entities from classes
	 */
	static function getSubEntities($ctx, $entitites, $entity_class_name, $entity_class_method)
	{
		$class_names = $entitites->filter($ctx, \Runtime\lib::isInstance($ctx, $entity_class_name));
		$methods = new \Runtime\Vector($ctx);
		$methods->appendVector($ctx, $entitites->filter($ctx, \Runtime\lib::isInstance($ctx, $entity_class_method)));
		for ($class_names_inc = 0;$class_names_inc < $class_names->count($ctx);$class_names_inc++)
		{
			$class_item = \Runtime\rtl::get($ctx, $class_names, $class_names_inc);
			$class_name = $class_item->name;
			if ($class_name == "")
			{
				continue;
			}
			$annotations = \Runtime\rtl::getMethodsAnnotations($ctx, $class_name);
			$annotations->each($ctx, function ($ctx, $annotations, $class_method_name) use (&$methods,&$class_item,&$class_name,&$entity_class_name,&$entity_class_method)
			{
				$method_info = \Runtime\rtl::methodApply($ctx, $class_name, "getMethodInfoByName", \Runtime\Collection::from([$class_method_name]));
				for ($annotations_inc = 0;$annotations_inc < $annotations->count($ctx);$annotations_inc++)
				{
					$annotation = \Runtime\rtl::get($ctx, $annotations, $annotations_inc);
					if ($annotation)
					{
						if (\Runtime\rtl::is_instanceof($ctx, $annotation, $entity_class_method))
						{
							$annotation = $annotation->addClassItem($ctx, $class_name, $class_method_name, $class_item, $method_info);
							$methods->pushValue($ctx, $annotation);
						}
					}
				}
			});
		}
		return $methods;
	}
	/**
	 * Extends entities
	 */
	static function extendEntities($ctx, $c, $entities)
	{
		return $entities;
	}
	/**
	 * Start App
	 */
	static function startApp($ctx, $env, $module_name, $main_class)
	{
		$context = static::create($ctx, $env);
		/* Set global context */
		\Runtime\rtl::setContext($context);
		$ctx = $context;
		$context = $context::setAppName($ctx, $context, $module_name);
		$context = $context::setMainModule($ctx, $context, $module_name);
		$context = $context::setMainClass($ctx, $context, $main_class);
		$context = $context::setEntryPoint($ctx, $context, $main_class);
		/* Init context */
		$context = $context::init($ctx, $context);
		/* Start context */
		$context = $context::start($ctx, $context);
		/* Set global context */
		\Runtime\rtl::setContext($context);
		$ctx = $context;
		try
		{
			
			/* Run app */
			$context::run($ctx, $context);
		}
		catch (\Exception $_ex)
		{
			if (true)
			{
				$e = $_ex;
			}
			else
			{
				throw $_ex;
			}
		}
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
		$this->__cli_args = null;
		$this->__drivers = new \Runtime\Map($ctx);
		$this->__initialized = false;
		$this->__started = false;
		$this->__start_time = 0;
		$this->__tz = "UTC";
		$this->__app_name = "";
		$this->__entry_point = "";
		$this->__main_module = "";
		$this->__main_class = "";
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
		return "Runtime.BaseStruct";
	}
	static function getClassInfo($ctx)
	{
		return \Runtime\Dict::from([
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		if (($f&3)==3)
		{
			$a[]="base_path";
			$a[]="enviroments";
			$a[]="settings";
			$a[]="modules";
			$a[]="entities";
			$a[]="cli_args";
			$a[]="drivers";
			$a[]="initialized";
			$a[]="started";
			$a[]="start_time";
			$a[]="tz";
			$a[]="app_name";
			$a[]="entry_point";
			$a[]="main_module";
			$a[]="main_class";
		}
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "base_path") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "enviroments") return \Runtime\Dict::from([
			"t"=>"Runtime.Dict",
			"s"=> ["string"],
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "settings") return \Runtime\Dict::from([
			"t"=>"Runtime.Dict",
			"s"=> ["var"],
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "modules") return \Runtime\Dict::from([
			"t"=>"Runtime.Collection",
			"s"=> ["string"],
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "entities") return \Runtime\Dict::from([
			"t"=>"Runtime.Collection",
			"s"=> ["Runtime.BaseStruct"],
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "cli_args") return \Runtime\Dict::from([
			"t"=>"Runtime.Collection",
			"s"=> ["string"],
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "drivers") return \Runtime\Dict::from([
			"t"=>"Runtime.Map",
			"s"=> ["Runtime.BaseDriver"],
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "initialized") return \Runtime\Dict::from([
			"t"=>"bool",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "started") return \Runtime\Dict::from([
			"t"=>"bool",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "start_time") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "tz") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "app_name") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "entry_point") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "main_module") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "main_class") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		return null;
	}
	static function getMethodsList($ctx,$f=0)
	{
		$a = [];
		if (($f&4)==4) $a=[
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}