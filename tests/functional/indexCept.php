<?php
$I = new TestGuy($scenario);
$I->wantTo('visit the home page');
$I->amOnPage('/');
$I->see('Default');