<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128122032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unique_album DROP FOREIGN KEY `FK_30E8AA531F48AE04`');
        $this->addSql('ALTER TABLE unique_album DROP FOREIGN KEY `FK_30E8AA539D86650F`');
        $this->addSql('DROP TABLE unique_album');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E431F48AE04 FOREIGN KEY (artist_id_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E439D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE unique_album (id INT AUTO_INCREMENT NOT NULL, album_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, release_date INT NOT NULL, cover_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, artist_id_id INT DEFAULT NULL, user_id_id INT NOT NULL, INDEX IDX_30E8AA539D86650F (user_id_id), INDEX IDX_30E8AA531F48AE04 (artist_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE unique_album ADD CONSTRAINT `FK_30E8AA531F48AE04` FOREIGN KEY (artist_id_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE unique_album ADD CONSTRAINT `FK_30E8AA539D86650F` FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E431F48AE04');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E439D86650F');
    }
}
