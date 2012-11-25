<?php
$I = new WebGuy($scenario);
$I->wantTo('view the administrator list');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/administrator');
$I->see('Manage Administrators');
