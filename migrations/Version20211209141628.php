<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211209141628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779BCF5E72D');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_publication (tag_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_B3F51D99BAD26311 (tag_id), INDEX IDX_B3F51D9938B217A7 (publication_id), PRIMARY KEY(tag_id, publication_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_publication ADD CONSTRAINT FK_B3F51D99BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_publication ADD CONSTRAINT FK_B3F51D9938B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_AF3C6779BCF5E72D ON publication');
        $this->addSql('ALTER TABLE publication ADD titre VARCHAR(255) NOT NULL, DROP categorie_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_publication DROP FOREIGN KEY FK_B3F51D99BAD26311');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, relation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_publication');
        $this->addSql('ALTER TABLE publication ADD categorie_id INT DEFAULT NULL, DROP titre');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779BCF5E72D FOREIGN KEY (categorie_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779BCF5E72D ON publication (categorie_id)');
    }
}
