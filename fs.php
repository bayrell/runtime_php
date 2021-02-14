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
class fs
{
	const DIRECTORY_SEPARATOR="/";
	/**
	 * Add first slash
	 */
	static function addFirstSlash($ctx, $s)
	{
		return \Runtime\re::replace($ctx, "//", "/", static::DIRECTORY_SEPARATOR . \Runtime\rtl::toStr($s));
	}
	/**
	 * Add last slash
	 */
	static function addLastSlash($ctx, $s)
	{
		return \Runtime\re::replace($ctx, "//", "/", $s . \Runtime\rtl::toStr(static::DIRECTORY_SEPARATOR));
	}
	/**
	 * Concat
	 */
	static function concat($ctx)
	{
		$arr = \Runtime\Collection::from([]);
		$arr = \Runtime\Collection::from( array_slice(func_get_args(), 1) );
		return static::concatArr($ctx, $arr);
	}
	/**
	 * Concat array
	 */
	static function concatArr($ctx, $arr)
	{
		$res = $arr->reduce($ctx, function ($ctx, $res, $item)
		{
			return $res . \Runtime\rtl::toStr(static::DIRECTORY_SEPARATOR) . \Runtime\rtl::toStr($item);
		}, "");
		return \Runtime\re::replace($ctx, "\\/\\/", "/", $res);
	}
	/**
	 * Relative
	 */
	static function relative($ctx, $path, $to)
	{
		return "";
		return "";
	}
	/**
	 * Exists
	 */
	static function exists($ctx, $path, $chroot="")
	{
		if ($chroot != "" && \Runtime\rs::substr($ctx, $chroot, -1) != "/")
		{
			$chroot .= \Runtime\rtl::toStr("/");
		}
		$filepath = $chroot . \Runtime\rtl::toStr($path);
		return file_exists($filepath);
		return false;
	}
	/**
	 * Save local file
	 */
	static function saveFile($ctx, $path, $content="", $chroot="", $ch="utf8")
	{
		if ($chroot != "" && \Runtime\rs::substr($ctx, $chroot, -1) != "/")
		{
			$chroot .= \Runtime\rtl::toStr("/");
		}
		$filepath = $chroot . \Runtime\rtl::toStr($path);
		if ($filepath == false) return "";
		if ($chroot != "" && strpos($filepath, $chroot) !== 0) return "";
		return @file_put_contents($filepath, $content);
		return "";
	}
	/**
	 * Read local file
	 */
	static function readFile($ctx, $path, $chroot="", $ch="utf8")
	{
		if ($chroot != "" && \Runtime\rs::substr($ctx, $chroot, -1) != "/")
		{
			$chroot .= \Runtime\rtl::toStr("/");
		}
		$filepath = $chroot . \Runtime\rtl::toStr($path);
		$filepath = realpath($filepath);
		if ($filepath == false) return "";
		if ($chroot != "" && strpos($filepath, $chroot) !== 0) return "";
		if (!file_exists($filepath)) return "";
		return file_get_contents($filepath);
		return "";
	}
	/**
	 * Rename file
	 */
	static function renameFile($ctx, $path, $new_path, $chroot="")
	{
		if ($chroot != "" && \Runtime\rs::substr($ctx, $chroot, -1) != "/")
		{
			$chroot .= \Runtime\rtl::toStr("/");
		}
		$filepath = $chroot . \Runtime\rtl::toStr($path);
		$filepath_new = $chroot . \Runtime\rtl::toStr($new_path);
		if ($filepath == false) return false;
		if ($chroot != "" && strpos($filepath, $chroot) !== 0) return false;
		if ($chroot != "" && strpos($filepath_new, $chroot) !== 0) return false;
		rename($filepath, $filepath_new);
		return "";
	}
	/**
	 * Make dir
	 */
	static function mkdir($ctx, $path, $chroot="", $mode="755")
	{
		if ($chroot != "" && \Runtime\rs::substr($ctx, $chroot, -1) != "/")
		{
			$chroot .= \Runtime\rtl::toStr("/");
		}
		$filepath = $chroot . \Runtime\rtl::toStr($path);
		if ($filepath == false) return false;
		if ($chroot != "" && strpos($filepath, $chroot) !== 0) return false;
		return @mkdir($filepath, octdec($mode), true);
		return "";
	}
	/**
	 * Synlink
	 */
	static function symlink($ctx, $target, $link_name, $chroot="")
	{
		if ($chroot != "" && \Runtime\rs::substr($ctx, $chroot, -1) != "/")
		{
			$chroot .= \Runtime\rtl::toStr("/");
		}
		$target_path = $target;
		$link_name_path = $link_name;
		if (\Runtime\rs::substr($ctx, $target_path, 0, 2) != "..")
		{
			$target_path = $chroot . \Runtime\rtl::toStr($target);
		}
		if (\Runtime\rs::substr($ctx, $link_name_path, 0, 2) != "..")
		{
			$link_name_path = $chroot . \Runtime\rtl::toStr($link_name);
		}
		return "";
	}
	/**
	 * Remove
	 */
	static function remove($ctx, $path, $chroot="")
	{
		if ($chroot != "" && \Runtime\rs::substr($ctx, $chroot, -1) != "/")
		{
			$chroot .= \Runtime\rtl::toStr("/");
		}
		$filepath = $chroot . \Runtime\rtl::toStr($path);
		if (is_dir($filepath)) rmdir($filepath);
		else unlink($filepath);
		return "";
	}
	static function unlink($ctx, $path, $chroot="")
	{
		return static::remove($ctx, $path, $chroot);
	}
	/**
	 * Return true if path is folder
	 * @param string path
	 * @param boolean
	 */
	static function isDir($ctx, $path, $chroot="")
	{
		if ($chroot != "" && \Runtime\rs::substr($ctx, $chroot, -1) != "/")
		{
			$chroot .= \Runtime\rtl::toStr("/");
		}
		$dirpath = $chroot . \Runtime\rtl::toStr($path);
		return is_dir($dirpath);
	}
	/**
	 * Scan directory
	 */
	static function readDir($ctx, $dirname, $chroot="")
	{
		if ($chroot != "" && \Runtime\rs::substr($ctx, $chroot, -1) != "/")
		{
			$chroot .= \Runtime\rtl::toStr("/");
		}
		$dirpath = $chroot . \Runtime\rtl::toStr($dirname);
		return \Runtime\Collection::from( scandir($dirpath) );
		return null;
	}
	/**
	 * Scan directory recursive
	 */
	static function readDirectoryRecursive($ctx, $dirname, $chroot="", $parent_name="")
	{
		$res = new \Runtime\Vector($ctx);
		$items = static::readDir($ctx, $dirname, $chroot);
		for ($i = 0;$i < $items->count($ctx);$i++)
		{
			$item_name = $items->item($ctx, $i);
			$item_path = static::concat($ctx, $dirname, $item_name);
			$item_name2 = \Runtime\fs::concat($ctx, $parent_name, $item_name);
			if ($item_name == "." || $item_name == "..")
			{
				continue;
			}
			$res->push($ctx, $item_name2);
			$is_dir = static::isDir($ctx, $item_path, $chroot);
			if ($is_dir)
			{
				$sub_items = static::readDirectoryRecursive($ctx, $item_path, $chroot, $item_name2);
				$res->appendVector($ctx, $sub_items);
			}
		}
		return $res->toCollection($ctx);
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.fs";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.fs";
	}
	static function getParentClassName()
	{
		return "";
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
		if ($field_name == "DIRECTORY_SEPARATOR") return \Runtime\Dict::from([
			"t"=>"string",
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