<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200516132734 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_details ADD objects_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C14BEE6933 FOREIGN KEY (objects_id) REFERENCES printedobject (id)');
        $this->addSql('CREATE INDEX IDX_845CA2C14BEE6933 ON order_details (objects_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C14BEE6933');
        $this->addSql('DROP INDEX IDX_845CA2C14BEE6933 ON order_details');
        $this->addSql('ALTER TABLE order_details DROP objects_id');
    }
}
