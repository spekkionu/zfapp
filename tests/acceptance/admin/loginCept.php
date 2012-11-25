<?php
$I = new WebGuy($scenario);
$I->wantTo('log into admin');
$I->amOnPage('/admin/login');
$I->fillField('username', $login['admin']['username']);
$I->fillField('password', $login['admin']['password']);
$I->click('Login');
$I->see("You have logged in successfully.");