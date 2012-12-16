<?php

class Utils extends CApplicationComponent
{
    public static function trimString($string, $maxChars)
    {
		if (strlen($string) > $maxChars) {
			$string = substr($string, 0, $maxChars - 3) . '...';
		}
		
		return $string;
    }
}
?>
