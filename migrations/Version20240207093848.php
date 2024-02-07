<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207093848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurante ADD email VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD password VARCHAR(255) NOT NULL, DROP correo, DROP clave, DROP ciudad, CHANGE pais pais VARCHAR(255) NOT NULL, CHANGE direccion direccion VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5957C275E7927C74 ON restaurante (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5957C275E7927C74 ON restaurante');
        $this->addSql('ALTER TABLE restaurante ADD correo VARCHAR(60) NOT NULL, ADD clave VARCHAR(60) NOT NULL, ADD ciudad VARCHAR(50) NOT NULL, DROP email, DROP roles, DROP password, CHANGE pais pais VARCHAR(50) NOT NULL, CHANGE direccion direccion VARCHAR(50) NOT NULL');
    }
}
