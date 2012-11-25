<?php
$I = new WebGuy($scenario);
$I->wantTo('delete an administrator');
$I->logIntoAdmin($login['admin']['username'],  $login['admin']['password']);
$I->amOnPage('/admin/administrator');
$I->see('testadmin', 'td');
$I->amOnPage('/admin/administrator/delete/2');
$I->click('delete');
$I->see('Sucessfully deleted administrator.');
$I->dontSee('testadmin', 'td');