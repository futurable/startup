<?php
namespace app\commands;

use app\models\Company;
class CreateBusinessID{

    public function run($prefix = false, $country = false) {
        if(!$country) $country = 'FI';
 
        if($country == 'FI'){
            $businessID = $this->createFinnishBusinessID($prefix);
        }
        else $businessID = false;

        return $businessID;
    }
    
    /*
     * Creates business ID with verification code
     * 
     * @param   int     $prefix     Business id without verification code. Default false
     */
    private function createFinnishBusinessID($prefix = false){
        $error = null;
        
        if(!$prefix){
            $businessID =  Company::find()
            	->select('business_id')
            	->orderBy('id DESC')
            	->one()
            	->business_id;

            if($businessID == null){
                // DB is empty, create a random first business id
                $businessID = $businessID = "9050".(rand(200,300));    
            };
            $prefix = substr($businessID, 0, 7) + 1;
        }
        
        if(!is_int((int)$prefix)) $error = "Business ID prefix must be an integer";
        elseif(strlen($prefix) != 7) $error = "Business ID prefix must be 7 characters";

        if($error == null){
            $remainder = $this->getBusinessIDsumRemainder($prefix);

            // Checksum 1 is illegal, so skip ID:s that have it
            if($remainder == 1){
                $prefix++;
                $remainder = $this->getBusinessIDsumRemainder($prefix);
            }
            
            if($remainder == 0) $checksum = 0;
            elseif($remainder > 1) $checksum = 11 - $remainder;
        }

        if(empty($error)){
            $businessID = $prefix."-".$checksum;
        }
        else{
            $businessID = false;
            // Only return error if debug is on
            if($this->debug === true) $businessID = $error;
        }
        
        return $businessID;
    }
    
    private function getBusinessIDsumRemainder($prefix){
        // Multiplying factors
        $multipliers = array(7,9,10,5,8,4,2);

        $sum = 0;
        for($i=0;$i<7;$i++){
           $sum += (int)(substr($prefix,$i,1) * $multipliers[$i]);
        }

        $remainder = $sum % 11;
        
        return $remainder;
    }

}
?>