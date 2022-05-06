<?php

use \Firebase\JWT\JWT;

/**
 * Example-Platform Controller
 * 
 * @author      Steve Pedersen (pedersen@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_Platform_Controller extends Bss_Master_Controller
{
    public static function getRouteMap ()
    {
        return [
            '/platform/login' => ['callback' => 'login'],
            '/platform/token' => ['callback' => 'token'],
            '/platform/jwks' => ['callback' => 'jwks'],

            '/platform/services/nrps' => ['callback' => 'nrps'],
            '/platform/services/ags/lineitems' => ['callback' => 'lineitems']
        ];
    }


    public function login ()
    {
        $params = $this->request->getQueryParameters();

        $registrations = $this->schema('At_LTI_Registration');
        $registration = $registrations->findOne($registrations->clientId->equals($params['client_id']));
        $deployments = $this->schema('At_LTI_Deployment');
        $deployment = $deployments->findOne($deployments->registration_id->equals($registration->id));

        $message_jwt = [
            "iss" => $registration->issuer,
            "aud" => [$params['client_id']],
            "sub" => '0ae836b9-7fc9-4060-006f-27b2066ac545',
            "exp" => time() + 600,
            "iat" => time(),
            "nonce" => $params['nonce'],
            "name" => "Steve Pedersen",
            "https://purl.imsglobal.org/spec/lti/claim/deployment_id" => $deployment->id,
            "https://purl.imsglobal.org/spec/lti/claim/message_type" => "LtiResourceLinkRequest", //LtiDeepLinkingResponse
            "https://purl.imsglobal.org/spec/lti/claim/version" => "1.3.0",
            "https://purl.imsglobal.org/spec/lti/claim/target_link_uri" => $params['redirect_uri'],
            "https://purl.imsglobal.org/spec/lti/claim/roles" => [
                "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor"
            ],
            "https://purl.imsglobal.org/spec/lti/claim/resource_link" => [
                "id" => "7b3c5109-b402-4eac-8f61-bdafa301cbb4",
            ],
            "https://purl.imsglobal.org/spec/lti-nrps/claim/namesroleservice" => [
                "context_memberships_url" => $this->baseUrl('/platform/services/nrps'),
                "service_versions" => ["2.0"]
            ],
            "https://purl.imsglobal.org/spec/lti-ags/claim/endpoint" => [
                "scope" => [
                  "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem",
                  "https://purl.imsglobal.org/spec/lti-ags/scope/result.readonly",
                  "https://purl.imsglobal.org/spec/lti-ags/scope/score"
                ],
                "lineitems" => $this->baseUrl('/platform/services/ags/lineitems'),
            ]
        ];

        $jwt = JWT::encode(
            $message_jwt,
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
            'RS256',
            'fcec4f14-28a5-4697-87c3-e9ac361dada5'
        );

        $this->template->redirect_uri = $params['redirect_uri'];
        $this->template->jwt = $jwt;
        $this->template->state = $params['state'];
    }

    public function token ()
    {
        echo json_encode([
            'access_token' => '9a4b5056-cdce-4cdd-8981-053b610d0842'
        ]);
        exit;
    }

    public function jwks ()
    {

        \IMSGlobal\LTI\JWKS_Endpoint::new([
            // 'fcec4f14-28a5-4697-87c3-e9ac361dada5' => file_get_contents(__DIR__ . '/../../db/platform.key')
            'fcec4f14-28a5-4697-87c3-e9ac361dada5' => 
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
-----END RSA PRIVATE KEY-----"
        ])->output_jwks();
        exit;
    }

    public function nrps ()
    {
        echo json_encode([
            "id" => $this->baseUrl('/platform/services/nrps'),
            "members" => [
                [
                    "status" => "Active",
                    "context_id" => "2923-abc",
                    "name" => "Trudie Senaida",
                    "given_name" => "Trudie",
                    "family_name" => "Senaida",
                    "user_id" => "0ae836b9-7fc9-4060-006f-27b2066ac545",
                    "roles" => [
                        "Instructor"
                    ],
                    "message" => []
                ],
                [
                    "status" => "Active",
                    "context_id" => "2923-abc",
                    "name" => "Marget Elke",
                    "given_name" => "Marget",
                    "family_name" => "Elke",
                    "user_id" => "4d0b3941-83f5-47fe-bd8a-66b39aa0651d",
                    "roles" => [
                        "Instructor"
                    ],
                    "message" => []
                ]
            ]

        ]);
        exit;
    }

    public function ags ()
    {
        switch($this->request->getQueryParameter('tag')) {
            case 'scores':
                $data = file_get_contents('php://input');
                $score = json_decode($data, true);
                if ($_REQUEST['tag'] == 'score') {
                    file_put_contents(__DIR__ . '/ags/score.txt', $score['scoreGiven']);
                } else {
                    file_put_contents(__DIR__ . '/ags/time.txt', $score['scoreGiven']);
                }
                $_SESSION['my_test'] = 3;
                var_dump($score);
                echo $data;
            break;

            case 'results':
                if ($_REQUEST['tag'] == "score") {
                    echo json_encode([
                        [
                            "id" => "https://lms.example.com/context/2923/lineitems/1/results/5323497",
                            "userId" => "0ae836b9-7fc9-4060-006f-27b2066ac545",
                            "resultScore" => file_get_contents(__DIR__ . '/ags/score.txt') ?: 0,
                            "resultMaximum" => 108,
                        ],
                        [
                            "id" => "https://lms.example.com/context/2923/lineitems/1/results/5323497",
                            "userId" => "4d0b3941-83f5-47fe-bd8a-66b39aa0651d",
                            "resultScore" => 60,
                            "resultMaximum" => 108,
                        ]
                    ]);
                } else {
                    echo json_encode([
                        [
                            "id" => "https://lms.example.com/context/2923/lineitems/1/results/5323497",
                            "userId" => "0ae836b9-7fc9-4060-006f-27b2066ac545",
                            "resultScore" => file_get_contents(__DIR__ . '/ags/time.txt') ?: 0,
                            "resultMaximum" => 999,
                        ],
                        [
                            "id" => "https://lms.example.com/context/2923/lineitems/1/results/5323497",
                            "userId" => "4d0b3941-83f5-47fe-bd8a-66b39aa0651d",
                            "resultScore" => 82,
                            "resultMaximum" => 999,
                        ]
                    ]);
                }
            break;
        }
    }

    public function lineitems ()
    {
        echo json_encode([
            [
                "id" => $this->baseUrl('/platform/services/ags?tag=time'),
                "scoreMaximum" => 999,
                "label" => "Time",
                "tag" => "time",
                "resourceId" => "time7b3c5109-b402-4eac-8f61-bdafa301cbb4"
            ],
            [
                "id" => $this->baseUrl('/platform/services/ags?tag=score'),
                "scoreMaximum" => 108,
                "label" => "Score",
                "tag" => "score",
                "resourceId" => "7b3c5109-b402-4eac-8f61-bdafa301cbb4"
            ]
        ]);
        exit;
    }
}
    