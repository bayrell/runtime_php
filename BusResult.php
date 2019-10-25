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
class BusResult extends \Runtime\CoreStruct
{
	public $__success;
	public $__code;
	public $__error;
	public $__url;
	public $__api_name;
	public $__interface_name;
	public $__method_name;
	public $__text;
	public $__data;
	public $__params;
	public $__logs;
	/**
	 * Returns true if success
	 * @return bool
	 */
	static function isSuccess($__ctx, $bus)
	{
		return $bus->success && $bus->code >= \Runtime\RuntimeConstant::ERROR_OK;
	}
	/**
	 * Set error data
	 * @param int code
	 * @param string error
	 * @return BusResult
	 */
	static function setError($__ctx, $bus, $error="", $code)
	{
		return $bus->copy($__ctx, \Runtime\Dict::from(["code"=>$code,"error"=>$error,"success"=>false]));
	}
	/**
	 * Set result
	 * @param primitive res
	 * @return BusResult
	 */
	static function setSuccess($__ctx, $bus, $res)
	{
		return $bus->copy($__ctx, \Runtime\Dict::from(["code"=>\Runtime\RuntimeConstant::ERROR_OK,"error"=>"","success"=>true,"data"=>$res]));
	}
	/**
	 * Set result
	 * @param primitive res
	 * @return BusResult
	 */
	static function setResult($__ctx, $bus, $res)
	{
		return $bus->copy($__ctx, $res->takeDict($__ctx));
	}
	/**
	 * Set result
	 * @param primitive res
	 * @return BusResult
	 */
	static function create($__ctx, $res)
	{
		return new \Runtime\BusResult($__ctx, \Runtime\Dict::from(["code"=>\Runtime\RuntimeConstant::ERROR_OK,"error"=>"","success"=>true,"data"=>$res]));
	}
	/**
	 * Set result
	 * @param primitive res
	 * @return BusResult
	 */
	static function fail($__ctx, $res, $error="", $code=-1)
	{
		return new \Runtime\BusResult($__ctx, \Runtime\Dict::from(["code"=>$code,"error"=>$error,"success"=>false,"data"=>$res]));
	}
	/* ======================= Class Init Functions ======================= */
	function _init($__ctx)
	{
		parent::_init($__ctx);
		$this->__success = false;
		$this->__code = 0;
		$this->__error = "";
		$this->__url = "";
		$this->__api_name = "";
		$this->__interface_name = "";
		$this->__method_name = "";
		$this->__text = "";
		$this->__data = null;
		$this->__params = null;
		$this->__logs = null;
	}
	function assignObject($__ctx,$o)
	{
		if ($o instanceof \Runtime\BusResult)
		{
			$this->__success = $o->__success;
			$this->__code = $o->__code;
			$this->__error = $o->__error;
			$this->__url = $o->__url;
			$this->__api_name = $o->__api_name;
			$this->__interface_name = $o->__interface_name;
			$this->__method_name = $o->__method_name;
			$this->__text = $o->__text;
			$this->__data = $o->__data;
			$this->__params = $o->__params;
			$this->__logs = $o->__logs;
		}
		parent::assignObject($__ctx,$o);
	}
	function assignValue($__ctx,$k,$v)
	{
		if ($k == "success")$this->__success = $v;
		else if ($k == "code")$this->__code = $v;
		else if ($k == "error")$this->__error = $v;
		else if ($k == "url")$this->__url = $v;
		else if ($k == "api_name")$this->__api_name = $v;
		else if ($k == "interface_name")$this->__interface_name = $v;
		else if ($k == "method_name")$this->__method_name = $v;
		else if ($k == "text")$this->__text = $v;
		else if ($k == "data")$this->__data = $v;
		else if ($k == "params")$this->__params = $v;
		else if ($k == "logs")$this->__logs = $v;
		else parent::assignValue($__ctx,$k,$v);
	}
	function takeValue($__ctx,$k,$d=null)
	{
		if ($k == "success")return $this->__success;
		else if ($k == "code")return $this->__code;
		else if ($k == "error")return $this->__error;
		else if ($k == "url")return $this->__url;
		else if ($k == "api_name")return $this->__api_name;
		else if ($k == "interface_name")return $this->__interface_name;
		else if ($k == "method_name")return $this->__method_name;
		else if ($k == "text")return $this->__text;
		else if ($k == "data")return $this->__data;
		else if ($k == "params")return $this->__params;
		else if ($k == "logs")return $this->__logs;
		return parent::takeValue($__ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.BusResult";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.BusResult";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreStruct";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.BusResult",
			"name"=>"Runtime.BusResult",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "success";
			$a[] = "code";
			$a[] = "error";
			$a[] = "url";
			$a[] = "api_name";
			$a[] = "interface_name";
			$a[] = "method_name";
			$a[] = "text";
			$a[] = "data";
			$a[] = "params";
			$a[] = "logs";
		}
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
}