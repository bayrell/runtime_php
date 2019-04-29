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
use Runtime\rtl;
use Runtime\CoreEvent;
use Runtime\CoreObject;
use Runtime\Map;
use Runtime\Vector;
use Runtime\Interfaces\SubscribeInterface;
class Emitter extends CoreObject{
	protected $methods;
	protected $emitters;
	/**
	 * Constructor
	 */
	function __construct($val = null){
		parent::__construct();
		$this->methods = new Map();
		$this->emitters = new Vector();
		if ($val != null){
			$this->addMethod($val);
		}
	}
	/**
	 * Assign and clone data from other object
	 * @param CoreObject obj
	 */
	function assignObject($obj){
		if ($obj instanceof Emitter){
			$this->methods = $obj->methods;
			$this->emitters = $obj->emitters;
		}
		parent::assignObject($obj);
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
	 * Add object
	 * @param Emitter emitter
	 */
	function addEmitter($emitter){
		$this->emitters->push($emitter);
		return $emitter;
	}
	/**
	 * Remove object
	 * @param Emitter emitter
	 */
	function removeEmitter($emitter){
		$this->emitters->removeItem($emitter);
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
		/* Call self handler */
		$this->handlerEvent($e);
		/* Call methods */
		$keys = $methods->keys();
		for ($i = 0; $i < $keys->count(); $i++){
			$key = $keys->item($i);
			$items = $methods->item($key);
			if ($key != "" && $e->getClassName() != $key && !rtl::is_instanceof($e, $key)){
				continue;
			}
			for ($j = 0; $j < $items->count(); $j++){
				$f = $items->item($j);
				$f($e);
				/*rtl::call(f, [e]);*/
			}
		}
		/* Call emitters */
		$emitters = $this->emitters->copy();
		for ($i = 0; $i < $emitters->count(); $i++){
			$emitter = $this->emitters->item($i);
			$emitter->dispatch($e);
		}
	}
	/**
	 * Handler Event
	 */
	function handlerEvent($e){
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Emitter";}
	public static function getCurrentClassName(){return "Runtime.Emitter";}
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