<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        
        $admin = new User();
        $admin->setEmail("admin@admin");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));        
        $admin->setName("admin");
        $admin->setAddress("4 quai de bordeaux");
        $admin->setPhone("06 54 35 46 56");
        $admin->setCountry("France");
        $admin->setZipCode("33100");
        $admin->setTown("Bordeaux");
        $manager->persist($admin);

        $user = new User();
        $user->setEmail("user@user");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $user->setName("user");        
        $user->setAddress("5 Avenue jean-michel");
        $user->setPhone("07 34 35 64 96");
        $user->setCountry("France");
        $user->setZipCode("46000");
        $user->setTown("CAHORS");
        $manager->persist($user);


        // Fixture 1 - Store banne chêne premium
        $product1 = new Product();
        $product1->setQuantity(12);
        $product1->setName('Store Banne Rétractable Chêne Massif Premium 4x3m');
        $product1->setDescription('Store banne haut de gamme en chêne massif européen avec structure rétractable électrique. Finition huilée naturelle résistante aux UV et aux intempéries. Toile acrylique déperlante 280g/m² avec protection solaire UPF 50+. Coffre intégré en aluminium laqué. Télécommande et capteur vent inclus. Garantie 5 ans structure.');
        $product1->setPrice(749.99);
        $product1->setImage('uploads/store-68cd282065ef2.jpg');
        $manager->persist($product1);

        // Fixture 2 - Store banne pin nordique
        $product2 = new Product();
        $product2->setQuantity(25);
        $product2->setName('Store Banne Pin Nordique Traité Autoclave 3x2.5m');
        $product2->setDescription('Store banne économique en pin nordique traité autoclave classe IV. Structure fixe avec bras articulés manuels. Bois certifié PEFC avec traitement fongicide et insecticide. Toile polyester enduite 250g/m² résistante aux déchirures. Installation murale avec fixations renforcées incluses. Excellent rapport qualité-prix.');
        $product2->setPrice(189.99);
        $product2->setImage('uploads/store2-68cd285cceac1.jpg');
        $manager->persist($product2);

        // Fixture 3 - Store banne teck exotique
        $product3 = new Product();
        $product3->setQuantity(8);
        $product3->setName('Store Banne Teck Birmanie Semi-Coffre 5x3.5m XXL');
        $product3->setDescription('Store banne d\'exception en teck de Birmanie grade A, naturellement imputrescible. Semi-coffre design avec mécanisme rétractable motorisé Somfy. Huile de teck premium pour protection longue durée. Toile technique Dickson Orchestra avec traitement Cleangard anti-salissures. Éclairage LED intégré et chauffage radiant en option. Pour terrasses luxueuses.');
        $product3->setPrice(1249.99);
        $product3->setImage('https://fastly.picsum.photos/id/877/400/400.jpg?hmac=1GLIHZpu-hjwP6VAuefuYNeMNNCuV9iBmsAMIu9XEE4');
        $manager->persist($product3);

        // Fixture 4 - Store banne bambou écologique
        $product4 = new Product();
        $product4->setQuantity(18);
        $product4->setName('Store Banne Bambou Écologique Fixe 2.5x2m Compact');
        $product4->setDescription('Store banne écologique en bambou massif traité thermiquement. Structure légère et résistante, idéale pour balcons et petites terrasses. Montage fixe avec inclinaison réglable. Matériau 100% renouvelable et biodégradable. Toile coton bio imperméabilisée. Fixations murales discrètes. Solution respectueuse de l\'environnement.');
        $product4->setPrice(159.99);
        $product4->setImage('https://fastly.picsum.photos/id/865/400/400.jpg?hmac=QIN9FBOMjQhJWA3pfiF-b6yH9S9eK9qnaYGRROTicgk');
        $manager->persist($product4);

        // Fixture 5 - Store banne cèdre canadien
        $product5 = new Product();
        $product5->setQuantity(15);
        $product5->setName('Store Banne Cèdre Rouge Canadien 3.5x3m Avec Coffre');
        $product5->setDescription('Store banne en cèdre rouge du Canada, naturellement résistant aux insectes et à la pourriture. Coffre intégral pour protection optimale de la toile. Mécanisme à chaîne avec démultiplication pour manœuvre facile. Essence noble aux propriétés aromatiques naturelles. Toile rayée marine haute résistance. Finition lasure transparente. Idéal climat océanique.');
        $product5->setPrice(449.99);
        $product5->setImage('https://fastly.picsum.photos/id/793/400/400.jpg?hmac=TPUBRVZK4qqLKA4pIME_5NqO_eGtFNyRjKNCsZckOGE');
        $manager->persist($product5);

        // Fixture 6 - Store banne acacia robuste
        $product6 = new Product();
        $product6->setQuantity(20);
        $product6->setName('Store Banne Acacia Robinia Rétractable 4x2.5m');
        $product6->setDescription('Store banne en acacia robinia européen, bois de classe de durabilité 1-2 naturelle. Structure rétractable manuelle avec système de tension par ressorts. Bois dense et dur, comparable au chêne. Toile micro-perforée pour ventilation optimale. Bras télescopiques en aluminium anodisé. Traitement fongicide préventif. Longévité exceptionnelle sans entretien.');
        $product6->setPrice(329.99);
        $product6->setImage('https://fastly.picsum.photos/id/483/400/400.jpg?hmac=077VPypgihZJeoNF1VsNiNyYNw35X1829XS_4NjyREI');
        $manager->persist($product6);

        // Fixture 7 - Store banne châtaignier français
        $product7 = new Product();
        $product7->setQuantity(14);
        $product7->setName('Store Banne Châtaignier Français Semi-Coffre 3x3m');
        $product7->setDescription('Store banne artisanal en châtaignier français séché naturellement. Semi-coffre en bois massif avec finition cirée à l\'ancienne. Mécanisme traditionnel à treuil avec manivelle amovible. Bois local tanné naturellement, anti-parasitaire. Toile canvas enduite à l\'ancienne 300g/m². Fabrication française dans le respect des traditions. Caractère authentique garanti.');
        $product7->setPrice(389.99);
        $product7->setImage('https://fastly.picsum.photos/id/1021/400/400.jpg?hmac=oIxL-oAN0rExkdnEZRAEjxwA0JggwMnpBqzT8LvM7jc');
        $manager->persist($product7);

        // Fixture 8 - Store banne frêne thermotraité
        $product8 = new Product();
        $product8->setQuantity(22);
        $product8->setName('Store Banne Frêne Thermo-traité Motorisé 4.5x3m');
        $product8->setDescription('Store banne moderne en frêne thermo-traité haute température pour stabilité dimensionnelle optimale. Motorisation 24V avec télécommande radio et détecteur de vent automatique. Traitement écologique par la chaleur sans produits chimiques. Couleur caramel homogène et stable. Toile technique avec marquise frontale dépliable. Système anti-balancement intégré.');
        $product8->setPrice(599.99);
        $product8->setImage('https://fastly.picsum.photos/id/623/400/400.jpg?hmac=cWA8OevZRMt2Z__hKLPY6UMyf_YJfmXYsxwBZ6bf7wA');
        $manager->persist($product8);

        // Fixture 9 - Store banne mélèze alpin
        $product9 = new Product();
        $product9->setQuantity(16);
        $product9->setName('Store Banne Mélèze des Alpes Fixe 3.5x2.5m Montagne');
        $product9->setDescription('Store banne rustique en mélèze des Alpes, résineux noble à croissance lente. Structure fixe renforcée pour résister aux conditions montagnardes. Bois naturellement imputrescible riche en résine. Finition brute brossée pour aspect authentique chalet. Toile imperméable haute résistance aux UV d\'altitude. Fixation sur madriers ou mur pierre. Style montagnard traditionnel.');
        $product9->setPrice(279.99);
        $product9->setImage('https://fastly.picsum.photos/id/931/400/400.jpg?hmac=JN68cEgHw9fJ661Qz4RWgpfDGSSdE_CD8Td1-Og_u2g');
        $manager->persist($product9);

        // Fixture 10 - Store banne douglas français
        $product10 = new Product();
        $product10->setQuantity(19);
        $product10->setName('Store Banne Douglas Français Rétractable 3x2m Terrasse');
        $product10->setDescription('Store banne compact en douglas français classe naturelle 3. Mécanisme rétractable à bras articulés avec tension automatique. Essence résineuse locale à croissance rapide et durable. Traitement par immersion pour protection renforcée. Toile acrylique teintée masse anti-décoloration. Manœuvre par manivelle ergonomique. Parfait pour terrasses urbaines modernes.');
        $product10->setPrice(249.99);
        $product10->setImage('https://fastly.picsum.photos/id/891/400/400.jpg?hmac=32KvM3ItmzbBZVwriqW56CSSlLQsHldqunqFFc6Jh5Q');
        $manager->persist($product10);

        // Fixture 11 - Store banne eucalyptus traité
        $product11 = new Product();
        $product11->setQuantity(13);
        $product11->setName('Store Banne Eucalyptus Traité Coffre Intégral 4x3.5m');
        $product11->setDescription('Store banne contemporain en eucalyptus traité haute pression. Coffre intégral aluminium et bois pour esthétique bi-matière. Croissance rapide et densité élevée pour résistance mécanique. Traitement CTB-P+ contre insectes et champignons. Toile micro-aérée avec tombant frontal zip. Motorisation solaire autonome en option. Design moderne et épuré.');
        $product11->setPrice(519.99);
        $product11->setImage('https://fastly.picsum.photos/id/20/400/400.jpg?hmac=A7YmXnzxu9K5utXf6B0jErut4ApRwfhKMuPwLRWY-Wk');
        $manager->persist($product11);

        // Fixture 12 - Store banne sapin du Nord
        $product12 = new Product();
        $product12->setQuantity(28);
        $product12->setName('Store Banne Sapin du Nord Économique 2.5x2m Balcon');
        $product12->setDescription('Store banne d\'entrée de gamme en sapin du Nord traité. Structure simple et robuste pour balcons et petits espaces. Bois scandinave à croissance lente pour fibres serrées. Traitement préventif contre l\'humidité et les UV. Toile polyester 180g/m² coloris unis ou rayés. Montage fixe avec inclinaison standard. Solution accessible pour tous budgets.');
        $product12->setPrice(149.99);
        $product12->setImage('https://fastly.picsum.photos/id/269/400/400.jpg?hmac=9XGk0SGSb59uWDZZpwbS0M0vv_qyDzmGdne-B3iXw_c');
        $manager->persist($product12);

        // Fixture 13 - Store banne iroko africain
        $product13 = new Product();
        $product13->setQuantity(9);
        $product13->setName('Store Banne Iroko Africain Premium 5x4m Professionnel');
        $product13->setDescription('Store banne professionnel en iroko africain, le "teck d\'Afrique". Structure extra-large pour restaurants et hôtels. Durabilité naturelle classe 1 sans traitement chimique. Couleur dorée stable et grain fin décoratif. Mécanisme motorisé avec automatismes météo complets. Toile technique professionnelle M1 ignifugée. Conception sur-mesure et installation par nos équipes.');
        $product13->setPrice(899.99);
        $product13->setImage('https://fastly.picsum.photos/id/794/400/400.jpg?hmac=Frz9pHb_OcWlV8mqVNkQbpo_2KH3uucPP6nzszeu96s');
        $manager->persist($product13);

        // Fixture 14 - Store banne robinier français
        $product14 = new Product();
        $product14->setQuantity(17);
        $product14->setName('Store Banne Robinier Faux-Acacia Local 3.5x2.8m Écologique');
        $product14->setDescription('Store banne en robinier français (faux-acacia) issu de forêts gérées durablement. Bois local ultra-résistant classe 1-2 naturelle. Structure semi-rétractable avec positionnement multiple. Certification PEFC et origine France garantie. Toile chanvre bio et lin naturel. Assemblage traditionnel par tenons-mortaises. Démarche éco-responsable complète du producteur au consommateur.');
        $product14->setPrice(419.99);
        $product14->setImage('https://fastly.picsum.photos/id/335/400/400.jpg?hmac=W_J8fdPeeKNO-LkQGSRFsAzZmJa2MdoyIM9PRT9560A');
        $manager->persist($product14);

        // Fixture 15 - Store banne composite bois-résine
        $product15 = new Product();
        $product15->setQuantity(21);
        $product15->setName('Store Banne Composite Bois-Résine 4x3m Sans Entretien');
        $product15->setDescription('Store banne innovant en composite bois-résine recyclée. Aspect bois naturel sans les inconvénients d\'entretien. Résistance UV, humidité, insectes et fissuration garantie. Structure rétractable avec motorisation intelligente connectée. Application mobile pour contrôle à distance. Matériau 70% fibres de bois recyclées. Gamme coloris bois authentic. Technologie 21ème siècle.');
        $product15->setPrice(649.99);
        $product15->setImage('https://fastly.picsum.photos/id/162/400/400.jpg?hmac=K4cAzDwKlnW224C4pzJzqoDBYnyFx_r_wycolqysXIQ');
        $manager->persist($product15);

        $manager->flush();

        $manager->flush();
    }
}
