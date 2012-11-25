<?php
$I = new WebGuy($scenario);
$I->wantTo('visit the admin home page');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->see('Admin Dashboard');
$I->seeInCurrentUrl('/admin');