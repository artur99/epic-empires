<?php
namespace Misc;

class StaticData{
    function buildingData(){
        $b = [];
        $b['center'] = [
            '2' => [
                'time' => 40,
                'workers' => 10,
                'costs' => ['wood' => 500, 'gold' => 500, 'food' => 500],
                'result' => ['workers' => 10, 'points' => 600]
            ],
            '3' => [
                'time' => 120,
                'workers' => 20,
                'costs' => ['wood' => 5000, 'gold' => 5000, 'food' => 5000],
                'result' => ['workers' => 30, 'points' => 1700]
            ]
        ];
        $b['barracks'] = [
            '1' => [
                'time' => 40,
                'workers' => 7,
                'costs' => ['wood' => 300, 'gold' => 300],
                'result' => ['points' => 50]
            ],
            '2' => [
                'time' => 120,
                'workers' => 17,
                'costs' => ['wood' => 1500, 'gold' => 1500],
                'result' => ['points' => 150]
            ],
            '3' => [
                'time' => 500,
                'workers' => 50,
                'costs' => ['wood' => 4000, 'gold' => 4000],
                'result' => ['points' => 350]
            ]
        ];
        $b['academy'] = [
            '1' => [
                'time' => 100,
                'workers' => 8,
                'costs' => ['wood' => 250, 'gold' => 250],
                'result' => ['points' => 250]
            ],
            '2' => [
                'time' => 120,
                'workers' => 28,
                'costs' => ['wood' => 4000, 'gold' => 4000],
                'result' => ['points' => 350]
            ]
        ];
        $b['house'] = [
            '1' => [
                'time' => 100,
                'workers' => 3,
                'costs' => ['food' => 60, 'wood' => 60, 'gold' => 60],
                'result' => ['workers' => 2, 'points' => 50]
            ],
            '2' => [
                'time' => 120,
                'workers' => 3,
                'costs' => ['food' => 60, 'wood' => 60, 'gold' => 80],
                'result' => ['workers' => 2, 'points' => 100]
            ],
            '3' => [
                'time' => 140,
                'workers' => 5,
                'costs' => ['food' => 60, 'wood' => 60, 'gold' => 800],
                'result' => ['workers' => 2, 'points' => 120]
            ],
            '4' => [
                'time' => 150,
                'workers' => 5,
                'costs' => ['food' => 60, 'wood' => 60, 'gold' => 1060],
                'result' => ['workers' => 2, 'points' => 170]
            ]
        ];

        return $b;
    }

    function resourceData(){
        // id = academy_level
        $lvl[0] = [
            'food' => [
                'workers' => 3,
                'time' => 70,
                'result' => ['food'=>'100']
            ],
            'wood' => [
                'workers' => 4,
                'time' => 80,
                'result' => ['wood'=>'100']
            ],
            'gold' => [
                'workers' => 8,
                'time' => 130,
                'result' => ['gold'=>'100']
            ]
        ];
        $lvl[1] = [
            'food' => [
                'workers' => 2,
                'time' => 70,
                'result' => ['food'=>'120']
            ],
            'wood' => [
                'workers' => 3,
                'time' => 80,
                'result' => ['wood'=>'120']
            ],
            'gold' => [
                'workers' => 5,
                'time' => 130,
                'result' => ['gold'=>'120']
            ]
        ];
        $lvl[2] = [
            'food' => [
                'workers' => 1,
                'time' => 70
            ],
            'wood' => [
                'workers' => 2,
                'time' => 70
            ],
            'gold' => [
                'workers' => 4,
                'time' => 90
            ]
        ];
        return $lvl;
    }

    function resMax(){
        // $id = city_level
        $max = [
            0 => 500,
            1 => 500,
            2 => 5000
        ];
        return $max;
    }
    function taskNames(){
        $tn = [
            'get' => [
                'food' => 'Hunting for food',
                'wood' => 'Gathering wood',
                'gold' => 'Minning for gold'
            ],
            'build' => [
                'academy' => 'Upgrading Academy',
                'center' => 'Upgrading Center',
                'barracks' => 'Upgrading Barracks',
                'house' => 'Upgrading House'
            ],
            'attack' => [
                'attack' => 'Attacking'
            ]
        ];
        return $tn;
    }
}
