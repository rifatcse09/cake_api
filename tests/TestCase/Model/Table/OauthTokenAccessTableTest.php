<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OauthTokenAccessTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OauthTokenAccessTable Test Case
 */
class OauthTokenAccessTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OauthTokenAccessTable
     */
    public $OauthTokenAccess;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OauthTokenAccess',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('OauthTokenAccess') ? [] : ['className' => OauthTokenAccessTable::class];
        $this->OauthTokenAccess = TableRegistry::getTableLocator()->get('OauthTokenAccess', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OauthTokenAccess);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
