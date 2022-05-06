<?php

/**
 * LTI Registration implementation
 * 
 * may be tricky to figure out with all the config stuff being mixed together in example
 * find out what stuff needed for tools vs platform
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_LTI_Registration extends Bss_ActiveRecord_Base
{  
    public static function SchemaInfo ()
    {
        return [
            '__type' => 'at_lti_registration',
            '__pk' => array('id'),
            
            'id' => 'string',
            'clientId' => ['string', 'nativeName' => 'client_id'],
            'issuer' => 'string',
            'platform_login_auth_endpoint' => 'string',
            'platform_service_auth_endpoint' => 'string',
            'platform_jwks_endpoint' => 'string',
            'platform_auth_provider' => 'string',
            'private_key' => 'string',
            'key_set_id' => 'string',

            'keySet' => ['1:1', 'to' => 'At_LTI_KeySet', 'keyMap' => ['key_set_id' => 'id']],
        ];
    }
}
