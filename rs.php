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
use Runtime\PathInfo;
use Runtime\Vector;
class rs{
	/**
	 * Returns string lenght
	 * @param string s The string
	 * @return int
	 */
	
	static function strlen(&$s)
	{
		if (gettype($s) != "string") return 0;
		return @mb_strlen($s);
	}
	/**
	 * Returns substring
	 * @param string s The string
	 * @param int start
	 * @param int length
	 * @return string
	 */
	
	static function substr(&$s, $start, $length = null){
		return mb_substr($s, $start, $length);
	}
	/**
	 * Returns char from string at the position
	 * @param string s The string
	 * @param int pos The position
	 * @return string
	 */
	static function charAt($s, $pos){
		$sz = static::strlen($s);
		if ($pos >= 0 && $pos < $sz){
			return mb_substr($s, $pos, 1);
		}
		return "";
	}
	/**
	 * Returns ASCII symbol code
	 * @param char ch
	 */
	
	static function ord($s){	
		if ($s == "") return 0;
		$s1 = mb_convert_encoding($s, 'UCS-4LE', 'UTF-8');
		$result = @unpack('V', $s1);
		if ($result) return $result[1];
		/*return mb_ord($s);*/
		return 0;
	}
	/**
	 * Convert string to lower case
	 * @param string s 
	 * @return string
	 */
	
	static function strtolower($s){
		return mb_strtolower($s);
	}
	/**
	 * Convert string to upper case
	 * @param string s
	 * @return string
	 */
	
	static function strtoupper($s){
		return mb_strtoupper($s);
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
	
	static function strpos($s, $search, $offset = 0){
		if ($search == ""){
			return -1;
		}
		$res = mb_strpos($s, $search, $offset);
		if ($res === false)
			return -1;
		return $res;
	}
	/**
	 * Заменяет одну строку на другую
	 */
	
	static function replace($search, $item, $s)
	{
		return str_replace($search, $item, $s);
	}
	/**
	 * Возвращает повторяющуюся строку
	 * @param {string} s - повторяемая строка
	 * @param {integer} n - количество раз, которые нужно повторить строку s
	 * @return {string} строка
	 */
	
	static function str_repeat($s, $n){
		if ($n <= 0) return "";
		return str_repeat($s, $n);
	}
	/**
	 * Разбивает строку на подстроки
	 * @param string ch - разделитель
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Vector<string>
	 */
	
	static function split($delimiters, $s, $limit = -1)
	{
		$res = new Vector();
		$pattern = "[".implode("", $delimiters->_getArr())."]";
		$pattern = str_replace("/", "\/", $pattern);
		$arr = preg_split("/".$pattern."/", $s, $limit);
		$res->_assignArr($arr);
		return $res;
	}
	/**
	 * Разбивает строку на подстроки
	 * @param string ch - разделитель
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Vector<string>
	 */
	
	static function explode($delimiter, $s, $limit = -1)
	{
		$res = new Vector();
		$arr = [];
		if ($limit < 0) $arr = explode($delimiter, $s);
		else $arr = explode($delimiter, $s, $limit);
		$res->_assignArr($arr);
		return $res;
	}
	/**
	 * Разбивает строку на подстроки
	 * @param string ch - разделитель
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Vector<string>
	 */
	
	static function implode($s, $arr){
		return implode($s, $arr->_getArr());
	}
	/**
	 * Удаляет лишние символы слева и справа
	 * @param {string} s - входная строка
	 * @return {integer} новая строка
	 */
	
	static function trim($s, $ch=""){
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
	
	static function json_encode($s, $flags = 0){
		$flags = $flags || JSON_UNESCAPED_UNICODE;
		return json_encode($s, JSON_UNESCAPED_UNICODE);
	}
	/**
	 * Escape HTML special chars
	 * @param string s
	 * @return string
	 */
	
	static function htmlEscape($s){
		if ($s instanceof \Runtime\Collection) return $s;
		if ($s instanceof \Runtime\UIStruct) return $s;
		return htmlspecialchars($s, ENT_QUOTES | ENT_HTML401);
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
	static function pathinfo($filepath){
		$arr1 = rs::explode(".", $filepath);
		$arr2 = rs::explode("/", $filepath);
		$ret = new PathInfo();
		$ret->filepath = $filepath;
		$ret->extension = $arr1->pop();
		$ret->basename = $arr2->pop();
		$ret->dirname = rs::implode("/", $arr2);
		$ext_length = rs::strlen($ret->extension);
		if ($ext_length > 0){
			$ext_length++;
		}
		$ret->filename = rs::substr($ret->basename, 0, -1 * $ext_length);
		return $ret;
	}
	/**
	 * Возвращает имя файла без расширения
	 * @param {string} filepath - путь к файлу
	 * @return {string} полное имя файла
	 */
	static function filename($filepath){
		$ret = (new \Runtime\Callback(self::class, "pathinfo"))($filepath);
		$res = $ret->basename;
		$ext = $ret->extension;
		if ($ext != ""){
			$sz = 0 - rs::strlen($ext) - 1;
			$res = rs::substr($res, 0, $sz);
		}
		return $res;
	}
	/**
	 * Возвращает полное имя файла
	 * @param {string} filepath - путь к файлу
	 * @return {string} полное имя файла
	 */
	static function basename($filepath){
		$ret = (new \Runtime\Callback(self::class, "pathinfo"))($filepath);
		$res = $ret->basename;
		return $res;
	}
	/**
	 * Возвращает расширение файла
	 * @param {string} filepath - путь к файлу
	 * @return {string} расширение файла
	 */
	static function extname($filepath){
		$ret = (new \Runtime\Callback(self::class, "pathinfo"))($filepath);
		$res = $ret->extension;
		return $res;
	}
	/**
	 * Возвращает путь к папке, содержащий файл
	 * @param {string} filepath - путь к файлу
	 * @return {string} путь к папке, содержащий файл
	 */
	static function dirname($filepath){
		$ret = (new \Runtime\Callback(self::class, "pathinfo"))($filepath);
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
	static function relativePath($filepath, $basepath, $ch = "/"){
		$source = rs::explode($ch, $filepath);
		$base = rs::explode($ch, $basepath);
		$source = $source->filter(function ($s){
			return $s != "";
		});
		$base = $base->filter(function ($s){
			return $s != "";
		});
		$i = 0;
		while ($source->count() > 0 && $base->count() > 0 && $source->item(0) == $base->item(0)){
			$source->shift();
			$base->shift();
		}
		$base->each(function ($s) use (&$source){
			$source->unshift("..");
		});
		return rs::implode($ch, $source);
	}
	/**
	 * Return normalize path
	 * @param string filepath - File path
	 * @return string
	 */
	static function normalize($filepath){
		return $filepath;
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.rs";}
	public static function getCurrentNamespace(){return "Runtime";}
	public static function getCurrentClassName(){return "Runtime.rs";}
	public static function getParentClassName(){return "";}
	public static function getFieldsList($names, $flag=0){
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
	public static function getMethodsList($names){
	}
	public static function getMethodInfoByName($method_name){
		return null;
	}
}