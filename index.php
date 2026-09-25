<?php

$action = isset($_GET['a']) ? strtolower($_GET['a']) : 'accueil';

$fiche_ctrl = 'src/controllers/c.' . $action . '.php';
$fiche_view = 'src/views/v.' . $action . '.php';

if (!file_exists($fiche_ctrl) || !file_exists($fiche_view)) {
    header('Location: ?a=erreurs&code=404');
    exit;
}

require $fiche_ctrl;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Boutique de café</title>
    <link href="public/css/index.css" rel="stylesheet" />
</head>

<body>
    <?php require $fiche_view; ?>
</body>

</html>
