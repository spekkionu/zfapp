<?php
// Here you can initialize variables that will for your tests
$login['admin']['username'] = 'admin';
$login['admin']['password'] = 'password';
$login['admin']['email'] = 'admin@example.com';

// Clear Mail Logs
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(SYSTEM.'/logs/mail', RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
foreach ($iterator as $path) {
    if ($path->isDir()) {
        @rmdir($path->__toString());
    } elseif ($path->getBasename() != '.htaccess') {
        @unlink($path->__toString());
    }
}