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
namespace Runtime\Exceptions;
class Error extends \Exception{function _init(){}}
class RuntimeException extends \Runtime\Exceptions\ClassException
{
	public $context;
	public $prev;
	public $error_message;
	public $error_str;
	public $error_code;
	public $error_file;
	public $error_line;
	public $error_pos;
	function __construct($__ctx, $message="", $code=-1, $context=null, $prev=null)
	{
		parent::__construct($__ctx, $message, $code, $prev);
		$this->_init($__ctx);
		$this->error_str = $message;
		$this->error_code = $code;
		$this->context = $context;
		$this->prev = $prev;
		$this->updateError($__ctx);
	}
	function getPreviousException($__ctx)
	{
		return $this->prev;
	}
	function getErrorMessage($__ctx)
	{
		return $this->error_message;
	}
	function getErrorString($__ctx)
	{
		return $this->error_str;
	}
	function getErrorCode($__ctx)
	{
		return $this->error_code;
	}
	function getFileName($__ctx)
	{
		if ($this->error_file == "")
		{
			return $this->getFile();
		}
		return $this->error_file;
	}
	function getErrorLine($__ctx)
	{
		if ($this->error_line == "")
		{
			return $this->getLine();
		}
		return $this->error_line;
	}
	function getErrorPos($__ctx)
	{
		return $this->error_pos;
	}
	function toString($__ctx)
	{
		return $this->buildMessage($__ctx);
	}
	function buildMessage($__ctx)
	{
		$error_str = $this->error_str;
		$file = $this->getFileName($__ctx);
		$line = $this->getErrorLine($__ctx);
		$pos = $this->getErrorPos($__ctx);
		if ($line != -1)
		{
			$error_str .= \Runtime\rtl::toStr(" at Ln:" . \Runtime\rtl::toStr($line) . \Runtime\rtl::toStr((($pos != "") ? ", Pos:" . \Runtime\rtl::toStr($pos) : "")));
		}
		if ($file != "")
		{
			$error_str .= \Runtime\rtl::toStr(" in file:'" . \Runtime\rtl::toStr($file) . \Runtime\rtl::toStr("'"));
		}
		return $error_str;
	}
	function updateError($__ctx)
	{
		$this->error_message = $this->buildMessage($__ctx);
	}
	/**
	 * Returns trace
	 */
	function getTraceStr($__ctx)
	{
		return $this->getTraceAsString();
	}
	/* ======================= Class Init Functions ======================= */
	function _init($__ctx)
	{
		parent::_init($__ctx);
		$this->context = null;
		$this->prev = null;
		$this->error_message = "";
		$this->error_str = "";
		$this->error_code = 0;
		$this->error_file = "";
		$this->error_line = -1;
		$this->error_pos = -1;
	}
	function getClassName()
	{
		return "Runtime.Exceptions.RuntimeException";
	}
	static function getCurrentNamespace()
	{
		return "Runtime.Exceptions";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Exceptions.RuntimeException";
	}
	static function getParentClassName()
	{
		return "Runtime.Exceptions.ClassException";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Exceptions.RuntimeException",
			"name"=>"Runtime.Exceptions.RuntimeException",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($__ctx,$field_name)
	{
		return null;
	}
	static function getMethodsList($__ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($__ctx,$field_name)
	{
		return null;
	}
	
	public function __toString (){
		return $this->toString();
	}
}