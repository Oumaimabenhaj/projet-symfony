<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307214912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossiermedical CHANGE resultatexamen resultatexamen VARCHAR(25555) DEFAULT NULL, CHANGE antecedantpersonnelles antecedantpersonnelles VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE emploi CHANGE description description VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance CHANGE medecamentprescrit medecamentprescrit VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE rendezvous ADD etat TINYINT(1) DEFAULT NULL, CHANGE description description VARCHAR(25555) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossiermedical CHANGE resultatexamen resultatexamen MEDIUMTEXT DEFAULT NULL, CHANGE antecedantpersonnelles antecedantpersonnelles MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE emploi CHANGE description description MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance CHANGE medecamentprescrit medecamentprescrit MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendezvous DROP etat, CHANGE description description MEDIUMTEXT DEFAULT NULL');
    }
}
