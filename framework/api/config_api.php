<?php

// Conteudo da variavel $applicationFile está no index.php da pasta API
$ini = parse_ini_file($applicationFile, true);

define ( 'API_DISPLAY_ERRORS_DETAILS', true );
define ( 'API_SYSTEM_NAME', $ini['general']['title'] );
define ( 'API_SYSTEM_ACRONYM', $ini['general']['application'] );
define ( 'API_SYSTEM_VERSION', $ini['system']['system_version'] );