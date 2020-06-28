<?php
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class OauthTokenAccessTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('oauth_token_access');
        $table->addColumn('users_id', 'integer', [
            'default' => null,
            'limit' => 10,
            'null' => false,
        ]); 
        $table->addColumn('token', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);
        $table->addColumn('refresh_token', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);
        $table->addColumn('expired', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('disabled', 'integer', [
            'default' => 0,
            'limit' => MysqlAdapter::INT_TINY, // 255
            'null' => false,
            'comment' => '0:active,1:inActive',
        ]);       
        $table->addColumn('updated', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('created', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);              
        $table->create();
    }
}
