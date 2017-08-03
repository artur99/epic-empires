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
}
