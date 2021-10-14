<?php namespace App\Enum;

class DebitStatus {
	
	public static function pending() {
		return "PENDING";
	} 

	public static function validate() {
		return "VALIDATE";
	}

	public static function paid() {
		return "PAID";
	}
}