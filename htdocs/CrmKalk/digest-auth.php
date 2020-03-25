<?php declare(strict_types=1);
use Intervention\HttpAuth\Environment;
use Intervention\HttpAuth\HttpAuth;

return (function () {
    $incomingUser = (new Environment())->getToken()->toKey()->getUsername();
    $username = null;
    $clientId = null;
    $secret = null;
    foreach (include __DIR__ . '/credentials.php' as $username => [$password, $clientId, $secret]) {
        if ($username === $incomingUser) {
            break;
        }
    }
    if ($username) {
        $auth = HttpAuth::make()->digest()->realm('Secure')->username($username)->password($password);
        $auth->secure();
    }
    return [$clientId, $secret];
})();
