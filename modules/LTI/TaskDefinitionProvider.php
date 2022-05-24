<?php

/**
 */
class At_LTI_TaskDefinitionProvider extends Bss_AuthZ_TaskDefinitionProvider
{
    public function getTaskDefinitions ()
    {
        return array(
        	'register' => 'ability to create/edit registrations',
            'delete registrations' => 'can delete registrations/deployments'
        );
    }
}
