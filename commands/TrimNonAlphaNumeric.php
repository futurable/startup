<?php
/**
 * Takes a string and strips all non-alphanumerical characters
 *
 * @param string $name
 * @return string $tag
 */
namespace app\commands;

class TrimNonAlphaNumeric{
	public function run($name){
		// Name to lowercase
		$tag = strtolower($name);
	
		// Replace accented letters
		$removeAccents = new RemoveAccents();
		$tag = $removeAccents->run($tag);
	
		// Remove illegal characters
		$tag = preg_replace("/[^A-Za-z0-9]/", '', $tag);
	
		return $tag;
	}
}
?>