<?php declare(strict_types=1);
use Intervention\HttpAuth\Environment;
use Intervention\HttpAuth\Exception\AuthentificationException;
use Intervention\HttpAuth\Vault\DigestVault;

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
        $realm = basename(__DIR__);
        $auth = new class ($realm, $username, $password) extends DigestVault {
            protected function denyAccess(): void
            {
                header('WWW-Authenticate: ' . (string)$this->getDirective(), true, 401);
                throw new AuthentificationException('401 Unauthorized for ' . $this->realm);
            }
        };
        $auth->secure();
    }
    return [$clientId, $secret];
})();
