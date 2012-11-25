<?php
$I = new WebGuy($scenario);
$I->wantTo('log out of admin');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin');
$I->seeInCurrentUrl('/admin');
$I->amOnPage('/admin/logout');
$I->amOnPage('/admin');
$I->seeInCurrentUrl('/admin/login');