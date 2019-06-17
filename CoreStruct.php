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
use Runtime\CoreObject;
use Runtime\Dict;
use Runtime\rtl;
use Runtime\Vector;
use Runtime\Interfaces\SerializeInterface;
class CoreStruct extends CoreObject implements SerializeInterface{
	/** 
	 * Constructor
	 */
	function __construct($obj = null){
		parent::__construct();
		if ($obj != null){
			$this->assignMap($obj);
			$this->initData();
		}
	}
	/**
	 * Init struct data
	 */
	function initData(){
	}
	/**
	 * Copy this struct with new values
	 * @param Map obj = null
	 * @return CoreStruct
	 */
	function copy($obj = null){
		if ($obj == null){
			return $this;
		}
		$res = rtl::newInstance($this->getClassName(), (new Vector()));
		$res->assignObject($this);
		$res->setMap($obj);
		$res->initData();
		/* Return object */
		return $res;
	}
	/**
	 * Create new struct with new value
	 * @param string field_name
	 * @param fun f
	 * @return CoreStruct
	 */
	function map($field_name, $f){
		return $this->copy((new $Map())->set($field_name, $f($this->takeValue($field_name))));
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.CoreStruct";}
	public static function getCurrentNamespace(){return "Runtime";}
	public static function getCurrentClassName(){return "Runtime.CoreStruct";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
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