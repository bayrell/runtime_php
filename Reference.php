<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2018 "Ildar Bikmamatov" <support@bayrell.org>
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
use Runtime\rtl;
class Reference extends CoreObject{
	public $uq;
	public $ref;
	/**
	 * Constructor
	 */
	function __construct($ref = null){
		parent::__construct();
		$this->ref = $ref;
	}
	/**
	 * Assign and clone data from other object
	 * @param CoreObject obj
	 */
	function assignObject($obj){
		if ($obj instanceof Reference){
			$this->uq = $obj->uq;
			$this->ref = $this->ref;
		}
		parent::assignObject($obj);
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Reference";}
	public static function getCurrentClassName(){return "Runtime.Reference";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
	protected function _init(){
		parent::_init();
	}
}