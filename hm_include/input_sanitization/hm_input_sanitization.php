<?php

if ( ! defined('BASEPATH')) exit('403');

class Input_Sanitization{
	
	/** Bỏ tab và xuống dòng /r/r */
	public function remove_tab_line($input=NULL){
		return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	}
	
	/** Lọc input kiểu email */
	public function email($input=NULL){
		return filter_var($input, FILTER_SANITIZE_EMAIL);
	}
	
	/** Mã hóa ký tự đặc biệt */
	public function encoded($input=NULL){
		return filter_var($input, FILTER_SANITIZE_ENCODED);
	}
	
	/** magic_quotes */
	public function magic_quotes($input=NULL){
		return filter_var($input, FILTER_SANITIZE_MAGIC_QUOTES);
	}
	
	/** Chỉ lấy chữ, số + - */
	public function number_float($input=NULL){
		return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT);
	}
	
	/** Chỉ lấy số + - */
	public function number_int($input=NULL){
		return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
	}
	
	/** HTML - escape */
	public function special_chars($input=NULL){
		return filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
	}
	
	/** full_special_chars */
	public function full_special_chars($input=NULL){
		return filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	
	/** string */
	public function string($input=NULL){
		return filter_var($input, FILTER_SANITIZE_STRING);
	}
	
	/** stripped */
	public function stripped($input=NULL){
		return filter_var($input, FILTER_SANITIZE_STRIPPED);
	}
	
	/** url */
	public function url($input=NULL){
		return filter_var($input, FILTER_SANITIZE_URL);
	}
	
	/** unsafe_raw */
	public function unsafe_raw($input=NULL){
		return filter_var($input, FILTER_UNSAFE_RAW);
	}
	
}