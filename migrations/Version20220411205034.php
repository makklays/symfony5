<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411205034 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update entidad Paciente';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paciente CHANGE pud nie VARCHAR(255) DEFAULT NULL, CHANGE bithday birthday DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE paciente ADD CONSTRAINT FK_C6CBA95E87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_C6CBA95E87F4FB17 ON paciente (doctor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paciente DROP FOREIGN KEY FK_C6CBA95E87F4FB17');
        $this->addSql('DROP INDEX IDX_C6CBA95E87F4FB17 ON paciente');
        $this->addSql('ALTER TABLE paciente CHANGE nie pud VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE birthday bithday DATE DEFAULT NULL');
    }
}
