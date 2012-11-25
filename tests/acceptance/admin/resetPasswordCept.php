<?php
$I = new WebGuy($scenario);
$I->wantTo('reset user password');
$I->amOnPage('/admin/reset-password');
$I->fillField('#email', $login['admin']['email']);
$I->click('#save');
$I->see("An email has been sent to {$login['admin']['email']} with instructions to complete the password reset. Your password has not yet been modified.");