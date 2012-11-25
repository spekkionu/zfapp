<?php
$I = new WebGuy($scenario);
$I->wantTo('view the content list');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/content');
$I->see('Manage Content');
