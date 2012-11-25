<?php
$I = new WebGuy($scenario);
$I->wantTo('add an administrator');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/administrator/add');
$I->fillField('#username', 'newadmin');
$I->fillField('#password', 'password');
$I->fillField('#confirm_password', 'password');
$I->fillField('#firstname', 'Firstname');
$I->fillField('#lastname', 'Lastname');
$I->fillField('#email', 'newadmin@example.com');
$I->selectOption('active', '1');
$I->click('save');
$I->see('Sucessfully added administrator.');
$I->see('newadmin');