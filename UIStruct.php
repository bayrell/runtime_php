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
class UIStruct extends \Runtime\CoreStruct
{
	const TYPE_ELEMENT="element";
	const TYPE_COMPONENT="component";
	const TYPE_STRING="string";
	const TYPE_RAW="raw";
	public $__class_name;
	public $__key;
	public $__name;
	public $__bind;
	public $__kind;
	public $__content;
	public $__reference;
	public $__value;
	public $__layout;
	public $__model;
	public $__props;
	public $__annotations;
	public $__children;
	/**
	 * Returns true if component
	 * @return bool
	 */
	function getTag($ctx)
	{
		if ($this->props == null)
		{
			return null;
		}
		return $this->props->get($ctx, "@tag", null);
	}
	/**
	 * Returns true if component
	 * @return bool
	 */
	function isComponent($ctx)
	{
		return $this->kind == \Runtime\UIStruct::TYPE_COMPONENT;
	}
	/**
	 * Returns true if element
	 * @return bool
	 */
	function isElement($ctx)
	{
		return $this->kind == \Runtime\UIStruct::TYPE_ELEMENT;
	}
	/**
	 * Returns true if string
	 * @return bool
	 */
	function isString($ctx)
	{
		return $this->kind == \Runtime\UIStruct::TYPE_STRING || $this->kind == \Runtime\UIStruct::TYPE_RAW;
	}
	/**
	 * Returns model
	 * @return CoreStruct
	 */
	function getModel($ctx)
	{
		return $this->model;
		if ($this->model != null)
		{
			return $this->model;
		}
		if ($this->kind == \Runtime\UIStruct::TYPE_COMPONENT)
		{
			$modelName = \Runtime\rtl::method($this->name, "modelName");
			$model_name = $modelName($ctx);
			if ($model_name == "")
			{
				return null;
			}
			$model = \Runtime\rtl::newInstance($ctx, $model_name, \Runtime\Collection::from([$this->props]));
			return $model;
		}
		return null;
	}
	/**
	 * Returns key path
	 * @return string
	 */
	function getKey($ctx, $index)
	{
		return ($this->key !== "") ? $this->key : $index;
	}
	/**
	 * Returns key path
	 * @return string
	 */
	function getKeyPath($ctx, $key_path, $index)
	{
		return ($key_path !== "") ? $key_path . \Runtime\rtl::toStr(".") . \Runtime\rtl::toStr($this->getKey($ctx, $index)) : $this->getKey($ctx, $index);
	}
	/**
	 * Returns attrs
	 */
	function getAttrs($ctx)
	{
		if ($this->props != null)
		{
			return $this->props->filter($ctx, function ($ctx, $key, $value)
			{
				return \Runtime\rs::strpos($ctx, $key, "@") != 0 || $key == "@class" || $key == "@style";
			});
		}
		return new \Runtime\Dict($ctx);
	}
	/**
	 * Returns props
	 */
	function getProps($ctx)
	{
		if ($this->props != null)
		{
			return $this->props->filter($ctx, function ($ctx, $key, $value)
			{
				return \Runtime\rs::strpos($ctx, $key, "@") == 0 && \Runtime\rs::strpos($ctx, $key, "@on") != 0 && $key != "@class";
			});
		}
		return new \Runtime\Dict($ctx);
	}
	/**
	 * Returns events
	 */
	function getEvents($ctx)
	{
		if ($this->props != null)
		{
			return $this->props->filter($ctx, function ($ctx, $key, $value)
			{
				return \Runtime\rs::strpos($ctx, $key, "@on") == 0;
			});
		}
		return new \Runtime\Dict($ctx);
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
		$this->__class_name = "";
		$this->__key = "";
		$this->__name = "";
		$this->__bind = "";
		$this->__kind = "element";
		$this->__content = "";
		$this->__reference = "";
		$this->__value = null;
		$this->__layout = null;
		$this->__model = null;
		$this->__props = null;
		$this->__annotations = null;
		$this->__children = null;
	}
	function assignObject($ctx,$o)
	{
		if ($o instanceof \Runtime\UIStruct)
		{
			$this->__class_name = $o->__class_name;
			$this->__key = $o->__key;
			$this->__name = $o->__name;
			$this->__bind = $o->__bind;
			$this->__kind = $o->__kind;
			$this->__content = $o->__content;
			$this->__reference = $o->__reference;
			$this->__value = $o->__value;
			$this->__layout = $o->__layout;
			$this->__model = $o->__model;
			$this->__props = $o->__props;
			$this->__annotations = $o->__annotations;
			$this->__children = $o->__children;
		}
		parent::assignObject($ctx,$o);
	}
	function assignValue($ctx,$k,$v)
	{
		if ($k == "class_name")$this->__class_name = $v;
		else if ($k == "key")$this->__key = $v;
		else if ($k == "name")$this->__name = $v;
		else if ($k == "bind")$this->__bind = $v;
		else if ($k == "kind")$this->__kind = $v;
		else if ($k == "content")$this->__content = $v;
		else if ($k == "reference")$this->__reference = $v;
		else if ($k == "value")$this->__value = $v;
		else if ($k == "layout")$this->__layout = $v;
		else if ($k == "model")$this->__model = $v;
		else if ($k == "props")$this->__props = $v;
		else if ($k == "annotations")$this->__annotations = $v;
		else if ($k == "children")$this->__children = $v;
		else parent::assignValue($ctx,$k,$v);
	}
	function takeValue($ctx,$k,$d=null)
	{
		if ($k == "class_name")return $this->__class_name;
		else if ($k == "key")return $this->__key;
		else if ($k == "name")return $this->__name;
		else if ($k == "bind")return $this->__bind;
		else if ($k == "kind")return $this->__kind;
		else if ($k == "content")return $this->__content;
		else if ($k == "reference")return $this->__reference;
		else if ($k == "value")return $this->__value;
		else if ($k == "layout")return $this->__layout;
		else if ($k == "model")return $this->__model;
		else if ($k == "props")return $this->__props;
		else if ($k == "annotations")return $this->__annotations;
		else if ($k == "children")return $this->__children;
		return parent::takeValue($ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.UIStruct";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.UIStruct";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreStruct";
	}
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.UIStruct",
			"name"=>"Runtime.UIStruct",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "class_name";
			$a[] = "key";
			$a[] = "name";
			$a[] = "bind";
			$a[] = "kind";
			$a[] = "content";
			$a[] = "reference";
			$a[] = "value";
			$a[] = "layout";
			$a[] = "model";
			$a[] = "props";
			$a[] = "annotations";
			$a[] = "children";
		}
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "TYPE_ELEMENT") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "TYPE_COMPONENT") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "TYPE_STRING") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "TYPE_RAW") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "class_name") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "key") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "name") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "bind") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "kind") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "content") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "reference") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "value") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "layout") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "model") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "props") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "annotations") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "children") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.UIStruct",
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