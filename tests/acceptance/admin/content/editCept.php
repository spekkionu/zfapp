<?php
$I = new WebGuy($scenario);
$I->wantTo('edit a content page');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/content/edit/1');
$I->fillField('#url', 'new-page-url');
$I->fillField('#title', 'New Page Title');
$I->fillField('#content', '<p>This is a new page.<br>It has new content.</p>');
$I->selectOption('active', '1');
$I->click('save');
$I->see('Sucessfully updated page.');
$I->see('New Page Title', 'td');