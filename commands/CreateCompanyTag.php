<?php
/**
 * Takes a string and strips all non-alphanumerical characters
 *
 * @param string $name
 * @return string $tag
 */
namespace app\commands;

class CreateCompanyTag extends TrimNonAlphaNumeric{
	public function run($company, $customer){
		$company = TrimNonAlphaNumeric($company);
		$customer = TrimNonAlphaNumeric($customer);
		
		$tag = "{$customer}_{$company}";
	
		return $tag;
	}
}
?>