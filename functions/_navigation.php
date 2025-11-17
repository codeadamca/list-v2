<?php

function navigation_array($selected = false)
{

    $navigation = [
        [
            'title' => 'Email List',
            'sections' => [
                [
                    'title' => 'Email List',
                    'id' => 'admin-content',
                    'pages' => [
                        [
                            'icon' => 'list',
                            'url' => '/admin/dashboard',
                            'title' => 'Email List',
                            'sub-pages' => [
                                [
                                    'title' => 'Dashboard',
                                    'url' => '/admin/dashboard',
                                    'colour' => 'red',
                                ],[
                                    'title' => 'Add Email',
                                    'url' => '/admin/add',
                                    'colour' => 'red',
                                ],[
                                    'title' => 'Export List',
                                    'url' => '/admin/export',
                                    'colour' => 'red',
                                ],[
                                    'br' => '---',
                                ],[
                                    'title' => 'Visit Email List App',
                                    'url' => 'https://list.brickmmo.com',
                                    'colour' => 'orange',
                                    'icon' => 'fa-solid fa-arrow-up-right-from-square',
                                ],[
                                    'br' => '---',
                                ],[
                                    'title' => 'Uptime Report',
                                    'url' => 'https://uptime.brickmmo.com/details/12',
                                    'colour' => 'orange',
                                    'icons' => 'bm-uptime',
                                ],[
                                    'title' => 'Stats Report',
                                    'url' => '/stas/events',
                                    'colour' => 'orange',
                                    'icons' => 'bm-stats',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    if($selected)
    {
        
        $selected = '/'.$selected;
        $selected = str_replace('//', '/', $selected);
        $selected = str_replace('.php', '', $selected);
        $selected = str_replace('.', '/', $selected);
        $selected = substr($selected, 0, strrpos($selected, '/'));

        foreach($navigation as $levels)
        {

            foreach($levels['sections'] as $section)
            {

                foreach($section['pages'] as $page)
                {

                    if(strpos($page['url'], $selected) === 0)
                    {
                        return $page;
                    }

                }

            }

        }

    }

    return $navigation;

}