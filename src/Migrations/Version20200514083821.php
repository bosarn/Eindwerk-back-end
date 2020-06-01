<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200514083821 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (cat_id INT NOT NULL, cat_beschrijving VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, cat_naam VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(cat_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE files (fil_id INT NOT NULL, fil_obj_id INT DEFAULT NULL, fil_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, fil_GCODE LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX fil_obj_id (fil_obj_id), PRIMARY KEY(fil_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE object (obj_id INT NOT NULL, obj_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, obj_printtime INT DEFAULT NULL, obj_price INT DEFAULT NULL, obj_size VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, obj_GCODE VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(obj_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE object_category (obj_cat_id INT NOT NULL, object_id INT DEFAULT NULL, category_id INT DEFAULT NULL, INDEX category_id (category_id), INDEX object_id (object_id), PRIMARY KEY(obj_cat_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_details (ord_det_id INT NOT NULL, ord_det_order_id INT DEFAULT NULL, ord_det_object_id INT DEFAULT NULL, ord_det_objectstatus VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ord_det_quantity INT DEFAULT NULL, INDEX ord_det_object_id (ord_det_object_id), INDEX ord_det_order_id (ord_det_order_id), PRIMARY KEY(ord_det_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE orders (order_id INT NOT NULL, order_user_id INT DEFAULT NULL, order_beschrijving VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, order_status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, order_date VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, order_shipping_adress VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, order_invoice LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX order_user_id (order_user_id), PRIMARY KEY(order_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE postcodes (post_id INT NOT NULL, post_gemeente INT DEFAULT NULL, post_postcode INT DEFAULT NULL, PRIMARY KEY(post_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prices (pri_id INT NOT NULL, pri_obj_id INT DEFAULT NULL, pri_val INT DEFAULT NULL, pri_detail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX pri_obj_id (pri_obj_id), PRIMARY KEY(pri_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE printer (print_id INT NOT NULL, pr_user_id INT DEFAULT NULL, print_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, print_location VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, print_status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX pr_user_id (pr_user_id), PRIMARY KEY(print_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE printerprofile (pp_id INT NOT NULL, pp_printer_id INT DEFAULT NULL, pp_settings JSON DEFAULT NULL, INDEX pp_printer_id (pp_printer_id), PRIMARY KEY(pp_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (usr_id INT NOT NULL, usr_username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, usr_sur_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, usr_first_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, usr_email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, usr_router VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, usr_adress VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, usr_postcode INT DEFAULT NULL, usr_role VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX usr_username (usr_username), PRIMARY KEY(usr_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT files_ibfk_1 FOREIGN KEY (fil_obj_id) REFERENCES object (obj_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE object_category ADD CONSTRAINT object_category_ibfk_1 FOREIGN KEY (object_id) REFERENCES object (obj_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE object_category ADD CONSTRAINT object_category_ibfk_2 FOREIGN KEY (category_id) REFERENCES category (cat_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT order_details_ibfk_1 FOREIGN KEY (ord_det_order_id) REFERENCES orders (order_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT order_details_ibfk_2 FOREIGN KEY (ord_det_object_id) REFERENCES object (obj_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT orders_ibfk_1 FOREIGN KEY (order_user_id) REFERENCES users (usr_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE prices ADD CONSTRAINT prices_ibfk_1 FOREIGN KEY (pri_obj_id) REFERENCES object (obj_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE printer ADD CONSTRAINT printer_ibfk_1 FOREIGN KEY (pr_user_id) REFERENCES users (usr_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE printer ADD CONSTRAINT printer_ibfk_2 FOREIGN KEY (pr_user_id) REFERENCES users (usr_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE printerprofile ADD CONSTRAINT printerprofile_ibfk_1 FOREIGN KEY (pp_printer_id) REFERENCES printer (print_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE images MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE images DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE images ADD img_id INT NOT NULL, ADD img_obj_id INT DEFAULT NULL, ADD img_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD img_beschrijving VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD img_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP id, DROP name, DROP description, DROP path');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT images_ibfk_1 FOREIGN KEY (img_obj_id) REFERENCES object (obj_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX img_obj_id ON images (img_obj_id)');
        $this->addSql('ALTER TABLE images ADD PRIMARY KEY (img_id)');
    }
}
