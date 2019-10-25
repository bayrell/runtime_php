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
class MessageRPC extends \Runtime\Message
{
	public $__code;
	public $__error;
	public $__response;
	public $__logs;
	/**
	 * Returns true if success
	 * @return bool
	 */
	static function isSuccess($__ctx, $msg)
	{
		return $msg->code >= \Runtime\RuntimeConstant::ERROR_OK;
	}
	/**
	 * Set success result
	 * @param primitive res
	 * @return Message
	 */
	static function success($__ctx, $response)
	{
		return new \Runtime\Message($__ctx, \Runtime\Dict::from(["code"=>\Runtime\RuntimeConstant::ERROR_OK,"error"=>"","response"=>$response]));
	}
	/**
	 * Set fail result
	 * @param primitive res
	 * @return Message
	 */
	static function fail($__ctx, $error="", $code=-1, $response=null)
	{
		return new \Runtime\Message($__ctx, \Runtime\Dict::from(["code"=>$code,"error"=>$error,"response"=>$response]));
	}
	/* ======================= Class Init Functions ======================= */
	function _init($__ctx)
	{
		parent::_init($__ctx);
		$this->__code = 0;
		$this->__error = "";
		$this->__response = null;
		$this->__logs = null;
	}
	function assignObject($__ctx,$o)
	{
		if ($o instanceof \Runtime\MessageRPC)
		{
			$this->__code = $o->__code;
			$this->__error = $o->__error;
			$this->__response = $o->__response;
			$this->__logs = $o->__logs;
		}
		parent::assignObject($__ctx,$o);
	}
	function assignValue($__ctx,$k,$v)
	{
		if ($k == "code")$this->__code = $v;
		else if ($k == "error")$this->__error = $v;
		else if ($k == "response")$this->__response = $v;
		else if ($k == "logs")$this->__logs = $v;
		else parent::assignValue($__ctx,$k,$v);
	}
	function takeValue($__ctx,$k,$d=null)
	{
		if ($k == "code")return $this->__code;
		else if ($k == "error")return $this->__error;
		else if ($k == "response")return $this->__response;
		else if ($k == "logs")return $this->__logs;
		return parent::takeValue($__ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.MessageRPC";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.MessageRPC";
	}
	static function getParentClassName()
	{
		return "Runtime.Message";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.MessageRPC",
			"name"=>"Runtime.MessageRPC",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "code";
			$a[] = "error";
			$a[] = "response";
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