<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208192228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidosproductos DROP FOREIGN KEY FK_3B085CFA7645698E');
        $this->addSql('ALTER TABLE pedidosproductos DROP FOREIGN KEY FK_3B085CFA4854653A');
        $this->addSql('DROP INDEX IDX_3B085CFA4854653A ON pedidosproductos');
        $this->addSql('DROP INDEX IDX_3B085CFA7645698E ON pedidosproductos');
        $this->addSql('ALTER TABLE pedidosproductos ADD pedido INT NOT NULL, ADD producto INT NOT NULL, DROP pedido_id, DROP producto_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidosproductos ADD pedido_id INT NOT NULL, ADD producto_id INT NOT NULL, DROP pedido, DROP producto');
        $this->addSql('ALTER TABLE pedidosproductos ADD CONSTRAINT FK_3B085CFA7645698E FOREIGN KEY (producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE pedidosproductos ADD CONSTRAINT FK_3B085CFA4854653A FOREIGN KEY (pedido_id) REFERENCES pedidos (id)');
        $this->addSql('CREATE INDEX IDX_3B085CFA4854653A ON pedidosproductos (pedido_id)');
        $this->addSql('CREATE INDEX IDX_3B085CFA7645698E ON pedidosproductos (producto_id)');
    }
}
