<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408172511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, cin VARCHAR(30) DEFAULT NULL, nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, telephone VARCHAR(30) DEFAULT NULL, adresse VARCHAR(30) DEFAULT NULL, photo LONGBLOB DEFAULT NULL, datenaissance DATE DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY res-billet');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY res-owner');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY res-billet');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY res-owner');
        $this->addSql('ALTER TABLE reservation CHANGE id_billet id_billet INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553934FF1B FOREIGN KEY (id_billet) REFERENCES billet (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556B3CA4B FOREIGN KEY (id_user) REFERENCES personnes (id)');
        $this->addSql('DROP INDEX res-billet ON reservation');
        $this->addSql('CREATE INDEX IDX_42C849553934FF1B ON reservation (id_billet)');
        $this->addSql('DROP INDEX res-owner ON reservation');
        $this->addSql('CREATE INDEX IDX_42C849556B3CA4B ON reservation (id_user)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT res-billet FOREIGN KEY (id_billet) REFERENCES billet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT res-owner FOREIGN KEY (id_user) REFERENCES personnes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY event');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY event');
        $this->addSql('ALTER TABLE session CHANGE evenement_id evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('DROP INDEX event ON session');
        $this->addSql('CREATE INDEX IDX_D044D5D4FD02F13 ON session (evenement_id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT event FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553934FF1B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556B3CA4B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553934FF1B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556B3CA4B');
        $this->addSql('ALTER TABLE reservation CHANGE id_billet id_billet INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT res-billet FOREIGN KEY (id_billet) REFERENCES billet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT res-owner FOREIGN KEY (id_user) REFERENCES personnes (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_42c849556b3ca4b ON reservation');
        $this->addSql('CREATE INDEX res-owner ON reservation (id_user)');
        $this->addSql('DROP INDEX idx_42c849553934ff1b ON reservation');
        $this->addSql('CREATE INDEX res-billet ON reservation (id_billet)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553934FF1B FOREIGN KEY (id_billet) REFERENCES billet (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556B3CA4B FOREIGN KEY (id_user) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4FD02F13');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4FD02F13');
        $this->addSql('ALTER TABLE session CHANGE evenement_id evenement_id INT NOT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT event FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_d044d5d4fd02f13 ON session');
        $this->addSql('CREATE INDEX event ON session (evenement_id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
    }
}
