<?php

abstract class At_LTI_ToolProviderExtension extends Bss_Core_NamedExtension
{
    public static function getExtensionPointName () { return 'at:ltitool:lti/lti-toolprovider'; }

    abstract public function getIssuer ();      // should the tool be providing this info??
    abstract public function getLoginHint ();   // should the tool be providing this info??

    /**
     * Returns the Launch URL for this tool
     *
     * @return string
     */
    abstract public function getLaunchUrl ();

    /**
     * Generate rate the content as described by the fileName.
     *
     * @param string $fileName
     * @param array $options
     * @return string
     */
    abstract public function processContent ($fileName, $options);
}