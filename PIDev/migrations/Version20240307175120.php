<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307175120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5E42CF9');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCCEFECA1D');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5E42CF9 FOREIGN KEY (idblog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCCEFECA1D FOREIGN KEY (idadmin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE dossiermedical CHANGE resultatexamen resultatexamen VARCHAR(25555) DEFAULT NULL, CHANGE antecedantpersonnelles antecedantpersonnelles VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE emploi CHANGE description description VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3DF0FD358');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3BA9CD190');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3DF0FD358 FOREIGN KEY (userr_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE medicament ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ordonnance CHANGE medecamentprescrit medecamentprescrit VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE rendezvous CHANGE description description VARCHAR(25555) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5E42CF9');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCCEFECA1D');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5E42CF9 FOREIGN KEY (idblog_id) REFERENCES blog (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCCEFECA1D FOREIGN KEY (idadmin_id) REFERENCES admin (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dossiermedical CHANGE resultatexamen resultatexamen MEDIUMTEXT DEFAULT NULL, CHANGE antecedantpersonnelles antecedantpersonnelles MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE emploi CHANGE description description MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3DF0FD358');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3BA9CD190');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3DF0FD358 FOREIGN KEY (userr_id) REFERENCES admin (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medicament DROP image');
        $this->addSql('ALTER TABLE ordonnance CHANGE medecamentprescrit medecamentprescrit MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendezvous CHANGE description description MEDIUMTEXT DEFAULT NULL');
    }
}
