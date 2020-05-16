<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200516132552 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE printedobject_category');
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_6354059507E5388');
        $this->addSql('DROP INDEX IDX_6354059507E5388 ON files');
        $this->addSql('ALTER TABLE files DROP printedobject_id');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A507E5388');
        $this->addSql('DROP INDEX IDX_E01FBE6A507E5388 ON images');
        $this->addSql('ALTER TABLE images DROP printedobject_id');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1507E5388');
        $this->addSql('DROP INDEX IDX_845CA2C1507E5388 ON order_details');
        $this->addSql('ALTER TABLE order_details DROP printedobject_id');
        $this->addSql('ALTER TABLE printedobject DROP price');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE printedobject_category (printedobject_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_8FD6EDC12469DE2 (category_id), INDEX IDX_8FD6EDC507E5388 (printedobject_id), PRIMARY KEY(printedobject_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE printedobject_category ADD CONSTRAINT FK_8FD6EDC12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE printedobject_category ADD CONSTRAINT FK_8FD6EDC507E5388 FOREIGN KEY (printedobject_id) REFERENCES printedobject (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE files ADD printedobject_id INT NOT NULL');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_6354059507E5388 FOREIGN KEY (printedobject_id) REFERENCES printedobject (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6354059507E5388 ON files (printedobject_id)');
        $this->addSql('ALTER TABLE images ADD printedobject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A507E5388 FOREIGN KEY (printedobject_id) REFERENCES printedobject (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E01FBE6A507E5388 ON images (printedobject_id)');
        $this->addSql('ALTER TABLE order_details ADD printedobject_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1507E5388 FOREIGN KEY (printedobject_id) REFERENCES printedobject (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_845CA2C1507E5388 ON order_details (printedobject_id)');
        $this->addSql('ALTER TABLE printedobject ADD price INT NOT NULL');
    }
}
