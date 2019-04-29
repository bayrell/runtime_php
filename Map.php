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
use Runtime\Vector;
use Runtime\Exceptions\KeyNotFound;

class Map extends Dict
{
	
	
	protected $_map = null;
	
		
	
	/**
	 * Set value size_to position
	 * @param T pos - position
	 * @param T value 
	 * @return self
	 */
	public function set($key, $value)
	{
		$key = rtl::toString($key);
		$this->_map[$key] = $value;
		return $this;
	}
	
	
	
	/**
	 * Remove value from position
	 * @param T key
	 * @return self
	 */
	public function remove($key)
	{
		$key = rtl::toString($key);
		if (isset($this->_map[$key]))
			unset($this->_map[$key]);
		return $this;
	}
	
	
	
	/**
	 * Clear all values from vector
	 * @return self
	 */
	public function clear(){
		$this->_map = [];
		return $this;
	}
	
	
	function getClassName(){return "Runtime.Map";}
	public static function getCurrentClassName(){return "Runtime.Map";}
	public static function getParentClassName(){return "Runtime.Dict";}
}