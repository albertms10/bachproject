<?php define("BASENAME", basename(getcwd())) ?>
<?php define("BASENAME_1", basename(dirname(getcwd(), 1))) ?>
<?php define("BASENAME_2", basename(dirname(getcwd(), 2))) ?>
<?php define("HTML_PATH", "/bachproject/") ?>
<base href="<?php echo HTML_PATH ?>">

<!-- CSS -->
<!-- <link rel="stylesheet" href="<?php echo HTML_PATH ?>assets/css/semantic.min.css"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
<link rel="stylesheet" href="<?php echo HTML_PATH ?>assets/css/style.css">

<?php setlocale(LC_TIME, 'ca_ES', 'Catalan_Spain', 'Catalan') ?>
<?php date_default_timezone_set('Europe/Madrid') ?>

<?php require_once ROOT . "assets/php/queries/session-controller.php" ?>
<?php require_once "functions.php" ?>