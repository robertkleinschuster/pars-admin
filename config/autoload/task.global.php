<?php

return [
    'task' => [
        \Pars\Import\ImportTask::class => [
            'day' => null,
            'hour' => null,
            'minute' => null,
            'active' => true
        ],
        \Pars\Model\Article\Translation\Auto\AutoTranslateTask::class => [
            'day' => null,
            'hour' => 1,
            'minute' => null,
            'active' => true
        ],
    ],
];
