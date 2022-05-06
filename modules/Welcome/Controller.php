<?php



/**
 * The welcome (landing) page.
 * 
 * @author      Daniel A. Koepke <dkoepke@sfsu.edu>
 * @copyright   Copyright &copy; San Francisco State University
 */
class LTITool_Welcome_Controller extends LTITool_Master_Controller
{
    public static function getRouteMap ()
    {
        return array(
            '/' => array('callback' => 'welcome'),
        );
    }
    
    public function welcome ()
    {

        $siteSettings = $this->getApplication()->siteSettings;

        if ($welcomeText = $siteSettings->getProperty('welcome-text'))
        {
            $this->template->welcomeText = $welcomeText;
        }

        // $db = new At_LTI_RegistrationDatabase($this->getApplication());
        // echo "<pre>"; var_dump($db->find_registration_by_issuer('https://spedersen18.dev.at.sfsu.edu/ltitool')); die;

    }


    /**
     * Issues with the Shibboleth implementation have forced us to hack this in.
     * The SP or the IDP is not properly redirecting some users to the correct
     * url to complete the login. This checks to see if the request came from the
     * shibboleth IDP and redirects properly if necessary.
     *
     */
    private function brokenShibHack()
    {
        error_log('brokenShibHack');
        if ($accout = $this->getAccount()) // Already logged in.
            return;

        $config = $this->getApplication()->configuration;

        error_log('brokenShibHack 2');
        foreach ($config->getProperty('identityProviders', array()) as $idpName => $idpAttributeMap)
        {
            if (isset($idpAttributeMap['entityId']) && (preg_match('/LTITool_AuthN_IdentityProvider/', $idpAttributeMap['impl'])))
            {
                $IDPUrl = parse_url($idpAttributeMap['entityId']);
                $IDPHost = $IDPUrl['host'];

                $referrer = $this->request->getReferrer();
                $referrerUrl = parse_url($referrer);
                $referrerHost = $referrerUrl['host'];

                if ($IDPHost === $referrerHost)
                {
                    $this->response->redirect('/login/complete/' . $idpName);
                }
            }
        }
    }
}
