<?php

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022140851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        //add module specific table. To be used with a shop model it needs OXID and TIMESTAMP columns.
        if (!$schema->hasTable('oeem_product_vote')) {
            $this->addSql("CREATE TABLE `oeem_product_vote` (
            `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci  NOT NULL
                COMMENT 'Primary oxid',
            `OXSHOPID` int(11) NOT NULL DEFAULT '0'
                COMMENT 'Shop id (oxshops), value 0 in case no shop was specified',
            `OXARTID` char(32) character set latin1 collate latin1_general_ci NOT NULL
                COMMENT 'Product id (oxarticles)',
            `OXUSERID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
                COMMENT 'User id',
            `OXVOTE` tinyint(1) NOT NULL
                COMMENT 'Vote up (1) or down (0)',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                COMMENT 'Timestamp',
            PRIMARY KEY (`OXID`),
            KEY `OXARTID` (`OXARTID`),
            UNIQUE KEY `OXMAINIDX` (`OXSHOPID`, `OXUSERID`, `OXARTID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
