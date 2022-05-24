<?php

class LTITool_Tool_ToolProvider extends At_LTI_ToolProviderExtension
{
    public static function getExtensionName () { return 'breakout'; }
    
    public function getName () { return 'Breakout'; }

    public function getDescription () { return 'The classic game of Breakout'; }

    public function getLaunchRoute () { return '/breakout/run'; }

    public function getLaunchUrl ()
    {
        return $this->getApplication()->baseUrl($this->getLaunchRoute());
    }

    public function getDeepLinks ()
    {
        return [
            'Breakout: Choose Difficulty' => [
                'url' => $this->getApplication()->baseUrl('breakout/configure'),
                'placements' => ['link_selection', 'editor_button']
            ],
        ];
    }

    // public function getIconUrl ()
    // {
    //     return $this->getApplication()->baseUrl('/assets/images/');
    // }

    public function processContent ($fileName, $options) {}
}