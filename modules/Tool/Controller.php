<?php

/**
 * Controller for tool provider
 * 
 * @author      Steve Pedersen (pedersen@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University.
 */
class LTITool_Tool_Controller extends LTITool_Master_Controller
{
    public static function getRouteMap ()
    {
        return [
            '/breakout/run' => ['callback' => 'breakout'],
            '/breakout/configure' => ['callback' => 'configure'],
            '/breakout/jwks' => ['callback' => 'jwks'],
            '/api/scoreboard' => ['callback' => 'scoreboard'],
            '/api/score' => ['callback' => 'score'],

            // '/register' => ['callback' => 'register'],
        ];
    }


    public function breakout ()
    {
        $launch = $this->getRouteVariable('launch');

        $this->setToolTemplate();

        $this->template->launch = $launch;
        $this->template->curr_diff = $launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/custom']['difficulty'] ?: 'normal';
        $this->template->curr_user_name = $launch->get_launch_data()['name'];
        $this->template->serviceName = 'breakout';
    }

    public function setToolTemplate ()
    {
        $this->template->setMasterTemplate(Bss_Core_PathUtils::path(dirname(__FILE__), 'resources', 'tool.html.tpl'));
    }

    public function configure ()
    {
        $launch = \IMSGlobal\LTI\LTI_Message_Launch::from_cache(
            $this->request->getQueryParameter('launch_id'), new At_LTI_RegistrationDatabase($this->getApplication())
        );

        if (!$launch->is_deep_link_launch()) {
            throw new Exception("Must be a deep link!");
        }

        $resource = \IMSGlobal\LTI\LTI_Deep_Link_Resource::new()
            ->set_url($this->baseUrl('/breakout/run'))
            ->set_custom_params(['difficulty' => $this->request->getQueryParameter('diff')])
            ->set_title('Breakout ' . $this->request->getQueryParameter('diff') . ' mode!');

        $launch->get_deep_link()
            ->output_response_form([$resource]);
    }

    public function scoreboard ()
    {
        $launch = \IMSGlobal\LTI\LTI_Message_Launch::from_cache(
            $this->request->getQueryParameter('launch_id'), new At_LTI_RegistrationDatabase($this->getApplication())
        );

        if (!$launch->has_nrps()) {
            throw new Exception("Don't have names and roles!");
        }
        if (!$launch->has_ags()) {
            throw new Exception("Don't have grades!");
        }
        $ags = $launch->get_ags();

        // fetch the scores from this line item
        $score_lineitem = \IMSGlobal\LTI\LTI_Lineitem::new()
            ->set_tag('score')
            ->set_score_maximum(100)
            ->set_label('Score')
            ->set_resource_id($launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/resource_link']['id']);
        $scores = $ags->get_grades($score_lineitem);

        // fetch the times from this line item
        $time_lineitem = \IMSGlobal\LTI\LTI_Lineitem::new()
            ->set_tag('time')
            ->set_score_maximum(999)
            ->set_label('Time Taken')
            ->set_resource_id('time'.$launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/resource_link']['id']);
        $times = $ags->get_grades($time_lineitem);

        // fetch all the members in this course 
        $members = $launch->get_nrps()->get_members();

        $scoreboard = [];

        foreach ($scores as $score) {
            $result = ['score' => $score['resultScore']];
            foreach ($times as $time) {
                if ($time['userId'] === $score['userId']) {
                    $result['time'] = $time['resultScore'];
                    break;
                }
            }
            foreach ($members as $member) {
                if ($member['user_id'] === $score['userId']) {
                    $result['name'] = $member['name'];
                    break;
                }
            }
            $scoreboard[] = $result;
        }

        $scoreboards = [];

        // Groups service
        $users_by_group = [];
        $gbs = [];
        if ($launch->has_gs()) {
            $gs = $launch->get_gs();
            $gbs = $gs->get_groups_by_set();
            foreach ($members as $member) {
                foreach ($member['group_enrollments'] as $enrollment) {
                    $users_by_group[$enrollment['group_id']][$member['user_id']] = $member;
                }
            }
        }

        foreach ($gbs as $set)     {
            $scoreboards[$set['id']] = [
                'name' => $set['name'],
                'id' => $set['id'],
                'scoreboard' => []
            ];
            foreach ($set['groups'] as $group_id => $group) {
                $result = [
                    'score' => 0,
                    'time' => 0,
                    'name' => $group['name']
                ];
                foreach ($scores as $score) {
                    if (isset($users_by_group[$group_id][$score['userId']])) {
                        $result['score'] += $score['resultScore'];
                    }
                }
                foreach ($times as $time) {
                    if (isset($users_by_group[$group_id][$time['userId']])) {
                        $result['time'] += $time['resultScore'];
                    }
                }
                $scoreboards[$set['id']]['scoreboard'][] = $result;
            }
        }

        $scoreboards['all'] = [
            'name' => 'All',
            'id' => 'all',
            'scoreboard' => $scoreboard,
        ];

        echo json_encode($scoreboards);
        exit;
    }

    public function score ()
    {
        $launch = \IMSGlobal\LTI\LTI_Message_Launch::from_cache(
            $this->request->getQueryParameter('launch_id'), new At_LTI_RegistrationDatabase($this->getApplication())
        );
        
        if (!$launch->has_ags()) {
            throw new Exception("Don't have grades!");
        }

        $grades = $launch->get_ags();

        $score = \IMSGlobal\LTI\LTI_Grade::new()
            ->set_score_given($_REQUEST['score'])
            ->set_score_maximum(100)
            ->set_timestamp(date(DateTime::ISO8601))
            ->set_activity_progress('Completed')
            ->set_grading_progress('FullyGraded')
            ->set_user_id($launch->get_launch_data()['sub']);
        // column in gradebook
        $score_lineitem = \IMSGlobal\LTI\LTI_Lineitem::new()
            ->set_tag('score')
            ->set_score_maximum(100)
            ->set_label('Score')
            ->set_resource_id($launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/resource_link']['id']);
        $grades->put_grade($score, $score_lineitem);


        $time = \IMSGlobal\LTI\LTI_Grade::new()
            ->set_score_given($_REQUEST['time'])
            ->set_score_maximum(999)
            ->set_timestamp(date(DateTime::ISO8601))
            ->set_activity_progress('Completed')
            ->set_grading_progress('FullyGraded')
            ->set_user_id($launch->get_launch_data()['sub']);
        $time_lineitem = \IMSGlobal\LTI\LTI_Lineitem::new()
            ->set_tag('time')
            ->set_score_maximum(999)
            ->set_label('Time Taken')
            ->set_resource_id('time'.$launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/resource_link']['id']);
        $grades->put_grade($time, $time_lineitem);
        
        echo '{"success" : true}';
        exit;
    }



    public function register () 
    {
        $config = [
            'title' => 'Breakout Game',
            'description' => 'BO Game desc',
            'oidc_initiation_url' => $this->baseUrl('/lti/breakout/login'),
            'target_link_uri' => $this->baseUrl('/lti/breakout/launch'),
            'scopes' => [
                'https://purl.imsglobal.org/spec/lti-ags/scope/score',
                'https://purl.imsglobal.org/spec/lti-ags/scope/lineitem',
                'https://purl.imsglobal.org/spec/lti-ags/scope/result.readonly',
                'https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly'
            ],
            'extensions' => [
                (object) [
                    'privacy_level' => 'public',
                    "platform" => "instructure.com",
                    'settings' => (object) [
                        "text" => "Breakout game Content",
                        'placements' => [
                            [
                                "text" => "Breakout game Link Selection",
                                "enabled" => true,
                                "placement" => "link_selection",
                                "message_type" => "LtiDeepLinkingRequest",
                                "target_link_uri" => $this->baseUrl('/lti/breakout/launch'),
                                "canvas_icon_class" => "icon-lti",
                                "selection_height" => 1000,
                                "selection_width" => 800
                            ],
                            [
                                "text" => "Breakout game Link Selection",
                                "enabled" => true,
                                "placement" => "assignment_selection",
                                "message_type" => "LtiDeepLinkingRequest",
                                "target_link_uri" => $this->baseUrl('/lti/breakout/launch'),
                                "canvas_icon_class" => "icon-lti",
                                "selection_height" => 1000,
                                "selection_width" => 800
                            ],
                            [
                                "text" => "Breakout game Course Navigation",
                                "enabled" => true,
                                "placement" => "course_navigation",
                                "default" => "disabled",
                                "message_type" => "LtiResourceLinkRequest",
                                "target_link_uri" => $this->baseUrl('/lti/breakout/launch'),
                                "canvas_icon_class" => "icon-lti"
                            ]
                        ]
                    ]
                ]
            ],
            'public_jwk_url' => $this->baseUrl('/lti/jwks/301a3597869ba93496e2'),
            'custom_fields' => [
                'masq_user_id' => '$Canvas.masqueradingUser.id',
                'canvas_user_id' => '$Canvas.user.id',
                "voc_section_name" => "$com.instructure.User.sectionNames"
            ]
        ];

        echo "<pre>";
        echo json_encode($config);
        exit;
    }

}
    