<?php

return [
    'gcp' => [
        'project_id' => env('GCP_PROJECT_ID', null),
        'bucket_name' => env('GCS_BUCKET_NAME', null),
        'auth_key_path' => env('AUTH_KEY_PATH', null),
        'dir' => env('GCS_BASE_DIR', '/local')
    ],
    'master_user_password' => 'password',
    'master_user_email' => 'stlaun@stlaun.jp',
    'japanese_check' => "/^[一-龠　-ー]+$/u",
    'PROGRESS_STATUS' => [
        1 => "作業中",
        2 => "確認中",
        3 => "完了",
    ],
    'APPLICATION_FLG_OFF' => 0,
    'APPLICATION_FLG_ON' => 1,
    'students' => [
        'GENDER_LIST' => [
            '男性' => 1,
            '女性' => 2,
            '未回答' => null,
        ],
        'ACADEMIC_TYPE_LIST' => [
            '中卒' => 0,
            '高卒' => 1,
            '専門卒' => 2,
            '短大卒(文系)' => 3,
            '短大卒(理系)' => 4,
            '大卒(文系)' => 5,
            '大卒(理系)' => 6,
            '大院卒(文系)' => 7,
            '大院卒(理系)' => 8,
            '未回答' => null,
        ],
        'INDUSTRY_LIST' => [
            '通信販売業' => 1,
            '金融／保険業' => 2,
            '通信業' => 3,
            '流通／小売業' => 4,
            '運輸業' => 5,
            '旅行業' => 6,
            '電力／ガス／水道' => 7,
            '製造業' => 8,
            'サービス業' => 9,
            '医療／福祉' => 10,
            '建設／不動産業' => 11,
            '放送／出版／マスコミ' => 12,
            '未回答' => null,
        ],
        'JOB_STATUS_LIST' => [
            '正社員' => 0,
            'パート・アルバイト' => 1,
            '契約社員' => 2,
            '派遣社員' => 3,
            '個人事業主' => 4,
            '未回答' => null,
        ],
        'HAVE_LIST' => [
            '有' => 1,
            '無' => 0,
        ],
        'CLUB_TYPE_LIST' => [
            '無' => 0,
            '運動部' => 1,
            '文化部' => 2,
        ],
    ]
];
