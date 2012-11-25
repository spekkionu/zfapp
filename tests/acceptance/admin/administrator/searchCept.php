<?php
$I = new WebGuy($scenario);
$I->wantTo('add an administrator');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/administrator');
$I->see('testadmin', 'td');
$I->fillField('#username', 'admin');
$I->fillField('#name', 'Administrator');
$I->fillField('#email', $login['admin']['email']);
$I->selectOption('active', '1');
$I->click('search');
$I->see('admin', 'td');
$I->dontSee('testadmin', 'td');