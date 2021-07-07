<?php

return [
    'translator' => [
        'namespace' => \Pars\Core\Translation\ParsTranslator::NAMESPACE_ADMIN,
        'locale' => ['de_AT', 'en_US'],
        'translation_file_patterns' => [
            [
                'type' => Laminas\I18n\Translator\Loader\PhpArray::class,
                'base_dir' => implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'translation', 'default']),
                'pattern' => '%s.php',
                'text_domain' => 'default'

            ],
            [
                'type' => Laminas\I18n\Translator\Loader\PhpArray::class,
                'base_dir' => implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'translation', 'admin']),
                'pattern' => '%s.php',
                'text_domain' => 'admin'

            ],
            [
                'type' => Laminas\I18n\Translator\Loader\PhpArray::class,
                'base_dir' => implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'translation', 'validation']),
                'pattern' => '%s.php',
                'text_domain' => 'validation'

            ],
        ],
        'translation_files' => [
        ],
        'remote_translation' => [
            [
                'type' => \Laminas\I18n\Translator\Loader\RemoteLoaderInterface::class,
                'text_domain' => 'default'
            ],
            [
                'type' => \Laminas\I18n\Translator\Loader\RemoteLoaderInterface::class,
                'text_domain' => 'admin'
            ],
            [
                'type' => \Laminas\I18n\Translator\Loader\RemoteLoaderInterface::class,
                'text_domain' => 'frontend'
            ]
        ],
        'event_manager_enabled' => true
    ]
];
