<?php
if (isset($_GET['code'])) {
    switch ($_GET['code']) {
        case '400': $code = "400 - Demande incorrecte"; break;
        case '403': $code = "403 - Accès interdit"; break;
        case '404': $code = "404 - Page non trouvée"; break;
        case '503': $code = "503 - Service indisponible"; break;
    }
}