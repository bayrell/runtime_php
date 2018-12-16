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
use Runtime\CoreEvent;
use Runtime\CoreObject;
use Runtime\Map;
use Runtime\Vector;
use Runtime\Interfaces\SubscribeInterface;
class Emitter extends CoreObject{
	protected $methods;
	protected $subscribers;
	/**
	 * Constructor
	 */
	function __construct($val = null){
		parent::__construct();
		$this->methods = new Map();
		$this->subscribers = new Map();
		if ($val != null){
			$this->addMethod($val);
		}
	}
	/**
	 * Add method by name
	 * @param callback f
	 * @param string name
	 */
	function addMethodByName($f, $name){
		if (!$this->methods->has($name)){
			$this->methods->set($name, new Vector());
		}
		$v = $this->methods->item($name);
		if ($v->indexOf($f) == -1){
			$v->push($f);
		}
	}
	/**
	 * Add method
	 * @param callback f
	 * @param Vector<string> events
	 * @return callback
	 */
	function addMethod($f, $events = null){
		if ($events == null){
			$this->addMethodByName($f, "");
		}
		else {
			$events->each(function ($item) use (&$f){
				$this->addMethodByName($f, $item);
			});
		}
		return $f;
	}
	/**
	 * Remove method
	 * @param callback f
	 */
	function removeMethod($f, $events = null){
		if ($events == null){
			$events = $this->methods->keys();
		}
		$events->each(function ($name) use (&$f){
			$v = $this->methods->get($name, null);
			if ($v == null){
				return ;
			}
			$v->removeItem($f);
		});
	}
	/**
	 * Add object by name
	 * @param callback f
	 * @param string name
	 */
	function addObjectByName($f, $name){
		if (!$this->subscribers->has($name)){
			$this->subscribers->set($name, new Vector());
		}
		$v = $this->subscribers->item($name);
		if ($v->indexOf($f) == -1){
			$v->push($f);
		}
	}
	/**
	 * Add object
	 * @param SubscribeInterface f
	 * @param Vector<string> events
	 */
	function addObject($f, $events = null){
		if ($events == null){
			$this->addObjectByName($f, "");
		}
		else {
			$events->each(function ($item) use (&$f){
				$this->addObjectByName($f, $item);
			});
		}
		return $f;
	}
	/**
	 * Remove object
	 * @param SubscribeInterface f
	 */
	function removeObject($f, $events = null){
		if ($events == null){
			$events = $this->subscribers->keys();
		}
		$events->each(function ($name) use (&$f){
			$v = $this->subscribers->get($name, null);
			if ($v == null){
				return ;
			}
			$v->removeItem($f);
		});
	}
	/**
	 * Dispatch event
	 * @param CoreEvent e
	 */
	function dispatch($e){
		$keys = null;
		/* Copy items */
		$methods = $this->methods->map(function ($key, $items){
			return $items->slice();
		});
		$subscribers = $this->subscribers->map(function ($key, $items){
			return $items->slice();
		});
		/* Call self handler */
		$this->handlerEvent($e);
		/* Call methods */
		$keys = $methods->keys();
		for ($i = 0; $i < $keys->count(); $i++){
			$key = $keys->item($i);
			$items = $methods->item($key);
			if ($key != "" && $e->getClassName() != $key){
				continue;
			}
			for ($j = 0; $j < $items->count(); $j++){
				$f = $items->item($j);
				rtl::call($f, (new Vector())->push($e));
			}
		}
		/* Call subscribers */
		$keys = $subscribers->keys();
		for ($i = 0; $i < $keys->count(); $i++){
			$key = $keys->item($i);
			$items = $subscribers->item($key);
			if ($key != "" && $e->getClassName() != $key){
				continue;
			}
			for ($j = 0; $j < $items->count(); $j++){
				$obj = $items->item($j);
				$obj->handlerEvent($e);
			}
		}
	}
	/**
	 * Handler Event
	 */
	function handlerEvent($e){
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Emitter";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
	protected function _init(){
		parent::_init();
		$this->methods = null;
		$this->subscribers = null;
	}
}