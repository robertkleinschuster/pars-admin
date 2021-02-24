<?php
return [
    'task' => [
        \Pars\Core\Task\Import\ImportTask::class => [
            'day' => null,
            'hour' => null,
            'minute' => null,
            'active' => true
        ],
       \Pars\Core\Task\Order\OrderTask::class => [
           'day' => null,
           'hour' => null,
           'minute' => null,
           'active' => false
       ],
        \Pars\Core\Task\Email\EmailTask::class => [
            'day' => null,
            'hour' => null,
            'minute' => null,
            'active' => false
        ],
    ],
];
