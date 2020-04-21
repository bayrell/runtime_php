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
class rs
{
	/**
	 * Returns string lenght
	 * @param string s The string
	 * @return int
	 */
	static function strlen($ctx, $s)
	{
		if (gettype($s) != "string") return 0;
		return @mb_strlen($s);
	}
	/**
	 * Search 'search' in s.
	 */
	static function search($ctx, $s, $search, $offset=0)
	{
		if ($search == ""){
			return -1;
		}
		$res = mb_strpos($s, $search, $offset);
		if ($res === false)
			return -1;
		return $res;
	}
	/**
	 * Returns substring
	 * @param string s The string
	 * @param int start
	 * @param int length
	 * @return string
	 */
	static function substr($ctx, $s, $start, $length=null)
	{
		if ($length === null)
		{
			return mb_substr($s, $start);
		}
		return mb_substr($s, $start, $length);
	}
	/**
	 * Returns char from string at the position
	 * @param string s The string
	 * @param int pos The position
	 * @return string
	 */
	static function charAt($ctx, $s, $pos)
	{
		$sz = static::strlen($ctx, $s);
		if ($pos >= 0 && $pos < $sz)
		{
			return static::substr($ctx, $s, $pos, 1);
		}
		return "";
	}
	/**
	 * Returns ASCII symbol code
	 * @param char ch
	 */
	static function ord($ctx, $ch)
	{
		if ($ch == "") return 0;
		$ch = mb_convert_encoding($ch, 'UCS-4LE', 'UTF-8');
		$r = @unpack('V', $ch);
		if ($r) return $r[1];
		return 0;
	}
	/**
	 * Convert string to lower case
	 * @param string s 
	 * @return string
	 */
	static function strtolower($ctx, $s)
	{
		return mb_strtolower($s);
	}
	/**
	 * Convert string to upper case
	 * @param string s
	 * @return string
	 */
	static function strtoupper($ctx, $s)
	{
		return mb_strtoupper($s);
	}
	/**
	 * Заменяет одну строку на другую
	 */
	static function replace($ctx, $search, $item, $s)
	{
		return str_replace($search, $item, $s);
	}
	/**
	 * Возвращает повторяющуюся строку
	 * @param {string} s - повторяемая строка
	 * @param {integer} n - количество раз, которые нужно повторить строку s
	 * @return {string} строка
	 */
	static function str_repeat($ctx, $s, $n)
	{
		if ($n <= 0) return "";
		return str_repeat($s, $n);
	}
	/**
	 * Разбивает строку на подстроки
	 * @param string delimiter - regular expression
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Collection<string>
	 */
	static function split($ctx, $delimiter, $s, $limit=-1)
	{
		$arr = preg_split("/".$delimiter."/", $s, $limit);
		return Collection::from($arr);
	}
	/**
	 * Разбивает строку на подстроки
	 * @param string ch - разделитель
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Collection<string>
	 */
	static function splitArr($ctx, $delimiters, $s, $limit=-1)
	{
		$pattern = "[".implode("", $delimiters->_getArr())."]";
		$pattern = str_replace("/", "\/", $pattern);
		$arr = preg_split("/".$pattern."/", $s, $limit);
		return Collection::from($arr);
	}
	/**
	 * Соединяет строки
	 * @param string ch - разделитель
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Vector<string>
	 */
	static function join($ctx, $ch, $arr)
	{
		if ($arr == null) return "";
		return implode($ch, $arr->_getArr());
	}
	/**
	 * Удаляет лишние символы слева и справа
	 * @param {string} s - входная строка
	 * @return {integer} новая строка
	 */
	static function trim($ctx, $s, $ch="")
	{
		if ($ch=="")
			return trim($s);
		return trim($s, $ch);
	}
	/**
	 * json encode scalar values
	 * @param {mixed} obj - объект
	 * @param {int} flags - Флаги
	 * @return {string} json строка
	 */
	static function json_encode($ctx, $s, $flags)
	{
		$flags = $flags || JSON_UNESCAPED_UNICODE;
		return json_encode($s, JSON_UNESCAPED_UNICODE);
	}
	/**
	 * Escape HTML special chars
	 * @param string s
	 * @return string
	 */
	static function htmlEscape($ctx, $s)
	{
		if ($s instanceof \Runtime\Collection) return $s;
		if ($s instanceof \Runtime\UIStruct) return $s;
		return htmlspecialchars($s, ENT_QUOTES | ENT_HTML401);
	}
	static function escapeHtml($ctx, $s)
	{
		return static::htmlEscape($ctx, $s);
	}
	/**
	 * Разбивает путь файла на составляющие
	 * @param {string} filepath путь к файлу
	 * @return {json} Объект вида:
	 *         dirname    - папка, в которой находиться файл
	 *         basename   - полное имя файла
	 *         extension  - расширение файла
	 *         filename   - имя файла без расширения
	 */
	static function pathinfo($ctx, $filepath)
	{
		$arr1 = \Runtime\rs::explode($ctx, ".", $filepath)->toVector($ctx);
		$arr2 = \Runtime\rs::explode($ctx, "/", $filepath)->toVector($ctx);
		$ret = new \Runtime\PathInfo($ctx);
		$ret->filepath = $filepath;
		$ret->extension = $arr1->pop($ctx);
		$ret->basename = $arr2->pop($ctx);
		$ret->dirname = \Runtime\rs::implode($ctx, "/", $arr2);
		$ext_length = \Runtime\rs::strlen($ctx, $ret->extension);
		if ($ext_length > 0)
		{
			$ext_length++;
		}
		$ret->filename = \Runtime\rs::substr($ctx, $ret->basename, 0, -1 * $ext_length);
		return $ret;
	}
	/**
	 * Возвращает имя файла без расширения
	 * @param {string} filepath - путь к файлу
	 * @return {string} полное имя файла
	 */
	static function filename($ctx, $filepath)
	{
		$ret = \Runtime\rs::pathinfo($ctx, $filepath);
		$res = $ret->basename;
		$ext = $ret->extension;
		if ($ext != "")
		{
			$sz = 0 - \Runtime\rs::strlen($ctx, $ext) - 1;
			$res = \Runtime\rs::substr($ctx, $res, 0, $sz);
		}
		return $res;
	}
	/**
	 * Возвращает полное имя файла
	 * @param {string} filepath - путь к файлу
	 * @return {string} полное имя файла
	 */
	static function basename($ctx, $filepath)
	{
		$ret = \Runtime\rs::pathinfo($ctx, $filepath);
		$res = $ret->basename;
		return $res;
	}
	/**
	 * Возвращает расширение файла
	 * @param {string} filepath - путь к файлу
	 * @return {string} расширение файла
	 */
	static function extname($ctx, $filepath)
	{
		$ret = \Runtime\rs::pathinfo($ctx, $filepath);
		$res = $ret->extension;
		return $res;
	}
	/**
	 * Возвращает путь к папке, содержащий файл
	 * @param {string} filepath - путь к файлу
	 * @return {string} путь к папке, содержащий файл
	 */
	static function dirname($ctx, $filepath)
	{
		$ret = \Runtime\rs::pathinfo($ctx, $filepath);
		$res = $ret->dirname;
		return $res;
	}
	/**
	 * Returns relative path of the filepath
	 * @param string filepath
	 * @param string basepath
	 * @param string ch - Directory separator
	 * @return string relative path
	 */
	static function relativePath($ctx, $filepath, $basepath, $ch="/")
	{
		$source = \Runtime\rs::explode($ctx, $ch, $filepath);
		$base = \Runtime\rs::explode($ctx, $ch, $basepath);
		$source = $source->filter($ctx, function ($ctx, $s)
		{
			return $s != "";
		});
		$base = $base->filter($ctx, function ($ctx, $s)
		{
			return $s != "";
		});
		$i = 0;
		while ($source->count($ctx) > 0 && $base->count($ctx) > 0 && $source->item($ctx, 0) == $base->item($ctx, 0))
		{
			$source->shift($ctx);
			$base->shift($ctx);
		}
		$base->each($ctx, function ($ctx, $s) use (&$source)
		{
			$source->unshift($ctx, "..");
		});
		return \Runtime\rs::implode($ctx, $ch, $source);
	}
	/**
	 * Return normalize path
	 * @param string filepath - File path
	 * @return string
	 */
	static function normalize($ctx, $filepath)
	{
		return $filepath;
	}
	/**
	 * New line to br
	 */
	static function nl2br($ctx, $s)
	{
		return static::replace($ctx, "\n", "<br/>", $s);
	}
	/* =================== Deprecated =================== */
	/**
	 * Разбивает строку на подстроки
	 * @param string delimiter - разделитель
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Vector<string>
	 */
	static function explode($ctx, $delimiter, $s, $limit=-1)
	{
		$arr = [];
		if ($limit < 0) $arr = explode($delimiter, $s);
		else $arr = explode($delimiter, $s, $limit);
		return Collection::from($arr);
	}
	/**
	 * Разбивает строку на подстроки
	 * @param string ch - разделитель
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Vector<string>
	 */
	static function implode($ctx, $ch, $arr)
	{
		return implode($s, $arr->_getArr());
	}
	/**
	 * Ищет позицию первого вхождения подстроки search в строке s.
	 * @param {string} s - строка, в которой производится поиск 
	 * @param {string} search - строка, которую ищем 
	 * @param {string} offset - если этот параметр указан, 
	 *                 то поиск будет начат с указанного количества символов с начала строки.  
	 * @return {variable} Если строка найдена, то возвращает позицию вхождения, начиная с 0.
	 *                    Если строка не найдена, то вернет -1
	 */
	static function strpos($ctx, $s, $search, $offset=0)
	{
		if ($search == ""){
			return -1;
		}
		$res = mb_strpos($s, $search, $offset);
		if ($res === false)
			return -1;
		return $res;
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.rs";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.rs";
	}
	static function getParentClassName()
	{
		return "";
	}
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.rs",
			"name"=>"Runtime.rs",
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