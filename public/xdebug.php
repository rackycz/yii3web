<?php
if (($_ENV['APP_ENV'] ?? 'prod') !== 'dev') {
    http_response_code(404);
    exit;
}
xdebug_info();
