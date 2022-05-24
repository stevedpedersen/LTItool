<?php 

use \IMSGlobal\LTI;

class At_LTI_RegistrationDatabase implements \IMSGlobal\LTI\Database {

    private $app;

    public function __construct ($app)
    {
        $this->app = $app;
    }

    public function find_registration_by_issuer ($iss) 
    {
    	$registrations = $this->app->schemaManager->getSchema('At_LTI_Registration');
    	
        if (!($registration = $registrations->findOne($registrations->issuer->equals($iss))))
        {
            return false;
        }

        return LTI\LTI_Registration::new()
            ->set_auth_login_url($registration->platform_login_auth_endpoint)
            ->set_auth_token_url($registration->platform_service_auth_endpoint)
            ->set_auth_server($registration->platform_auth_provider)
            ->set_client_id($registration->client_id)
            ->set_key_set_url($registration->platform_jwks_endpoint)
            ->set_kid($registration->key_set_id) 
            ->set_issuer($iss)
            ->set_tool_private_key($this->private_key($registration));
    }

    public function find_deployment ($iss, $deployment_id) 
    {
        $registrations = $this->app->schemaManager->getSchema('At_LTI_Registration');
        $deployments = $this->app->schemaManager->getSchema('At_LTI_Deployment');

        $deployment = null;
        if ($registration = $registrations->findOne($registrations->issuer->equals($iss)))
        {
            $deployment = $deployments->findOne(
                $deployments->id->equals($deployment_id)->andIf(
                    $deployments->registration_id->equals($registration->id)
                )
            );            
        }

        if (!$deployment)
        {
            return false;
        }

        return LTI\LTI_Deployment::new()
            ->set_deployment_id($deployment_id);
    }

    public function find_keys_by_issuer ($issuer)
    {
        if ($registration = $this->find_registration_by_issuer($issuer)) {
            return [$registration->key_set_id => $registration->private_key];
        }

        return [];
    }

    public function get_keys_in_set ($key_set_id)
    {
        $keys = $this->app->schemaManager->getSchema('At_LTI_Key');
        return $keys->findValues(['key_set_id' => 'privateKey'], $keys->key_set_id->equals($key_set_id));
    }

    private function private_key ($registration) 
    {
        $keys = $this->app->schemaManager->getSchema('At_LTI_Key');
        $key = $keys->findOne($keys->key_set_id->equals($registration->keySet->id));
        return $key->privateKey;
    }
}