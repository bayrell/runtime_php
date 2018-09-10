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
use Runtime\Vector;
use Runtime\Interfaces\SubscribeInterface;
class Emitter extends CoreObject{
	protected $methods;
	protected $subscribers;
	public function getClassName(){return "Runtime.Emitter";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
	protected function _init(){
		parent::_init();
		$this->methods = null;
		$this->subscribers = null;
	}
	/**
	 * Constructor
	 */
	function __construct($val = null){
		parent::__construct();
		$this->methods = new Vector();
		$this->subscribers = new Vector();
		if ($val != null){
			$this->methods->push($val);
		}
	}
	/**
	 * Add method
	 * @param callback f
	 * @param Vector<string> events
	 * @return callback
	 */
	function addMethod($f, $events = null){
		$this->methods->push($f);
		return $f;
	}
	/**
	 * Remove method
	 * @param callback f
	 */
	function removeMethod($f){
		$this->methods->removeItem($f);
	}
	/**
	 * Add object
	 * @param SubscribeInterface f
	 * @param Vector<string> events
	 */
	function addObject($f, $events = null){
		$this->subscribers->push($f);
	}
	/**
	 * Remove object
	 * @param SubscribeInterface f
	 */
	function removeObject($f){
		$this->subscribers->removeItem($f);
	}
	/**
	 * Dispatch event
	 * @param var e
	 */
	function emit($e){
		$this->dispatch($e);
	}
	/**
	 * Dispatch event
	 * @param var e
	 */
	function dispatch($e){
		/* Call self handler */
		$this->handlerEvent($e);
		/* Call methods */
		$methods = $this->methods->slice();
		$methods->each(function ($f) use (&$e){
			rtl::call($f, $e);
		});
		/* Call subscribers */
		$subscribers = $this->subscribers->slice();
		$subscribers->each(function ($obj) use (&$e){
			$obj->handlerEvent($e);
		});
	}
	/**
	 * Handler Event
	 */
	function handlerEvent($e){
	}
}