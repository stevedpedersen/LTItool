<?php

class LTITool_Tool_ToolProvider extends At_LTI_ToolProviderExtension
{
    public static function getExtensionName () { return 'game'; }
    
    public function getDescription () { return 'Breakout game'; }

    public function getIssuer () 
    { 
        return rtrim($this->getApplication()->baseUrl(''), '/'); 
    }

    public function getLaunchUrl ()
    {
        return $this->getApplication()->baseUrl($this->getLaunchRoute());
    }

    public function getLaunchRoute ()
    {
        return '/game/run';
    }

    public function getIconUrl ()
    {
        return $this->getApplication()->baseUrl('/assets/images/');
    }

    public function getLoginHint () { return $this->getIssuer(); }

    public function processContent ($fileName, $options) {}

    // TODO: Is this only for LTI 2.0 or something? can it be used for LTI 1.3?
    // for auto populating LTI tool fields
    // Cartridge Link response
    public function getConfiguration ()
    {
        // generate XML
        Header('Content-type: text/xml');
        $xml = new DOMDocument('1.0', 'utf-8');

        $root = $xml->createElement('cartridge_basiclti_link');
        $root->setAttribute('xmlns', "http://www.imsglobal.org/xsd/imslticc_v1p0");
        $root->setAttribute('xmlns:blti', "http://www.imsglobal.org/xsd/imsbasiclti_v1p0");
        $root->setAttribute('xmlns:lticm', "http://www.imsglobal.org/xsd/imslticm_v1p0");
        $root->setAttribute('xmlns:lticp', "http://www.imsglobal.org/xsd/imslticp_v1p0");
        $root->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
        $root->setAttribute('xsi:schemaLocation', "http://www.imsglobal.org/xsd/imslticc_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imslticc_v1p0.xsd http://www.imsglobal.org/xsd/imsbasiclti_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imsbasiclti_v1p0p1.xsd http://www.imsglobal.org/xsd/imslticm_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imslticm_v1p0.xsd http://www.imsglobal.org/xsd/imslticp_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imslticp_v1p0.xsd");
        $xml->appendChild($root);

        $title = $xml->createElement('blti:title', self::getExtensionName());
        $root->appendChild($title);

        $description = $xml->createElement('blti:description', $this->getDescription());
        $root->appendChild($description);

        $launch_url = $xml->createElement('blti:launch_url', $this->getLaunchUrl());
        $root->appendChild($launch_url);

        $icon = $xml->createElement('blti:icon', $this->getIconUrl());
        $root->appendChild($icon);

        $extensions = $xml->createElement('blti:extensions');
        $extensions->setAttribute('platform', "canvas.instructure.com");
        $root->appendChild($extensions);

        $options = $xml->createElement('lticm:options');
        $options->setAttribute('name', "course_navigation");
        $extensions->appendChild($options);

        $property = $xml->createElement('lticm:property', 'disabled');
        $property->setAttribute('name', 'default');
        $options->appendChild($property);

        $property = $xml->createElement('lticm:property', 'true');
        $property->setAttribute('name', 'enabled');
        $options->appendChild($property);
    
        $property = $xml->createElement('lticm:property', 'some url');
        $property->setAttribute('name', 'url');
        $options->appendChild($property);
        
        $property = $xml->createElement('lticm:property', 'admins');
        $property->setAttribute('name', 'visibility');
        $options->appendChild($property);
        
        $property = $xml->createElement('lticm:property', 'sfsu.mediasite.com');
        $property->setAttribute('name', 'domains');
        $extensions->appendChild($property);
        

        $options = $xml->createElement('lticm:options');
        $options->setAttribute('name', "editor_button");
        $extensions->appendChild($options);

        $property = $xml->createElement('lticm:property', 'true');
        $property->setAttribute('name', 'enabled');
        $options->appendChild($property);

        $property = $xml->createElement('lticm:property', 'some url');
        $property->setAttribute('name', 'icon_url');
        $options->appendChild($property);
    
        $property = $xml->createElement('lticm:property', '600');
        $property->setAttribute('name', 'selection_height');
        $options->appendChild($property);
        
        $property = $xml->createElement('lticm:property', '600');
        $property->setAttribute('name', 'selection_width');
        $options->appendChild($property);

        $property = $xml->createElement('lticm:property', 'Mediasite');
        $property->setAttribute('name', 'text');
        $options->appendChild($property);

        $property = $xml->createElement('lticm:property', 'some URL');
        $property->setAttribute('name', 'url');
        $options->appendChild($property);
        

        $property = $xml->createElement('lticm:property', 'an icon url');
        $property->setAttribute('name', 'icon_url');
        $extensions->appendChild($property);
        
        $property = $xml->createElement('lticm:property', 'public');
        $property->setAttribute('name', 'privacy_level');
        $extensions->appendChild($property);
        
        $property = $xml->createElement('lticm:property', 'sofo_mediasite');
        $property->setAttribute('name', 'tool_id');
        $extensions->appendChild($property);
        
        echo $xml->saveXML();
        die;
    }
}