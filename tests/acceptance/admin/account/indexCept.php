<?php
$I = new WebGuy($scenario);
$I->wantTo('view user profile');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/account');
$I->see('My Account');
