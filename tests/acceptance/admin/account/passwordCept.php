<?php
$I = new WebGuy($scenario);
$I->wantTo('change user password');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/account/password');
$I->see('Edit Profile');
$I->fillField('#old_password', 'password');
$I->fillField('#password', 'newpassword');
$I->fillField('#confirm_password', 'newpassword');
$I->click('save');
$I->see('Successfully changed account password.');