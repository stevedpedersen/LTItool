<?php

/**
 * LTI KeySet for LTI Keys
 * 
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_LTI_KeySet extends Bss_ActiveRecord_Base
{  
    public static function SchemaInfo ()
    {
        return [
            '__type' => 'at_lti_key_set',
            '__pk' => array('id'),
            
            'id' => 'string',
            'url' => 'string',

            'keys' => ['1:N', 'to' => 'At_LTI_Key', 'reverseOf' => 'keySet'],
        ];
    }

    public function generateKeySet ($keySetId, $url, $alg)
    {
        $keySet = $this->createInstance();
        $keySet->id = $keySetId;
        $keySet->url = $url;
        $keySet->save();

        $public = $this->getSchema('At_LTI_Key')->createInstance();
        $private = $this->getSchema('At_LTI_Key')->createInstance();
        list($pubKey, $pivKey) = $public->generateKeys('rs256');


    }
}
