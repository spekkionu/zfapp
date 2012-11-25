<?php
$I = new WebGuy($scenario);
$I->wantTo('add a content page');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/content/add');
$I->fillField('#url', 'new-page');
$I->fillField('#title', 'New Page');
$I->fillField('#content', '<p>This is a new page</p>');
$I->selectOption('active', '1');
$I->click('save');
$I->see('Sucessfully added page.');
$I->see('New Page', 'td');