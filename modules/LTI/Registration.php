<?php

/**
 * LTI Registration implementation
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @author      Steve Pedersen (pedersen@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_LTI_Registration extends Bss_ActiveRecord_Base
{  
    public static function SchemaInfo ()
    {
        return [
            '__type' => 'at_lti_registration',
            '__pk' => ['id'],
            
            'id' => 'string',
            'client_id' => 'string',
            'issuer' => 'string',
            'platform_login_auth_endpoint' => 'string',
            'platform_service_auth_endpoint' => 'string',
            'platform_jwks_endpoint' => 'string',
            'platform_auth_provider' => 'string',
            'toolProvider' => ['string', 'nativeName' => 'tool_provider'],
            'key_set_id' => 'string',

            'createdDate' => ['datetime', 'nativeName' => 'created_date'],
            'modifiedDate' => ['datetime', 'nativeName' => 'modified_date'],

            'keySet' => ['1:1', 'to' => 'At_LTI_KeySet', 'keyMap' => ['key_set_id' => 'id']],
        ];
    }

    public function getToolExtension ()
    {
        return $this->getApplication()->getModuleManager()->getExtensionByName(
            'at:ltitool:lti/lti-toolprovider', $this->toolProvider
        );
    }
}
