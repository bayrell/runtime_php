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
namespace Runtime;
class MessageRPC extends \Runtime\Message
{
	public $__uri;
	public $__api_name;
	public $__space_name;
	public $__method_name;
	public $__data;
	public $__code;
	public $__error;
	public $__response;
	public $__logs;
	public $__have_result;
	/**
	 * Returns true if success
	 * @return bool
	 */
	static function isSuccess($ctx, $msg)
	{
		return $msg->have_result && $msg->code >= \Runtime\RuntimeConstant::ERROR_OK;
	}
	/**
	 * Set success result
	 * @param primitive res
	 * @return Message
	 */
	static function success($ctx, $msg, $response)
	{
		return $msg->copy($ctx, \Runtime\Dict::from(["code"=>\Runtime\RuntimeConstant::ERROR_OK,"error"=>"","response"=>$response,"have_result"=>true]));
	}
	/**
	 * Set fail result
	 * @param primitive res
	 * @return Message
	 */
	static function fail($ctx, $msg, $response, $error="", $code=-1)
	{
		return $msg->copy($ctx, \Runtime\Dict::from(["code"=>$code,"error"=>$error,"response"=>$response,"have_result"=>true]));
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
		$this->__uri = "";
		$this->__api_name = "";
		$this->__space_name = "";
		$this->__method_name = "";
		$this->__data = null;
		$this->__code = 0;
		$this->__error = "";
		$this->__response = null;
		$this->__logs = null;
		$this->__have_result = false;
	}
	function assignObject($ctx,$o)
	{
		if ($o instanceof \Runtime\MessageRPC)
		{
			$this->__uri = $o->__uri;
			$this->__api_name = $o->__api_name;
			$this->__space_name = $o->__space_name;
			$this->__method_name = $o->__method_name;
			$this->__data = $o->__data;
			$this->__code = $o->__code;
			$this->__error = $o->__error;
			$this->__response = $o->__response;
			$this->__logs = $o->__logs;
			$this->__have_result = $o->__have_result;
		}
		parent::assignObject($ctx,$o);
	}
	function assignValue($ctx,$k,$v)
	{
		if ($k == "uri")$this->__uri = $v;
		else if ($k == "api_name")$this->__api_name = $v;
		else if ($k == "space_name")$this->__space_name = $v;
		else if ($k == "method_name")$this->__method_name = $v;
		else if ($k == "data")$this->__data = $v;
		else if ($k == "code")$this->__code = $v;
		else if ($k == "error")$this->__error = $v;
		else if ($k == "response")$this->__response = $v;
		else if ($k == "logs")$this->__logs = $v;
		else if ($k == "have_result")$this->__have_result = $v;
		else parent::assignValue($ctx,$k,$v);
	}
	function takeValue($ctx,$k,$d=null)
	{
		if ($k == "uri")return $this->__uri;
		else if ($k == "api_name")return $this->__api_name;
		else if ($k == "space_name")return $this->__space_name;
		else if ($k == "method_name")return $this->__method_name;
		else if ($k == "data")return $this->__data;
		else if ($k == "code")return $this->__code;
		else if ($k == "error")return $this->__error;
		else if ($k == "response")return $this->__response;
		else if ($k == "logs")return $this->__logs;
		else if ($k == "have_result")return $this->__have_result;
		return parent::takeValue($ctx,$k,$d);
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
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.MessageRPC",
			"name"=>"Runtime.MessageRPC",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "uri";
			$a[] = "api_name";
			$a[] = "space_name";
			$a[] = "method_name";
			$a[] = "data";
			$a[] = "code";
			$a[] = "error";
			$a[] = "response";
			$a[] = "logs";
			$a[] = "have_result";
		}
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "uri") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "api_name") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "space_name") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "method_name") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "data") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "code") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "error") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "response") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "logs") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "have_result") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.MessageRPC",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		return null;
	}
	static function getMethodsList($ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}