<?php

/**
 * LTI KeySet for LTI Keys
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @author      Steve Pedersen (pedersen@sfsu.edu)
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

    public function generateKeySet ($alg = 'RS256')
    {
        $this->id = self::UUID();
        $this->url = $this->getApplication()->baseUrl("/lti/jwks/$this->id");
        $this->save();

        $this->keys = $this->getSchema('At_LTI_Key')->createInstance()->generateKeys($this, $alg);
        $this->save();
        $this->keys->save();

        return $this;
    }

    public static function UUID($data = null)
    {
        $data = $data ?? random_bytes(16);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
