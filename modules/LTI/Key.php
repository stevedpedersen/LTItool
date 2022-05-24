<?php

/**
 * LTI Key implementation
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @author      Steve Pedersen (pedersen@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_LTI_Key extends Bss_ActiveRecord_Base
{  
    public static function SchemaInfo ()
    {
        return [
            '__type' => 'at_lti_key',
            '__pk' => array('id'),
            
            'id' => 'int',
            'publicKey' => ['string', 'nativeName' => 'public_key'],
            'privateKey' => ['string', 'nativeName' => 'private_key'],
            'keySetId' => ['string', 'nativeName' => 'key_set_id'],
            'alg' => 'string',

            'keySet' => ['1:1', 'to' => 'At_LTI_KeySet', 'keyMap' => ['key_set_id' => 'id']],
        ];
    }

    public function generateKeys ($keySet, $alg = 'RS256')
    {
        $config = [
            "digest_alg" => $alg,
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];
           
        // Create the private and public key
        $result = openssl_pkey_new($config);

        // Extract the private key from $result to $private
        openssl_pkey_export($result, $private);

        // Extract the public key from $result to $public
        $public = openssl_pkey_get_details($result);

        $this->publicKey = $public['key'];
        $this->privateKey = $private;
        $this->keySetId = $keySet->id;
        $this->alg = $alg;
        $this->save();

        return $this;
    }
}
