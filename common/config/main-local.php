<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=127.0.0.1;dbname=store_db',
            'username' => 'helder',
            'password' => 'Local@1234',
            'charset' => 'utf8mb4',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'transport' => [
                'scheme' => 'smtps',
                'class' => 'Swift_SmtpTransport',
                'host' => 'sandbox.smtp.mailtrap.io',
                'username' => '7b845b4515bc81',
                'password' => '3889e618845383',
                'port' => '2525',
                'encryption' => 'tls',
                'dsn' => 'smtp://7b845b4515bc81:3889e618845383@sandbox.smtp.mailtrap.io:2525',
            ],
        ],
    ],
];
