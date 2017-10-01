<?php

use Illuminate\Events\Dispatcher;
use Silber\Bouncer\Database\Role;
use Silber\Bouncer\Database\Ability;
use Illuminate\Database\Eloquent\Model;

class MultiTenancyTest extends BaseTestCase
{
    /**
     * Setup Elquent's events.
     *
     * @return void
     */
    public function setUp()
    {
        Model::setEventDispatcher(new Dispatcher);

        parent::setUp();
    }

    /**
     * Reset any scopes that have been applied in a test.
     *
     * @return void
     */
    public function tearDown()
    {
        Models::scope()->scopeTo(null);

        parent::tearDown();
    }

    public function test_creating_aroles_and_abilities_automatically_scopes_them()
    {
        $bouncer = $this->bouncer()->dontCache();

        $bouncer->scopeTo(1);

        $bouncer->allow('admin')->to('create', User::class);

        $this->assertEquals(1, $bouncer->ability()->query()->value('scope'));
        $this->assertEquals(1, $bouncer->role()->query()->value('scope'));
    }
}
