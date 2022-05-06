<?php

/**
 * LTI Key implementation
 * 
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_LTI_Key extends Bss_ActiveRecord_Base
{  
    public static function SchemaInfo ()
    {
        return [
            '__type' => 'at_lti_key_set',
            '__pk' => array('id'),
            
            'id' => 'string',
            'privateKey' => ['string', 'nativeName' => 'private_key'],
            'alg' => 'string',

            'keySet' => ['1:1', 'to' => 'At_LTI_KeySet', 'keyMap' => ['key_set_id' => 'id']],
        ];
    }

    public function generateKeys ($alg = 'RS256')
    {
        $config = array(
            "digest_alg" => $alg,
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
           
        // Create the private and public key
        $res = openssl_pkey_new($config);

        // Extract the private key from $res to $privKey
        openssl_pkey_export($res, $privKey);

        // Extract the public key from $res to $pubKey
        $pubKey = openssl_pkey_get_details($res);
        echo "<pre>"; var_dump($pubKey, $privKey); die;

        return [$pubKey['key'], $privKey['key']];
    }
}
