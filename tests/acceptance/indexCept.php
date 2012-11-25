<?php
$I = new WebGuy($scenario);
$I->wantTo('visit the home page');
$I->amOnPage('/');
$I->see('Default');