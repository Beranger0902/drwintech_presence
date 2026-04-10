<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OpCache vidé avec succès!";
} else {
    echo "OpCache non activé ou non disponible";
}
?>
