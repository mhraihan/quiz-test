<?php
return [
    'limit' => 10,
    'pagination' => 15,
    'cache' => now()->addMinute(2),
    'languages' => [
        [
            "label" => "English",
            "value" => "en",
        ],
        [
            "label" => "Chinese",
            "value" => "zh",
        ]
    ],
];
