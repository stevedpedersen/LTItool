<?php

/**
 */
class LTITool_AuthN_Role extends Bss_ActiveRecord_BaseWithAuthorization
{
    public static function SchemaInfo ()
    {
        return array(
            '__type' => 'ltitool_authn_roles',
            '__pk' => array('id'),
            '__azidPrefix' => 'at:ltitool:authN/Role/',
            
            'id' => 'int',
            'name' => 'string',
            'description' => 'string',
            'isSystemRole' => array('bool', 'nativeName' => 'is_system_role'),
            
            'accounts' => array('N:M', 'to' => 'Bss_AuthN_Account', 'via' => 'ltitool_authn_account_roles', 'toPrefix' => 'account', 'fromPrefix' => 'role'),
        );
    }
}
