<?php

use \IMSGlobal\LTI;

/**
 * Controller for authentication actions.
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @author      Steve Pedersen (pedersen@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class At_LTI_Controller extends LTITool_Master_Controller
{
    public static function getRouteMap ()
    {
        return [
            '/lti/registrations'            => ['callback' => 'listRegistrations'],
            '/lti/registrations/create'     => ['callback' => 'selectToolProvider'],
            '/lti/registrations/:id'        => ['callback' => 'registration'],
            '/lti/registrations/:id/delete' => ['callback' => 'deleteRegistration'],
            '/lti/:service/launch'          => ['callback' => 'launch'],
            '/lti/:service/login'           => ['callback' => 'login'],
            '/lti/jwks/:kid'                => ['callback' => 'jwks'],
        ];
    }

    public static function ExtensionPoint () { return 'at:ltitool:lti/lti-toolprovider'; }

    public function listRegistrations ()
    {
        $this->setPageTitle('List LTI Registrations');
        $this->requirePermission('register');
        $this->template->registrations = $this->schema('At_LTI_Registration')->getAll(['orderBy' => 'issuer']);
    }

    public function selectToolProvider ()
    {
        $this->setPageTitle('New Registration: Select Tool Provider');
        $this->addBreadcrumb('lti/registrations', 'List LTI Registrations');
        $this->requirePermission('register');
        $this->template->toolProviders = $this->getApplication()->getModuleManager()->getExtensions(self::ExtensionPoint());
    }

    public function registration ()
    {
        $registration = $this->helper('activeRecord')->fromRoute('At_LTI_Registration', 'id', ['allowNew' => true]);
        $this->setPageTitle($registration->inDatasource ? 'Edit' : 'New' .' LTI registration');
        $this->addBreadcrumb('lti/registrations', 'List LTI Registrations');
        $this->requirePermission('register');

        $moduleManager = $this->getApplication()->getModuleManager();
        $deployments = $this->schema('At_LTI_Deployment');

        if ($this->request->wasPostedByUser())
        {
            switch ($this->getPostCommand())
            {
                case 'new':
                    $registration->id = At_LTI_KeySet::UUID();
                    $registration->keySet = $this->schema('At_LTI_KeySet')->createInstance()->generateKeySet();
                    $registration->toolProvider = $this->request->getPostParameter('tool');
                    $registration->createdDate = new DateTime;
                    $registration->save();

                    $this->flash('Creating new registration for Tool Provider: '. ucfirst($registration->toolProvider));
                    $this->response->redirect('lti/registrations/' . $registration->id);
                    break;

                case 'save':
                    $this->processSubmission($registration, [
                        'issuer', 'client_id', 'platform_jwks_endpoint', 'platform_service_auth_endpoint', 
                        'platform_login_auth_endpoint', 'platform_auth_provider'
                    ]);
                    $registration->modifiedDate = new DateTime;
                    $registration->save();
                    
                    $deployment = $this->schema('At_LTI_Deployment')->createInstance();
                    $deployment->id = $this->request->getPostParameter('deployment_id');
                    $deployment->customerId = $this->request->getPostParameter('customer_id', $registration->issuer);
                    $deployment->registration_id = $registration->id;
                    $deployment->service = $registration->toolProvider;
                    $deployment->save();

                    $this->flash("Saved registration data for tool: $registration->toolProvider, issuer: $registration->issuer");
                    $this->response->redirect('lti/registrations');
                    break;
            }
        }

        $this->template->registration = $registration;
        $this->template->toolProvider = $registration->getToolExtension();
        $this->template->deployment = $deployments->findOne($deployments->registration_id->equals($registration->id));
        $this->template->loginUrl = $this->baseUrl("lti/$registration->toolProvider/login");
        $this->template->launchUrl = $this->baseUrl("lti/$registration->toolProvider/launch");
    }

    public function deleteRegistration ()
    {
        $this->setPageTitle('Delete Registration');
        $this->addBreadcrumb('lti/registrations', 'List LTI Registrations');
        $this->requirePermission('delete registrations');
        $registration = $this->requireExists($this->helper('activeRecord')->fromRoute('At_LTI_Registration', 'id'));
        $deployments = $this->schema('At_LTI_Deployment');
        $deployment = $deployments->findOne($deployments->registration_id->equals($registration->id));
        
        if ($this->request->wasPostedByUser() && $this->getPostCommand() === 'delete')
        {
            $deployment->delete();
            $registration->delete();
            $this->flash('Registration deleted');
            $this->response->redirect('lti/registrations');
        }

        $this->template->registration = $registration;
        $this->template->deployment = $deployment;
    }

    public function launch ()
    {
        $serviceName = strtolower($this->getRouteVariable('service', ''));
        $extension = $this->getApplication()->getModuleManager()->getExtensionByName(self::ExtensionPoint(), $serviceName);

        $launch = LTI\LTI_Message_Launch::new(new At_LTI_RegistrationDatabase($this->getApplication()))
            ->validate();

        $this->forward($extension->getLaunchRoute(), [
            'launch' => $launch
        ]);
    }

    public function login ()
    {
        $serviceName = strtolower($this->getRouteVariable('service', ''));
        $moduleManager = $this->getApplication()->getModuleManager();

        if ($extension = $moduleManager->getExtensionByName(self::ExtensionPoint(), $serviceName))
        {
            LTI\LTI_OIDC_Login::new(new At_LTI_RegistrationDatabase($this->getApplication()))
                ->do_oidc_login_redirect($this->baseUrl("/lti/$serviceName/launch"))
                ->do_redirect();
        }
    }

    public function jwks ()
    {
        $db = new At_LTI_RegistrationDatabase($this->getApplication());
        \IMSGlobal\LTI\JWKS_Endpoint::new($db->get_keys_in_set($this->getRouteVariable('kid')))
            ->output_jwks();
        exit;
    }
}
    