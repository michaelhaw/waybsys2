<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219134539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE cargo (cargo_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, waybill_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL, unit CLOB NOT NULL, description CLOB NOT NULL, declared_value NUMERIC(11, 2) NOT NULL, length NUMERIC(8, 2) DEFAULT NULL, width NUMERIC(8, 2) DEFAULT NULL, height NUMERIC(8, 2) DEFAULT NULL, weight NUMERIC(8, 2) DEFAULT NULL, total_volume NUMERIC(11, 2) DEFAULT NULL, charge_type CLOB DEFAULT NULL, volume_charge NUMERIC(11, 2) DEFAULT NULL, weight_charge NUMERIC(11, 2) DEFAULT NULL, additional_charge NUMERIC(11, 2) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_3BEE577188F82440 ON cargo (waybill_id)');
        $this->addSql('CREATE TABLE customers (customer_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, customer_name VARCHAR(100) NOT NULL, customer_address CLOB DEFAULT NULL, customer_city CLOB DEFAULT NULL, customer_country CLOB DEFAULT NULL, customer_contact_no CLOB DEFAULT NULL, rate_volume NUMERIC(11, 2) NOT NULL, rate_value NUMERIC(11, 2) NOT NULL, customer_notes CLOB DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62534E21AB2DA71 ON customers (customer_name)');
        $this->addSql('CREATE TABLE employees (employee_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, last_name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, extra_name VARCHAR(50) DEFAULT NULL, middle_name VARCHAR(50) DEFAULT NULL, email VARCHAR(60) NOT NULL, local_office VARCHAR(3) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA82C300E7927C74 ON employees (email)');
        $this->addSql('CREATE TABLE user (user_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, employee_id INTEGER DEFAULT NULL, username VARCHAR(32) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(64) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498C03F15C ON user (employee_id)');
        $this->addSql('CREATE TABLE waybills (waybill_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, shipper_id INTEGER DEFAULT NULL, consignee_id INTEGER DEFAULT NULL, waybill_no VARCHAR(12) NOT NULL, destination VARCHAR(3) NOT NULL, waybill_date DATE DEFAULT NULL, total_amount NUMERIC(11, 2) NOT NULL, total_weight_charge NUMERIC(11, 2) DEFAULT NULL, total_value_charge NUMERIC(11, 2) DEFAULT NULL, total_cu_msmt_charge NUMERIC(11, 2) DEFAULT NULL, total_delivery_charge NUMERIC(11, 2) DEFAULT NULL, total_vat NUMERIC(11, 2) DEFAULT NULL, notes CLOB DEFAULT NULL, received_by VARCHAR(8) DEFAULT NULL, received_at VARCHAR(8) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_260EF675506B6B0F ON waybills (waybill_no)');
        $this->addSql('CREATE INDEX IDX_260EF67538459F23 ON waybills (shipper_id)');
        $this->addSql('CREATE INDEX IDX_260EF67596549545 ON waybills (consignee_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE cargo');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE employees');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE waybills');
    }
}
