<?php


use \IMSGlobal\LTI;

/**
 * Controller for authentication actions.
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_LTI_Controller extends LTITool_Master_Controller
{
    public static function getRouteMap ()
    {
        return [
            '/lti/:service/launch' => ['callback' => 'launch'],
            '/lti/:service/login' => ['callback' => 'login'],
            '/lti/jwks' => ['callback' => 'jwks'],
            '/lti/jwks/:kid' => ['callback' => 'jwks'],
        ];
    }

    public function launch ()
    {
        $serviceName = $this->getRouteVariable('service');
        $extension = $this->getApplication()->moduleManager->getExtensionByName('at:ltitool:lti/lti-toolprovider', $serviceName);

        $launch = LTI\LTI_Message_Launch::new(new At_LTI_RegistrationDatabase($this->getApplication()))
            ->validate();

        $this->forward($extension->getLaunchRoute(), [
            'launch' => $launch
        ]);
    }

    public function login ()
    {
        $serviceName = $this->getRouteVariable('service');
        $moduleManager = $this->getApplication()->moduleManager;

        if ($extension = $moduleManager->getExtensionByName('at:ltitool:lti/lti-toolprovider', $serviceName))
        {
            // DEV stuff. This info should normally be sent in the login request from the platform
            if (!isset($_REQUEST['iss']))
            {
                $_REQUEST['iss'] = $extension->getIssuer();
            }
            if (!isset($_REQUEST['login_hint']))
            {
                $_REQUEST['login_hint'] = $extension->getLoginHint();
            }

            $temp = LTI\LTI_OIDC_Login::new(new At_LTI_RegistrationDatabase($this->getApplication()))
                ->do_oidc_login_redirect($this->baseUrl("/lti/$serviceName/launch"))
                ->do_redirect();
        }
    }

    /**
     * Library allows keys to be fetched based on issuer, key_set_id
     */
    public function jwks ()
    {
        $db = new At_LTI_RegistrationDatabase($this->getApplication());
        $kid = $this->getRouteVariable('kid'); // ?? '301a3597869ba93496e2';

        \IMSGlobal\LTI\JWKS_Endpoint::new($db->get_keys_in_set($kid))
            ->output_jwks();
        exit;
    }
}
    