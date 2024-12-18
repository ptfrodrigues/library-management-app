<?php

return [
    'permissions' => [
        'book' => [
            'view_book',
            'create_book',
            'update_book',
            'soft_delete_book',
            'force_delete_book',
        ],
        'author' => [
            'view_author',
            'create_author',
            'update_author',
            'soft_delete_author',
            'force_delete_author',
        ],
        'loan' => [
            'loan_book',
        ],
        'user' => [
            'create_user',
            'edit_user',
            'delete_user',
        ],
    ],

    'roles' => [
        'admin' => '*',
        'manager' => [
            'book', 'author', 'user',
        ],
        'librarian' => [
            'book', 'author',
        ],
        'member' => [
            'loan',
        ],
    ],

    'user_management' => [
        'admin' => ['manager', 'librarian', 'member'],
        'manager' => ['manager', 'librarian'],
        'librarian' => [],
    ],
];
