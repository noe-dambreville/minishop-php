<?php
$messagesParCode = [
    400 => 'Demande incorrecte',
    403 => 'Accès interdit',
    404 => 'Page non trouvée',
    503 => 'Service indisponible',
];

$codeHttp = ($_GET['code'] ?? 404);
if (!isset($messagesParCode[$codeHttp])) {
    $codeHttp = 404;
}

http_response_code($codeHttp);

$messageErreur = $codeHttp . ' - ' . $messagesParCode[$codeHttp];
