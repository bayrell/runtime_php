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
use Runtime\CoreObject;
use Runtime\Map;
use Runtime\rtl;
use Runtime\Interfaces\SerializeInterface;
class CoreStruct extends CoreObject implements SerializeInterface{
	/** 
	 * Constructor
	 */
	function __construct($obj = null){
		parent::__construct();
		$this->assignMap($obj);
		$this->onCreated();
	}
	/**
	 * Struct created 
	 */
	function onCreated(){
	}
	/**
	 * Clone this object with new values
	 * @param Map obj = null
	 * @return CoreStruct
	 */
	function clone($obj = null){
		$instance = rtl::newInstance($this->getClassName());
		$instance->assignObject($this);
		if ($obj != null){
			$instance->setMap($obj);
		}
		$instance->onCreated();
		return $instance;
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.CoreStruct";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
}