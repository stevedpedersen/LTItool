<?php

/**
 * Upgrade this module.
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @author      Steve Pedersen (pedersen@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_LTI_ModuleUpgradeHandler extends Bss_ActiveRecord_BaseModuleUpgradeHandler
{
    public function onModuleUpgrade ($fromVersion)
    {
        switch ($fromVersion)
        {
            case 0:
                $def = $this->createEntityType('at_lti_key_set', $this->getDataSource('At_LTI_KeySet'));
                $def->addProperty('id', 'string', array('primaryKey' => true));
                $def->addProperty('url', 'string');
                $def->save();

                $def = $this->createEntityType('at_lti_key', $this->getDataSource('At_LTI_Key'));
                $def->addProperty('id', 'int', array('primaryKey' => true, 'sequence' => true));
                $def->addProperty('key_set_id', 'string');
                $def->addProperty('public_key', 'string');
                $def->addProperty('private_key', 'string');
                $def->addProperty('alg', 'string');
                $def->save();

                $def = $this->createEntityType('at_lti_registration', $this->getDataSource('At_LTI_Registration'));
                $def->addProperty('id', 'string', array('primaryKey' => true));
                $def->addProperty('issuer', 'string');
                $def->addProperty('client_id', 'string');
                $def->addProperty('key_set_id', 'string');
                $def->addProperty('platform_login_auth_endpoint', 'string');
                $def->addProperty('platform_service_auth_endpoint', 'string');
                $def->addProperty('platform_jwks_endpoint', 'string');
                $def->addProperty('platform_auth_provider', 'string');
                $def->addProperty('tool_provider', 'string');
                $def->addProperty('created_date', 'datetime');
                $def->addProperty('modified_date', 'datetime');
                $def->save();

                $def = $this->createEntityType('at_lti_deployment', $this->getDataSource('At_LTI_Deployment'));
                $def->addProperty('id', 'string', array('primaryKey' => true));
                $def->addProperty('registration_id', 'string');
                $def->addProperty('customer_id', 'string');
                $def->addProperty('service', 'string');
                $def->save();

                // $registrationIdMap = $this->insertData($this->getRegistrationData(), 'at_lti_registration', 'At_LTI_Registration');
                // $deploymentIdMap = $this->insertData($this->getDeploymentData($registrationIdMap), 'at_lti_deployment', 'At_LTI_Deployment');
                // $keySets = $this->insertData($this->getKeySetData(), 'at_lti_key_set', 'At_LTI_KeySet');
                // $keys = $this->insertData($this->getKeyData($keySets), 'at_lti_key', 'At_LTI_Key');

                break;
        }
    }

    private function insertData ($data, $table, $dataSource)
    {
        $entries = [];
        foreach ($data as $items)
        {
            $record = [];
            foreach ($items as $key => $value)
            {
                $record[$key] = $value;
            }
            $entries[] = $record;
        }

        $this->useDataSource($dataSource);

        return $this->insertRecords($table, $entries);
    }

    private function getDeploymentData ($registrations)
    {
        return [
            [
                "id" => "8c49a5fa-f955-405e-865f-3d7e959e809f",
                "registration_id" => hash('sha1', 'https://spedersen18.dev.at.sfsu.edu/ltitool'),
                "customer_id" => "",
                "service" => "https://spedersen18.dev.at.sfsu.edu/ltitool",
            ],
            [
                "id" => "1",
                "registration_id" => hash('sha1', "https://spedersen18.dev.at.sfsu.edu/ilearn39"),
                "customer_id" => "",
                "service" => "https://spedersen18.dev.at.sfsu.edu/ilearn39",
            ],
            [
                "id" => "209:0b1c0dce1ebe70f1871f52b029433b0569eaf2ea",
                "registration_id" => hash('sha1', "https://sfsu.beta.instructure.com"),
                "customer_id" => "",
                "service" => "https://sfsu.beta.instructure.com",
            ]
        ];
    }

    private function getRegistrationData ()
    {
        return [
            [
                "id" => hash('sha1', 'https://spedersen18.dev.at.sfsu.edu/ltitool'),
                "issuer" => "https://spedersen18.dev.at.sfsu.edu/ltitool",
                "client_id" => "d42df408-70f5-4b60-8274-6c98d3b9468d",
                "key_set_id" => "58f36e10-c1c1-4df0-af8b-85c857d1634f",
                "platform_login_auth_endpoint" => "https://spedersen18.dev.at.sfsu.edu/ltitool/platform/login",
                "platform_service_auth_endpoint" => "https://spedersen18.dev.at.sfsu.edu/ltitool/platform/token",
                "platform_jwks_endpoint" => "https://spedersen18.dev.at.sfsu.edu/ltitool/platform/jwks",
                "platform_auth_provider" => "",
                "tool_provider" => "breakout",
            ],
            [
                "id" => hash('sha1', "https://spedersen18.dev.at.sfsu.edu/ilearn39"),
                "issuer" => "https://spedersen18.dev.at.sfsu.edu/ilearn39",
                "client_id" => "6gVH1w2DwwY54C1",
                "key_set_id" => "301a3597869ba93496e2",
                "platform_login_auth_endpoint" => "https://spedersen18.dev.at.sfsu.edu/ilearn39/mod/lti/auth.php",
                "platform_service_auth_endpoint" => "https://spedersen18.dev.at.sfsu.edu/ilearn39/mod/lti/token.php",
                "platform_jwks_endpoint" => "https://spedersen18.dev.at.sfsu.edu/ilearn39/mod/lti/certs.php",
                "platform_auth_provider" => "",
                "tool_provider" => "breakout",
            ],
            [
                "id" => hash('sha1', "https://sfsu.beta.instructure.com"),
                "issuer" => "https://sfsu.beta.instructure.com",
                "client_id" => "211650000000000114",
                "key_set_id" => "301a3597869ba93496e2",
                "platform_login_auth_endpoint" => "https://sfsu.beta.instructure.com/api/lti/authorize_redirect",
                "platform_service_auth_endpoint" => "https://sfsu.beta.instructure.com/login/oauth2/token",
                "platform_jwks_endpoint" => "https://canvas.instructure.com/api/lti/security/jwks",
                "platform_auth_provider" => "",
                "tool_provider" => "breakout",
            ]
        ];
    }

    private function getKeySetData ()
    {
        return [
            [
                "id" => "58f36e10-c1c1-4df0-af8b-85c857d1634f",
                "url" => "https://spedersen18.dev.at.sfsu.edu/ltitool/lti/jwks/58f36e10-c1c1-4df0-af8b-85c857d1634f",
            ],
            [
                "id" => "301a3597869ba93496e2",
                "url" => "https://spedersen18.dev.at.sfsu.edu/ltitool/lti/jwks/301a3597869ba93496e2",
            ]
        ];
    }

    private function getKeyData ($keySets)
    {
        return [
            [
                'id' => ['type' => 'int', 'sequence' => true],
                "key_set_id" => "58f36e10-c1c1-4df0-af8b-85c857d1634f",
                "alg" => "RS256",
                "public_key" => "",
                "private_key" => 
"-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEA8osiSa75nmqmakwNNocLA2N2huWM9At/tjSZOFX1r4+PDclS
zxhMw+ZcgHH+E/05Ec6Vcfd75i8Z+Bxu4ctbYk2FNIvRMN5UgWqxZ5Pf70n8UFxj
GqdwhUA7/n5KOFoUd9F6wLKa6Oh3OzE6v9+O3y6qL40XhZxNrJjCqxSEkLkOK3xJ
0J2npuZ59kipDEDZkRTWz3al09wQ0nvAgCc96DGH+jCgy0msA0OZQ9SmDE9CCMbD
T86ogLugPFCvo5g5zqBBX9Ak3czsuLS6Ni9Wco8ZSxoaCIsPXK0RJpt6Jvbjclqb
4imsobifxy5LsAV0l/weNWmU2DpzJsLgeK6VVwIDAQABAoIBAQC2R1RUdfjJUrOQ
rWk8so7XVBfO15NwEXhAkhUYnpmPAF/tZ4EhfMysaWLZcVIW6bbLKCtuRCVMX9ev
fIbkkLU0ErhqPi3QATcXL/z1r8+bAUprhpNAg9fvfM/ZukXDRged6MPNMC11nseE
p8HUU4oHNwXVyL6FvmstrHyYoEnkjIiMk34O2MFjAavoIJhM0gkoXVnxRP5MNi1n
GPVhK+TfZyRri20x1Rh3CsIq36PUyxCICWkD7jftLGqVdQBfuii600LP5v7nuHz9
LDsCeY7xRJu0eLdDk7/9ukb8fuq6/+3VYMYChYWvpw4DaH8qDHxZfWzMyaI489ma
l27lhgdxAoGBAPkxH6WuZM/GOowjySuruRjAVyJ4stfe9l/x8MrqnFA2Q8stqK69
60Y9LDrSaAx7QutvzZ64br2WMlvnGdJw868z4/JmvoAqW3IHUXzqRAHgOk/8Y3ze
Sjd7t3R0O3v6qAbQjyRYYgfAMZo7PzXW8FKNGsakAedEKW0b94HYndKpAoGBAPkr
grtARp2nnd1WGuxgQMjX++HjT0p9x7fTMCtfvYhZguU9AlCx53VHFeGc6fqsDkUm
BFv0dqMnw0TPzEQqLElBIh87TGS4JSXmcbQcejIx+ry2kMFuyMZIPuvZCnLfB/d7
Qu2DU6mdeIBME/8AX5kBqn1ekddioESdSkHkkif/AoGAaPCeAjjZ7YHuP/wGCOUN
UvYU+8hWkIAtwyPxIpMAdusTS6oTwlrqjK7QRIk9FhyGhv2TWwcSY7avyHIfNrco
eBzjHr7T9MdhsTiRwYgqUZvrEqoX/4rhOFJaZKlaL5DUV+JWlZi+18LBYNEYgoTc
ufcAUqzYvFrBE1jWt5DQjdkCgYATs6sMn1J2GNDUtYA/fITi3KEgBVc5rqRiFqLS
aymTZHCDK8XJF6gTj+FdC4k8tuoR8aWal8Phtr0r7bpbEXKbADlwesHZnO3jB0uq
UC4hVe5biZv8j4P0mbXP9ENtPdFlciuimCW/XaIvktRp71+fu4/9hcLGYxgFFOLQ
PwCHhQKBgGMCxIcueUkLnI9r0KkjtXap9mIgdgERwQPN0Cm9Tx35ZEzRp95kf4C6
MPsVOwZk5gNvvQngx4iaw9fNYG+PF2yNuDZ+EFwI0vpmGCKRQEke9/VCOFucMsjg
jMhbU+jrqRIJKisP7MCE1NRhymCPpQf/stEPl0nS5rj+mZJHQEGq
-----END RSA PRIVATE KEY-----",
            ],
            [
                'id' => ['type' => 'int', 'sequence' => true],
                "key_set_id" => "301a3597869ba93496e2",
                "alg" => "RS256",
                "public_key" => "",
                "private_key" => 
"-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEA8osiSa75nmqmakwNNocLA2N2huWM9At/tjSZOFX1r4+PDclS
zxhMw+ZcgHH+E/05Ec6Vcfd75i8Z+Bxu4ctbYk2FNIvRMN5UgWqxZ5Pf70n8UFxj
GqdwhUA7/n5KOFoUd9F6wLKa6Oh3OzE6v9+O3y6qL40XhZxNrJjCqxSEkLkOK3xJ
0J2npuZ59kipDEDZkRTWz3al09wQ0nvAgCc96DGH+jCgy0msA0OZQ9SmDE9CCMbD
T86ogLugPFCvo5g5zqBBX9Ak3czsuLS6Ni9Wco8ZSxoaCIsPXK0RJpt6Jvbjclqb
4imsobifxy5LsAV0l/weNWmU2DpzJsLgeK6VVwIDAQABAoIBAQC2R1RUdfjJUrOQ
rWk8so7XVBfO15NwEXhAkhUYnpmPAF/tZ4EhfMysaWLZcVIW6bbLKCtuRCVMX9ev
fIbkkLU0ErhqPi3QATcXL/z1r8+bAUprhpNAg9fvfM/ZukXDRged6MPNMC11nseE
p8HUU4oHNwXVyL6FvmstrHyYoEnkjIiMk34O2MFjAavoIJhM0gkoXVnxRP5MNi1n
GPVhK+TfZyRri20x1Rh3CsIq36PUyxCICWkD7jftLGqVdQBfuii600LP5v7nuHz9
LDsCeY7xRJu0eLdDk7/9ukb8fuq6/+3VYMYChYWvpw4DaH8qDHxZfWzMyaI489ma
l27lhgdxAoGBAPkxH6WuZM/GOowjySuruRjAVyJ4stfe9l/x8MrqnFA2Q8stqK69
60Y9LDrSaAx7QutvzZ64br2WMlvnGdJw868z4/JmvoAqW3IHUXzqRAHgOk/8Y3ze
Sjd7t3R0O3v6qAbQjyRYYgfAMZo7PzXW8FKNGsakAedEKW0b94HYndKpAoGBAPkr
grtARp2nnd1WGuxgQMjX++HjT0p9x7fTMCtfvYhZguU9AlCx53VHFeGc6fqsDkUm
BFv0dqMnw0TPzEQqLElBIh87TGS4JSXmcbQcejIx+ry2kMFuyMZIPuvZCnLfB/d7
Qu2DU6mdeIBME/8AX5kBqn1ekddioESdSkHkkif/AoGAaPCeAjjZ7YHuP/wGCOUN
UvYU+8hWkIAtwyPxIpMAdusTS6oTwlrqjK7QRIk9FhyGhv2TWwcSY7avyHIfNrco
eBzjHr7T9MdhsTiRwYgqUZvrEqoX/4rhOFJaZKlaL5DUV+JWlZi+18LBYNEYgoTc
ufcAUqzYvFrBE1jWt5DQjdkCgYATs6sMn1J2GNDUtYA/fITi3KEgBVc5rqRiFqLS
aymTZHCDK8XJF6gTj+FdC4k8tuoR8aWal8Phtr0r7bpbEXKbADlwesHZnO3jB0uq
UC4hVe5biZv8j4P0mbXP9ENtPdFlciuimCW/XaIvktRp71+fu4/9hcLGYxgFFOLQ
PwCHhQKBgGMCxIcueUkLnI9r0KkjtXap9mIgdgERwQPN0Cm9Tx35ZEzRp95kf4C6
MPsVOwZk5gNvvQngx4iaw9fNYG+PF2yNuDZ+EFwI0vpmGCKRQEke9/VCOFucMsjg
jMhbU+jrqRIJKisP7MCE1NRhymCPpQf/stEPl0nS5rj+mZJHQEGq
-----END RSA PRIVATE KEY-----",
            ],
        ];
    }
}

