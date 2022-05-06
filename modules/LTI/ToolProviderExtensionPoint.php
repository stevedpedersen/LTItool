<?php

class At_LTI_ToolProviderExtensionPoint extends Bss_Core_ExtensionPoint
{
    public function getUnqualifiedName () { return 'lti-toolprovider'; }
    public function getDescription () { return 'Implementations of LTI tool providers.'; }
    public function getRequiredInterfaces () { return array('At_LTI_ToolProviderExtension'); }
}