<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200517103525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE files ADD printedobject_id INT NOT NULL');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_6354059507E5388 FOREIGN KEY (printedobject_id) REFERENCES printedobject (id)');
        $this->addSql('CREATE INDEX IDX_6354059507E5388 ON files (printedobject_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_6354059507E5388');
        $this->addSql('DROP INDEX IDX_6354059507E5388 ON files');
        $this->addSql('ALTER TABLE files DROP printedobject_id');
    }
}
