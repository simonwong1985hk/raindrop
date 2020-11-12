<?php

function get_tls_version($sslversion = null)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, "https://www.howsmyssl.com/a/check");
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    if ($sslversion !== null) {
        curl_setopt($c, CURLOPT_SSLVERSION, $sslversion);
    }
    $rbody = curl_exec($c);
    if ($rbody === false) {
        $errno = curl_errno($c);
        $msg = curl_error($c);
        curl_close($c);
        return "Error! errno = " . $errno . ", msg = " . $msg;
    } else {
        $r = json_decode($rbody);
        curl_close($c);
        return $r->tls_version;
    }
}

echo "<pre>\n";

echo "OS: " . PHP_OS . "\n";
echo "uname: " . php_uname() . "\n";
echo "PHP version: " . phpversion() . "\n";

$curl_version = curl_version();
echo "curl version: " . $curl_version["version"] . "\n";
echo "SSL version: " . $curl_version["ssl_version"] . "\n";
echo "SSL version number: " . $curl_version["ssl_version_number"] . "\n";
echo "OPENSSL_VERSION_NUMBER: " . dechex(OPENSSL_VERSION_NUMBER) . "\n";

echo "TLS test (default): " . get_tls_version() . "\n";
echo "TLS test (TLS_v1): " . get_tls_version(1) . "\n";
echo "TLS test (TLS_v1_2): " . get_tls_version(6) . "\n";

echo "</pre>\n";
