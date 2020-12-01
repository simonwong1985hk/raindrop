<?php

/* Frontend is closed and backend is open */
if (file_exists($maintenanceFile) && (strpos($_SERVER[REQUEST_URI], 'admin') == false)) {
    include_once dirname(__FILE__) . '/errors/503.php';
    exit;
}
