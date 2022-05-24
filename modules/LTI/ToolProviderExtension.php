<?php

/**
 * Abstract class for Tool Provider Extensions
 * 
 * @author      Steve Pedersen (pedersen@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
abstract class At_LTI_ToolProviderExtension extends Bss_Core_NamedExtension
{
    public static function getExtensionPointName () { return 'at:ltitool:lti/lti-toolprovider'; }

    /**
     * The name is used as an identifying route variable for the tool.
     * Should be lowercase and use "-" or something instead of spaces.
     *
     * @return string
     */
    abstract public static function getExtensionName ();

    /**
     * @return string - normal formatted name (e.g., Example Tool)
     */
    abstract public function getName ();
    
    /**
     * @return string - description of tool
     */
    abstract public function getDescription ();

    /**
     * @return string - the relative url route for a tool's launch endpoint
     */
    abstract public function getLaunchRoute ();
    
    /**
     * @return string - the full url of the launch route (baseUrl($this->getLaunchRoute()))
     */
    abstract public function getLaunchUrl ();

    /**
     * Return a list of this tool's deep linked resources in format:
     * ['<resource1 name>' => [
     *      'url' => '<url>', 
     *      'placements' => ['<editor_button>', '<link_selection>', ...]],
     *  '<resource2 name>' => [...]]
     *
     * @return array
     */
    abstract public function getDeepLinks ();

    /**
     * Generate rate the content as described by the fileName.
     *
     * @param string $fileName
     * @param array $options
     * @return string
     */
    abstract public function processContent ($fileName, $options);
}