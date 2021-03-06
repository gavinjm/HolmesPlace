<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190906215048 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, wallet_id VARCHAR(255) NOT NULL, timestamp BIGINT NOT NULL, description VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, balance_delta DOUBLE PRECISION NOT NULL, available_bal_delta DOUBLE PRECISION NOT NULL, balance DOUBLE PRECISION NOT NULL, available_balance DOUBLE PRECISION NOT NULL, cc_transaction_id VARCHAR(255) NOT NULL, cc_address BIGINT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE ticker CHANGE timestamp timestamp INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, wallet_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, timestamp BIGINT NOT NULL, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, currency VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, balance_delta DOUBLE PRECISION NOT NULL, available_bal_delta DOUBLE PRECISION NOT NULL, balance DOUBLE PRECISION NOT NULL, available_balance DOUBLE PRECISION NOT NULL, cc_transaction_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, cc_address BIGINT NOT NULL, value VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (userId INT AUTO_INCREMENT NOT NULL, username VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_0900_ai_ci, email VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_0900_ai_ci, password VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_0900_ai_ci, UNIQUE INDEX username (username), UNIQUE INDEX email (email), PRIMARY KEY(userId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE ticker CHANGE timestamp timestamp BIGINT UNSIGNED NOT NULL');
    }
}
