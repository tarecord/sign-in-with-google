<?php

class PluginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    /**
     * Tests that the plugin can be activated successfully.
     *
     * @param AcceptanceTester $I The acceptance tester.
     *
     * @return void
     */
    public function tryToActivatePlugin(AcceptanceTester $I)
    {
        $I->loginAsAdmin();
        $I->amOnPluginsPage();
        $I->activatePlugin('sign-in-with-google');
        $I->seePluginActivated('sign-in-with-google');
    }
}
