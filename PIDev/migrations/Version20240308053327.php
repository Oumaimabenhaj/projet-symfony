<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308053327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514379F37AE5');
        $this->addSql('DROP INDEX IDX_C015514379F37AE5 ON blog');
        $this->addSql('ALTER TABLE blog CHANGE id_user_id idadmin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143CEFECA1D FOREIGN KEY (idadmin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_C0155143CEFECA1D ON blog (idadmin_id)');
        $this->addSql('ALTER TABLE emploi CHANGE description description VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE medicament ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rendezvous ADD etat TINYINT(1) DEFAULT NULL, CHANGE description description VARCHAR(25555) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143CEFECA1D');
        $this->addSql('DROP INDEX IDX_C0155143CEFECA1D ON blog');
        $this->addSql('ALTER TABLE blog CHANGE idadmin_id id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514379F37AE5 FOREIGN KEY (id_user_id) REFERENCES global_user (id)');
        $this->addSql('CREATE INDEX IDX_C015514379F37AE5 ON blog (id_user_id)');
        $this->addSql('ALTER TABLE emploi CHANGE description description MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE medicament DROP image');
        $this->addSql('ALTER TABLE rendezvous DROP etat, CHANGE description description MEDIUMTEXT DEFAULT NULL');
    }
}
