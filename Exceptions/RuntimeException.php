<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2020 "Ildar Bikmamatov" <support@bayrell.org>
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
class ClassException extends \Exception
{
	function __construct($ctx, $message="", $code=-1, $prev=null)
	{
		parent::__construct($message, (int)$code, $prev);
	}
	function _init($ctx){}
}
class RuntimeException extends \Runtime\Exceptions\ClassException
{
	public $prev;
	public $error_message;
	public $error_str;
	public $error_code;
	public $error_file;
	public $error_line;
	public $error_pos;
	function __construct($ctx, $message="", $code=-1, $prev=null)
	{
		parent::__construct($ctx, $message, $code, $prev);
		$this->_init($ctx);
		$this->error_str = $message;
		$this->error_code = $code;
		$this->prev = $prev;
		$this->updateError($ctx);
	}
	function getPreviousException($ctx)
	{
		return $this->prev;
	}
	function getErrorMessage($ctx)
	{
		return $this->error_message;
	}
	function getErrorString($ctx)
	{
		return $this->error_str;
	}
	function getErrorCode($ctx)
	{
		return $this->error_code;
	}
	function getFileName($ctx)
	{
		if ($this->error_file == "")
		{
			return $this->getFile();
		}
		return $this->error_file;
	}
	function getErrorLine($ctx)
	{
		if ($this->error_line == "")
		{
			return $this->getLine();
		}
		return $this->error_line;
	}
	function getErrorPos($ctx)
	{
		return $this->error_pos;
	}
	function toString($ctx)
	{
		return $this->buildMessage($ctx);
	}
	function buildMessage($ctx)
	{
		return $this->error_str;
	}
	function updateError($ctx)
	{
		$this->error_message = $this->buildMessage($ctx);
	}
	/**
	 * Returns trace
	 */
	function getTraceStr($ctx)
	{
		return $this->getTraceAsString();
	}
	public function __toString (){
		return $this->toString(null);
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
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
	static function getClassInfo($ctx)
	{
		return \Runtime\Dict::from([
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "prev") return \Runtime\Dict::from([
			"t"=>"Object",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "error_message") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "error_str") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "error_code") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "error_file") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "error_line") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "error_pos") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		return null;
	}
	static function getMethodsList($ctx,$f=0)
	{
		$a = [];
		if (($f&4)==4) $a=[
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}