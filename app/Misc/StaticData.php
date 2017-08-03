<?php
namespace Misc;

class StaticData{
    function buildingData(){
        $b = [];
        $b['barracks'] = [
            '1' => [
                'time' => 40,
                'costs' => ['wood' => 300, 'gold' => 300]
            ],
            '2' => [
                'time' => 120,
                'costs' => ['wood' => 500, 'gold' => 500]
            ],
            '3' => [
                'time' => 500,
                'costs' => ['wood' => 500, 'gold' => 500]
            ],
        ];
        $b['academy'] = [
            '1' => [
                'time' => 100,
                'costs' => ['wood' => 250, 'gold' => 250]
            ],
            '2' => [
                'time' => 120,
                'costs' => ['wood' => 400, 'gold' => 400]
            ]
        ];

        return $b;
    }

    function resourceData(){
        $lvl[0] = [
            'food' => [
                'workers' => 3,
                'time' => 70
            ],
            'wood' => [
                'workers' => 4,
                'time' => 80
            ],
            'gold' => [
                'workers' => 8,
                'time' => 130
            ]
        ];
        $lvl[1] = [
            'food' => [
                'workers' => 2,
                'time' => 70
            ],
            'wood' => [
                'workers' => 3,
                'time' => 80
            ],
            'gold' => [
                'workers' => 5,
                'time' => 130
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
}
