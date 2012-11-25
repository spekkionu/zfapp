<?php
$I = new WebGuy($scenario);
$I->wantTo('search content pages');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/content');
$I->see('Contact Us', 'td');
$I->fillField('#url', 'about/us');
$I->fillField('#title', 'About Us');
$I->selectOption('active', '1');
$I->click('search');
$I->dontSee('Contact Us', 'td');