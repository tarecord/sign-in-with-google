<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('Activate Sign In With Google');
$I->loginAsAdmin();
$I->amOnPluginsPage();
$I->activatePlugin('sign-in-with-google');
