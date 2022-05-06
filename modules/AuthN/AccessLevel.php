<?php

/**
 */
class LTITool_AuthN_AccessLevel extends Bss_ActiveRecord_BaseWithAuthorization
{
    public static function SchemaInfo ()
    {
        return array(
            '__class' => 'LTITool_AuthN_AccessLevelSchema',
            '__type' => 'ltitool_authn_access_levels',
            '__pk' => array('id'),
            '__azidPrefix' => 'at:ltitool:authN/AccessLevel/',
            
            'id' => 'int',
            'name' => 'string',
            'description' => 'string',
        );
    }
}
