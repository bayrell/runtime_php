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
namespace Runtime;
use Runtime\rtl;
use Runtime\Vector;
class rs{
	/**
	 * Returns string lenght
	 * @param string s The string
	 * @return int
	 */
	
	static function strlen(&$s){
		return mb_strlen($s);
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
	
	static function explode($delimiter, $s, $limit = -1){
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
		return htmlspecialchars($s, ENT_QUOTES | ENT_HTML401);
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.rs";}
	public static function getParentClassName(){return "";}
}