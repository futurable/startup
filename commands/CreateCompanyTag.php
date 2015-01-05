<?php
/**
 * Takes a string and strips all non-alphanumerical characters
 *
 * @param string $name
 * @return string $tag
 */
namespace app\commands;

class CreateCompanyTag{
	public function run($company, $customer){
		$Trimmer = new TrimNonAlphaNumeric();
		
		$company = $Trimmer->run($company);
		$customer = $Trimmer->run($customer);
		
		$tag = "{$customer}_{$company}";
		
		// Allow 32 characters or less
		$tag = substr($tag, 0, 32);
		
		return $tag;
	}
}
?>