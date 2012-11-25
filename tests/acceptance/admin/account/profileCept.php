<?php
$I = new WebGuy($scenario);
$I->wantTo('edit user profile');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/account/profile');
$I->see('Edit Profile');
$I->fillField('#username', 'newusername');
$I->fillField('#firstname', 'NewFirstName');
$I->fillField('#lastname', 'NewLastName');
$I->fillField('#email', 'newemail@example.com');
$I->click('save');
$I->see('Successfully updated profile.');
$I->see('newusername', 'td');