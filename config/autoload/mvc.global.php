<?php
return [
    'mvc' => [
        'error_controller' => 'index',
        'controllers' => [
            'setup' => \Pars\Admin\Setup\SetupController::class,
            'index' => \Pars\Admin\Index\IndexController::class,
            'auth' => \Pars\Admin\Authentication\AuthenticationController::class,
            'user' => \Pars\Admin\User\UserController::class,
            'update' => \Pars\Admin\Update\UpdateController::class,
            'role' => \Pars\Admin\Role\RoleController::class,
            'rolepermission' => \Pars\Admin\RolePermission\RolePermissionController::class,
            'userrole' => \Pars\Admin\UserRole\UserRoleController::class,
            'translation' => \Pars\Admin\Translation\TranslationController::class,
            'locale' => \Pars\Admin\Locale\LocaleController::class,
            'cmsmenu' => \Pars\Admin\Cms\Menu\CmsMenuController::class,
            'cmssubmenu' => \Pars\Admin\Cms\Menu\CmsSubMenuController::class,
            'cmspage' => \Pars\Admin\Cms\Page\CmsPageController::class,
            'cmsparagraph' => \Pars\Admin\Cms\Paragraph\CmsParagraphController::class,
            'cmspageparagraph' => \Pars\Admin\Cms\PageParagraph\CmsPageParagraphController::class,
            'filedirectory' => \Pars\Admin\File\Directory\FileDirectoryController::class,
            'file' => \Pars\Admin\File\FileController::class,
            'config' => \Pars\Admin\Config\ConfigController::class,
            'import' => \Pars\Admin\Import\ImportController::class,
            'articledata' => \Pars\Admin\Article\Data\ArticleDataController::class,
        ],
        'models' => [
            'setup' => \Pars\Admin\Setup\SetupModel::class,
            'index' => \Pars\Admin\Index\IndexModel::class,
            'auth' => \Pars\Admin\Authentication\AuthenticationModel::class,
            'user' => \Pars\Admin\User\UserModel::class,
            'update' => \Pars\Admin\Update\UpdateModel::class,
            'role' => \Pars\Admin\Role\RoleModel::class,
            'rolepermission' => \Pars\Admin\RolePermission\RolePermissionModel::class,
            'userrole' => \Pars\Admin\UserRole\UserRoleModel::class,
            'translation' => \Pars\Admin\Translation\TranslationModel::class,
            'locale' => \Pars\Admin\Locale\LocaleModel::class,
            'cmsmenu' => \Pars\Admin\Cms\Menu\CmsMenuModel::class,
            'cmssubmenu' => \Pars\Admin\Cms\Menu\CmsSubMenuModel::class,
            'cmspage' => \Pars\Admin\Cms\Page\CmsPageModel::class,
            'cmsparagraph' => \Pars\Admin\Cms\Paragraph\CmsParagraphModel::class,
            'cmspageparagraph' => \Pars\Admin\Cms\PageParagraph\CmsPageParagraphModel::class,
            'filedirectory' => \Pars\Admin\File\Directory\FileDirectoryModel::class,
            'file' => \Pars\Admin\File\FileModel::class,
            'config' => \Pars\Admin\Config\ConfigModel::class,
            'import' => \Pars\Admin\Import\ImportModel::class,
            'articledata' => \Pars\Admin\Article\Data\ArticleDataModel::class
        ],

    ],
];
