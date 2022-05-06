<?php

/**
 * LTI Deployment implementation
 * 
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_LTI_Deployment extends Bss_ActiveRecord_Base
{  
    public static function SchemaInfo ()
    {
        return [
            '__type' => 'at_lti_deployment',
            '__pk' => array('id'),
            
            'id' => 'string',
            'customerId' => ['string', 'nativeName' => 'customer_id'],
            'service' => 'string',

            'registration' => ['1:1', 'to' => 'At_LTI_Registration', ['registration_id' => 'id'],],
        ];
    }
}
