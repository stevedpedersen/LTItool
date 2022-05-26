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

    public function setToolTemplate ()
    {
        $this->template->setMasterTemplate(Bss_Core_PathUtils::path(dirname(__FILE__), 'resources', 'tool.html.tpl'));
    }

    public function breakout ()
    {
        $toolManager = new At_LTI_ToolManager($this);

        $this->setToolTemplate();

        $this->template->launch = $toolManager->getLaunch();
        $this->template->curr_diff = $toolManager->getLaunchCustomData('difficulty') ?: 'normal';
        $this->template->curr_user_name = $toolManager->getLaunchData('name');
        $this->template->serviceName = 'breakout';
    }

    public function configure ()
    {   
        $url = $this->baseUrl('/breakout/run');
        $title = 'Breakout: ' . $this->request->getQueryParameter('diff') . ' mode!';

        $toolManager = new At_LTI_ToolManager($this);
        $toolManager->sendDeepLinkResource($url, $title,
            ['difficulty' => $this->request->getQueryParameter('diff')]
        );
    }

    public function score ()
    {
        $agService = At_LTI_ToolManager::GetService('ags', $this);

        $grade = $agService->newGrade($this->request->getQueryParameter('score'), 100);
        $lineitem = $agService->newLineitem('score', 100, 'Score');
        $agService->putGrade($grade, $lineitem);

        $grade = $agService->newGrade($this->request->getQueryParameter('time'), 999, 'Completed', 'FullyGraded');
        $lineitem = $agService->newLineitem('time', 999, 'Time Taken');
        $agService->putGrade($grade, $lineitem);

        echo '{"success" : true}';
        exit;
    }

    public function scoreboard ()
    {
        $agService = At_LTI_ToolManager::GetService('ags', $this);
        $scores = $agService->getGradesByLineitem('score');
        $times = $agService->getGradesByLineitem('time');

        $nrpService = At_LTI_ToolManager::GetService('nrps', $this);
        $members = $nrpService->getMembers();

        $scoreboards = [];
        $allScores = [];

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
            $allScores[] = $result;
        }

        $scoreboards['all'] = [
            'name' => 'All',
            'id' => 'all',
            'scoreboard' => $allScores,
        ];
        

        $groupService = At_LTI_ToolManager::GetService('gs', $this);
        
        if ($gbs = $groupService->getGroupsBySet()) {
            $users_by_group = [];

            foreach ($members as $member) {
                foreach ($member['group_enrollments'] as $enrollment) {
                    $users_by_group[$enrollment['group_id']][$member['user_id']] = $member;
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
        }

        echo json_encode($scoreboards);
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
    