<?php
$I = new WebGuy($scenario);
$I->wantTo('edit an administrator');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/administrator/edit/2');
$I->fillField('#username', 'newusername');
$I->fillField('#firstname', 'Newfirstname');
$I->fillField('#lastname', 'Newlastname');
$I->fillField('#email', 'newemail@example.com');
$I->selectOption('active', '1');
$I->click('save');
$I->see('Sucessfully updated administrator.');
$I->see('newusername', 'td');