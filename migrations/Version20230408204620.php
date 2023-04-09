<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408204620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY bille-ev');
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY bille-ev');
        $this->addSql('ALTER TABLE billet CHANGE id_evenement id_evenement INT DEFAULT NULL, CHANGE code code VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT FK_1F034AF68B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('DROP INDEX bille-ev ON billet');
        $this->addSql('CREATE INDEX IDX_1F034AF68B13D439 ON billet (id_evenement)');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT bille-ev FOREIGN KEY (id_evenement) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fkproduiit');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fruserid1');
        $this->addSql('ALTER TABLE commande CHANGE id_produit id_produit INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL, CHANGE dateCommande datecommande DATETIME NOT NULL');
        $this->addSql('DROP INDEX fruserid1 ON commande');
        $this->addSql('CREATE INDEX IDX_6EEAA67D6B3CA4B ON commande (id_user)');
        $this->addSql('DROP INDEX fkproduiit ON commande');
        $this->addSql('CREATE INDEX IDX_6EEAA67DF7384557 ON commande (id_produit)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fkproduiit FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fruserid1 FOREIGN KEY (id_user) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE commandeproduit DROP FOREIGN KEY fkcommande');
        $this->addSql('ALTER TABLE commandeproduit CHANGE id_commende id_commende INT DEFAULT NULL');
        $this->addSql('DROP INDEX fkcommande ON commandeproduit');
        $this->addSql('CREATE INDEX IDX_D2C2F071B153DDBF ON commandeproduit (id_commende)');
        $this->addSql('ALTER TABLE commandeproduit ADD CONSTRAINT fkcommande FOREIGN KEY (id_commende) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY comment-ev');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY comment-user');
        $this->addSql('DROP INDEX comment-ev ON commentaire');
        $this->addSql('DROP INDEX comment-user ON commentaire');
        $this->addSql('ALTER TABLE commentaire ADD id_user_id INT DEFAULT NULL, ADD id_event_id INT DEFAULT NULL, DROP id_user, DROP id_evenement');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC212C041E FOREIGN KEY (id_event_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC79F37AE5 ON commentaire (id_user_id)');
        $this->addSql('CREATE INDEX IDX_67F068BC212C041E ON commentaire (id_event_id)');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY fk_pers');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY fk_categorie');
        $this->addSql('ALTER TABLE evenement CHANGE categorie_id categorie_id INT DEFAULT NULL, CHANGE createur_id createur_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX fk_pers ON evenement');
        $this->addSql('CREATE INDEX IDX_B26681E73A201E5 ON evenement (createur_id)');
        $this->addSql('DROP INDEX fk_categorie ON evenement');
        $this->addSql('CREATE INDEX IDX_B26681EBCF5E72D ON evenement (categorie_id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT fk_pers FOREIGN KEY (createur_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT fk_categorie FOREIGN KEY (categorie_id) REFERENCES categorie_evenement (id)');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY commande');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY commande');
        $this->addSql('ALTER TABLE facture CHANGE id_commende id_commende INT DEFAULT NULL, CHANGE datefacture datefacture DATETIME NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410B153DDBF FOREIGN KEY (id_commende) REFERENCES commande (id)');
        $this->addSql('DROP INDEX commande ON facture');
        $this->addSql('CREATE INDEX IDX_FE866410B153DDBF ON facture (id_commende)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT commande FOREIGN KEY (id_commende) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE galerie DROP FOREIGN KEY eve');
        $this->addSql('ALTER TABLE galerie DROP FOREIGN KEY eve');
        $this->addSql('ALTER TABLE galerie CHANGE event_id event_id INT DEFAULT NULL, CHANGE photo photo LONGBLOB NOT NULL');
        $this->addSql('ALTER TABLE galerie ADD CONSTRAINT FK_9E7D159071F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('DROP INDEX eve ON galerie');
        $this->addSql('CREATE INDEX IDX_9E7D159071F7E88B ON galerie (event_id)');
        $this->addSql('ALTER TABLE galerie ADD CONSTRAINT eve FOREIGN KEY (event_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY fk_event');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY fk_user');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY fk_event');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY fk_user');
        $this->addSql('ALTER TABLE jaime CHANGE user_id user_id INT DEFAULT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB7780571F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB77805A76ED395 FOREIGN KEY (user_id) REFERENCES personnes (id)');
        $this->addSql('DROP INDEX fk_event ON jaime');
        $this->addSql('CREATE INDEX IDX_3CB7780571F7E88B ON jaime (event_id)');
        $this->addSql('DROP INDEX fk_user ON jaime');
        $this->addSql('CREATE INDEX IDX_3CB77805A76ED395 ON jaime (user_id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT fk_event FOREIGN KEY (event_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES personnes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY fkuserr');
        $this->addSql('ALTER TABLE panier CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX fkuserr ON panier');
        $this->addSql('CREATE INDEX IDX_24CC0DF2A76ED395 ON panier (user_id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT fkuserr FOREIGN KEY (user_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE panierproduit DROP FOREIGN KEY fkidpanier');
        $this->addSql('ALTER TABLE panierproduit DROP FOREIGN KEY fkidpanier');
        $this->addSql('ALTER TABLE panierproduit DROP FOREIGN KEY idproduit');
        $this->addSql('ALTER TABLE panierproduit CHANGE id_panier id_panier INT DEFAULT NULL, CHANGE id_produit id_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panierproduit ADD CONSTRAINT FK_656FE9BA2FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id)');
        $this->addSql('DROP INDEX fkidpanier ON panierproduit');
        $this->addSql('CREATE INDEX IDX_656FE9BA2FBB81F ON panierproduit (id_panier)');
        $this->addSql('DROP INDEX idproduit ON panierproduit');
        $this->addSql('CREATE INDEX IDX_656FE9BAF7384557 ON panierproduit (id_produit)');
        $this->addSql('ALTER TABLE panierproduit ADD CONSTRAINT fkidpanier FOREIGN KEY (id_panier) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panierproduit ADD CONSTRAINT idproduit FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('DROP INDEX unique_email ON personnes');
        $this->addSql('ALTER TABLE personnes CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE role role VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY fk_vendeur');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY fkcategorie');
        $this->addSql('ALTER TABLE produit CHANGE image image LONGBLOB DEFAULT NULL');
        $this->addSql('DROP INDEX fk_vendeur ON produit');
        $this->addSql('CREATE INDEX IDX_29A5EC27858C065E ON produit (vendeur_id)');
        $this->addSql('DROP INDEX fkcategorie ON produit');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_vendeur FOREIGN KEY (vendeur_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fkcategorie FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY quiz');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY question');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY quiz');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY question');
        $this->addSql('ALTER TABLE quiz_question CHANGE quiz_id quiz_id INT DEFAULT NULL, CHANGE question_id question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (quiz_id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (question_id)');
        $this->addSql('DROP INDEX quiz ON quiz_question');
        $this->addSql('CREATE INDEX IDX_6033B00B853CD175 ON quiz_question (quiz_id)');
        $this->addSql('DROP INDEX question ON quiz_question');
        $this->addSql('CREATE INDEX IDX_6033B00B1E27F6BF ON quiz_question (question_id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT quiz FOREIGN KEY (quiz_id) REFERENCES quiz (quiz_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT question FOREIGN KEY (question_id) REFERENCES question (question_id) ON DELETE CASCADE');
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
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY FK_1F034AF68B13D439');
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY FK_1F034AF68B13D439');
        $this->addSql('ALTER TABLE billet CHANGE id_evenement id_evenement INT NOT NULL, CHANGE code code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT bille-ev FOREIGN KEY (id_evenement) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_1f034af68b13d439 ON billet');
        $this->addSql('CREATE INDEX bille-ev ON billet (id_evenement)');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT FK_1F034AF68B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6B3CA4B');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF7384557');
        $this->addSql('ALTER TABLE commande CHANGE id_user id_user INT NOT NULL, CHANGE id_produit id_produit INT NOT NULL, CHANGE datecommande dateCommande DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('DROP INDEX idx_6eeaa67d6b3ca4b ON commande');
        $this->addSql('CREATE INDEX fruserid1 ON commande (id_user)');
        $this->addSql('DROP INDEX idx_6eeaa67df7384557 ON commande');
        $this->addSql('CREATE INDEX fkproduiit ON commande (id_produit)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B3CA4B FOREIGN KEY (id_user) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commandeproduit DROP FOREIGN KEY FK_D2C2F071B153DDBF');
        $this->addSql('ALTER TABLE commandeproduit CHANGE id_commende id_commende INT NOT NULL');
        $this->addSql('DROP INDEX idx_d2c2f071b153ddbf ON commandeproduit');
        $this->addSql('CREATE INDEX fkcommande ON commandeproduit (id_commende)');
        $this->addSql('ALTER TABLE commandeproduit ADD CONSTRAINT FK_D2C2F071B153DDBF FOREIGN KEY (id_commende) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC212C041E');
        $this->addSql('DROP INDEX IDX_67F068BC79F37AE5 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BC212C041E ON commentaire');
        $this->addSql('ALTER TABLE commentaire ADD id_user INT NOT NULL, ADD id_evenement INT NOT NULL, DROP id_user_id, DROP id_event_id');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT comment-ev FOREIGN KEY (id_evenement) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT comment-user FOREIGN KEY (id_user) REFERENCES personnes (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX comment-ev ON commentaire (id_evenement)');
        $this->addSql('CREATE INDEX comment-user ON commentaire (id_user)');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E73A201E5');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EBCF5E72D');
        $this->addSql('ALTER TABLE evenement CHANGE createur_id createur_id INT NOT NULL, CHANGE categorie_id categorie_id INT NOT NULL');
        $this->addSql('DROP INDEX idx_b26681ebcf5e72d ON evenement');
        $this->addSql('CREATE INDEX fk_categorie ON evenement (categorie_id)');
        $this->addSql('DROP INDEX idx_b26681e73a201e5 ON evenement');
        $this->addSql('CREATE INDEX fk_pers ON evenement (createur_id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E73A201E5 FOREIGN KEY (createur_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_evenement (id)');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410B153DDBF');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410B153DDBF');
        $this->addSql('ALTER TABLE facture CHANGE id_commende id_commende INT NOT NULL, CHANGE datefacture datefacture DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT commande FOREIGN KEY (id_commende) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_fe866410b153ddbf ON facture');
        $this->addSql('CREATE INDEX commande ON facture (id_commende)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410B153DDBF FOREIGN KEY (id_commende) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE galerie DROP FOREIGN KEY FK_9E7D159071F7E88B');
        $this->addSql('ALTER TABLE galerie DROP FOREIGN KEY FK_9E7D159071F7E88B');
        $this->addSql('ALTER TABLE galerie CHANGE event_id event_id INT NOT NULL, CHANGE photo photo LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE galerie ADD CONSTRAINT eve FOREIGN KEY (event_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_9e7d159071f7e88b ON galerie');
        $this->addSql('CREATE INDEX eve ON galerie (event_id)');
        $this->addSql('ALTER TABLE galerie ADD CONSTRAINT FK_9E7D159071F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB7780571F7E88B');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB77805A76ED395');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB7780571F7E88B');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB77805A76ED395');
        $this->addSql('ALTER TABLE jaime CHANGE event_id event_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT fk_event FOREIGN KEY (event_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES personnes (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_3cb7780571f7e88b ON jaime');
        $this->addSql('CREATE INDEX fk_event ON jaime (event_id)');
        $this->addSql('DROP INDEX idx_3cb77805a76ed395 ON jaime');
        $this->addSql('CREATE INDEX fk_user ON jaime (user_id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB7780571F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB77805A76ED395 FOREIGN KEY (user_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE panier CHANGE user_id user_id INT NOT NULL');
        $this->addSql('DROP INDEX idx_24cc0df2a76ed395 ON panier');
        $this->addSql('CREATE INDEX fkuserr ON panier (user_id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE panierproduit DROP FOREIGN KEY FK_656FE9BA2FBB81F');
        $this->addSql('ALTER TABLE panierproduit DROP FOREIGN KEY FK_656FE9BA2FBB81F');
        $this->addSql('ALTER TABLE panierproduit DROP FOREIGN KEY FK_656FE9BAF7384557');
        $this->addSql('ALTER TABLE panierproduit CHANGE id_panier id_panier INT NOT NULL, CHANGE id_produit id_produit INT NOT NULL');
        $this->addSql('ALTER TABLE panierproduit ADD CONSTRAINT fkidpanier FOREIGN KEY (id_panier) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_656fe9ba2fbb81f ON panierproduit');
        $this->addSql('CREATE INDEX fkidpanier ON panierproduit (id_panier)');
        $this->addSql('DROP INDEX idx_656fe9baf7384557 ON panierproduit');
        $this->addSql('CREATE INDEX idproduit ON panierproduit (id_produit)');
        $this->addSql('ALTER TABLE panierproduit ADD CONSTRAINT FK_656FE9BA2FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE panierproduit ADD CONSTRAINT FK_656FE9BAF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE personnes CHANGE prenom prenom VARCHAR(20) NOT NULL, CHANGE role role VARCHAR(30) DEFAULT \'utilisateur\'');
        $this->addSql('CREATE UNIQUE INDEX unique_email ON personnes (email)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27858C065E');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE produit CHANGE image image BLOB DEFAULT NULL');
        $this->addSql('DROP INDEX idx_29a5ec27858c065e ON produit');
        $this->addSql('CREATE INDEX fk_vendeur ON produit (vendeur_id)');
        $this->addSql('DROP INDEX idx_29a5ec27bcf5e72d ON produit');
        $this->addSql('CREATE INDEX fkcategorie ON produit (categorie_id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27858C065E FOREIGN KEY (vendeur_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B1E27F6BF');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B1E27F6BF');
        $this->addSql('ALTER TABLE quiz_question CHANGE quiz_id quiz_id INT NOT NULL, CHANGE question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT quiz FOREIGN KEY (quiz_id) REFERENCES quiz (quiz_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT question FOREIGN KEY (question_id) REFERENCES question (question_id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_6033b00b853cd175 ON quiz_question');
        $this->addSql('CREATE INDEX quiz ON quiz_question (quiz_id)');
        $this->addSql('DROP INDEX idx_6033b00b1e27f6bf ON quiz_question');
        $this->addSql('CREATE INDEX question ON quiz_question (question_id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (quiz_id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (question_id)');
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
