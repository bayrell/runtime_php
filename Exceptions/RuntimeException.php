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
namespace Runtime\Exceptions;
use Runtime\rtl;
use Runtime\RuntimeUtils;
use Runtime\Interfaces\ContextInterface;

class ClassException extends \Exception {}
class RuntimeException extends ClassException{
	protected $context;
	protected $prev;
	protected $error_str;
	protected $message;
	protected $code;
	protected $file;
	protected $line;
	protected $pos;
	function __construct($message = "", $code = 0, $context = null, $prev = null){
		parent::__construct($message, $code, $prev);
		if ($context == null){
			$context = RuntimeUtils::globalContext();
		}
		$this->error_str = $message;
		$this->context = $context;
		$this->message = $message;
		$this->code = $code;
		$this->prev = $prev;
		$this->file = "";
		$this->line = -1;
		$this->pos = -1;
	}
	function getPreviousException(){
		return $this->prev;
	}
	function getErrorMessage(){
		return $this->message;
	}
	function getErrorCode(){
		return $this->code;
	}
	function getFileName(){
		return $this->file;
	}
	function setFileName($file){
		$this->file = $file;
	}
	function getErrorLine(){
		return $this->line;
	}
	function setErrorLine($line){
		$this->line = $line;
	}
	function getErrorPos(){
		return $this->pos;
	}
	function setErrorPos($pos){
		$this->pos = $pos;
	}
	function toString(){
		return $this->message;
	}
	function buildMessage(){
		$this->message = $this->error_str;
		if ($this->line != -1 && $this->pos != -1){
			$this->message .= " at Ln:" . rtl::toString($this->line) . ", Pos:" . rtl::toString($this->pos);
		}
		if ($this->file != ""){
			$this->message .= " in file:'" . rtl::toString($this->file) . "'";
		}
	}
	
	public function __toString (){
		return $this->toString();
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.Exceptions.RuntimeException";}
	public static function getParentClassName(){return "Runtime.Exceptions.ClassException";}
	protected function _init(){
		parent::_init();
		$this->context = null;
		$this->prev = null;
		$this->error_str = "";
		$this->message = "";
		$this->code = 0;
		$this->file = "";
		$this->line = -1;
		$this->pos = -1;
	}
}