<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

/**
 * Handles the creation for table `{{%rbac}}`.
 */
class m160728_025849_create_rbac_table extends Migration
{
    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('SET foreign_key_checks = 0');
        // auth_assignment
        $this->createTable('{{%auth_assignment}}', [
            'item_name' => Schema::TYPE_STRING . "(64) NOT NULL",
            'user_id' => Schema::TYPE_STRING . "(64) NOT NULL",
            'created_at' => Schema::TYPE_INTEGER . "(11) NULL",
            'PRIMARY KEY (item_name, user_id)',
        ], $this->tableOptions);

// auth_item
        $this->createTable('{{%auth_item}}', [
            'name' => Schema::TYPE_STRING . "(64) NOT NULL",
            'type' => Schema::TYPE_INTEGER . "(11) NOT NULL",
            'description' => Schema::TYPE_TEXT . " NULL",
            'rule_name' => Schema::TYPE_STRING . "(64) NULL",
            'data' => Schema::TYPE_TEXT . " NULL",
            'created_at' => Schema::TYPE_INTEGER . "(11) NULL",
            'updated_at' => Schema::TYPE_INTEGER . "(11) NULL",
            'PRIMARY KEY (name)',
        ], $this->tableOptions);

// auth_item_child
        $this->createTable('{{%auth_item_child}}', [
            'parent' => Schema::TYPE_STRING . "(64) NOT NULL",
            'child' => Schema::TYPE_STRING . "(64) NOT NULL",
            'PRIMARY KEY (parent, child)',
        ], $this->tableOptions);

// auth_rule
        $this->createTable('{{%auth_rule}}', [
            'name' => Schema::TYPE_STRING . "(64) NOT NULL",
            'data' => Schema::TYPE_TEXT . " NULL",
            'created_at' => Schema::TYPE_INTEGER . "(11) NULL",
            'updated_at' => Schema::TYPE_INTEGER . "(11) NULL",
            'PRIMARY KEY (name)',
        ], $this->tableOptions);
        // fk: auth_assignment
        $this->addForeignKey('fk_auth_assignment_item_name', '{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name');

// fk: auth_item
        $this->addForeignKey('fk_auth_item_rule_name', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name');

// fk: auth_item_child
        $this->addForeignKey('fk_auth_item_child_parent', '{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name');
        $this->addForeignKey('fk_auth_item_child_child', '{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name');

        $sql = <<<sql
--
-- Dumping data for table {{%auth_item}}
--

LOCK TABLES {{%auth_item}} WRITE;
/*!40000 ALTER TABLE {{%auth_item}} DISABLE KEYS */;
INSERT INTO {{%auth_item}} VALUES ('superAdmin',1,'超级管理员',NULL,NULL,1443080982,1443408507),('/*',2,NULL,NULL,NULL,1458640575,1458640575);
/*!40000 ALTER TABLE {{%auth_item}} ENABLE KEYS */;
UNLOCK TABLES;


--
-- Dumping data for table {{%auth_assignment}}
--

LOCK TABLES {{%auth_assignment}} WRITE;
/*!40000 ALTER TABLE {{%auth_assignment}} DISABLE KEYS */;
INSERT INTO {{%auth_assignment}} VALUES ('superAdmin','1',1443080982);
/*!40000 ALTER TABLE {{%auth_assignment}} ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table {{%auth_item_child}}
--

LOCK TABLES {{%auth_item_child}} WRITE;
/*!40000 ALTER TABLE {{%auth_item_child}} DISABLE KEYS */;
INSERT INTO {{%auth_item_child}} VALUES ('superAdmin','/*');
/*!40000 ALTER TABLE {{%auth_item_child}} ENABLE KEYS */;
UNLOCK TABLES;
sql;

        $this->execute($sql);
        $this->execute('SET foreign_key_checks = 1');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->dropTable('{{%auth_assignment}}'); // fk: item_name
        $this->dropTable('{{%auth_item_child}}'); // fk: parent, child
        $this->dropTable('{{%auth_item}}'); // fk: rule_name
        $this->dropTable('{{%auth_rule}}');
        $this->execute('SET foreign_key_checks = 1');
    }
}
