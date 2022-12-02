<?php
/**
 * @see https://github.com/Edujugon/PushNotification
 */

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAWv9zizs:APA91bEnXNogJg0jQQdwFfnd0XPeNesHlVPsnq7TU3K2J8xui91BuB71kx3FpWN3VoZ5kBQSOrGPx5E_KdGZbQNhZlymgwcNp9lItod9piKwdNxoRYCJ9ak-zyDwjBRLmc2zn41MJKWx',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAWv9zizs:APA91bEnXNogJg0jQQdwFfnd0XPeNesHlVPsnq7TU3K2J8xui91BuB71kx3FpWN3VoZ5kBQSOrGPx5E_KdGZbQNhZlymgwcNp9lItod9piKwdNxoRYCJ9ak-zyDwjBRLmc2zn41MJKWx',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
        'passPhrase' => 'secret', //Optional
        'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => true,
    ],
];
