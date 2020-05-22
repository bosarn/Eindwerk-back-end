<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200517103057 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_printedobject (category_id INT NOT NULL, printedobject_id INT NOT NULL, INDEX IDX_D44FC2E712469DE2 (category_id), INDEX IDX_D44FC2E7507E5388 (printedobject_id), PRIMARY KEY(category_id, printedobject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_printedobject ADD CONSTRAINT FK_D44FC2E712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_printedobject ADD CONSTRAINT FK_D44FC2E7507E5388 FOREIGN KEY (printedobject_id) REFERENCES printedobject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE category_printedobject');
        $this->addSql('ALTER TABLE orders CHANGE user_id user_id INT NOT NULL');
    }
}
