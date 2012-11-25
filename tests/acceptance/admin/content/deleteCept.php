<?php
$I = new WebGuy($scenario);
$I->wantTo('delete a content page');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/content');
$I->see('About Us', 'td');
$I->amOnPage('/admin/content/delete/1');
$I->click('delete');
$I->see('Sucessfully deleted page.');
$I->dontSee('About Us', 'td');