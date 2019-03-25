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
use Runtime\RuntimeUtils;
use Runtime\Interfaces\ContextInterface;
class ContextObject extends CoreObject{
	protected $_context;
	/**
	 * Returns context provider
	 *
	 * @params string provider_name
	 * @return ContextObject
	 */
	function createProvider($provider_name){
		return $this->_context->createProvider($provider_name);
	}
	/**
	 * Translate message
	 * @params string message - message need to be translated
	 * @params Map params - Messages params. Default null.
	 * @params string locale - Different locale. Default "".
	 * @return string - translated string
	 */
	function translate($message, $params = null, $locale = ""){
		return $this->_context->translate($message, $params, $locale);
	}
	/**
	 * Get context
	 *
	 * @return ContextInterface 
	 */
	function context(){
		return $this->_context;
	}
	/** 
	 * Constructor
	 */
	function __construct($context = null){
		parent::__construct();
		$this->_context = $context;
		if (!rtl::exists($this->_context)){
			$this->_context = RuntimeUtils::getContext();
		}
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.ContextObject";}
	public static function getCurrentClassName(){return "Runtime.ContextObject";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
	protected function _init(){
		parent::_init();
	}
}