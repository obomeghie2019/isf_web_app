<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'latest_version'  => '1.0.3',
    'min_version'     => '1.0.0',
    'force_update'    => true,
    'update_message'  => 'New Features Added!  Please Update your Application',
]);
