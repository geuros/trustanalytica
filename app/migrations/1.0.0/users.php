<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersMigration_100
 */
class UsersMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('users', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 50,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'surname',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 50,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'age',
                        [
                            'type' => Column::TYPE_TINYINTEGER,
                            'notNull' => true,
                            'size' => 2,
                            'after' => 'surname'
                        ]
                    ),
                    new Column(
                        'phone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 15,
                            'after' => 'age'
                        ]
                    ),
                    new Column(
                        'driver_licence',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 17,
                            'after' => 'phone'
                        ]
                    ),
                    new Column(
                        'address',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 1000,
                            'after' => 'driver_licence'
                        ]
                    ),
                    new Column(
                        'password',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 155,
                            'after' => 'address'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('driver_licence', ['driver_licence'], ''),
                    new Index('password', ['password'], '')
                ],
                'options' => [
                    'table_type' => 'BASE TABLE',
                    'auto_increment' => '1',
                    'engine' => 'InnoDB',
                    'table_collation' => 'utf8mb4_unicode_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
