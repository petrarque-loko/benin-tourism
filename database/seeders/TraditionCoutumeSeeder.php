<?php

namespace Database\Seeders;

use App\Models\TraditionCoutume;
use Illuminate\Database\Seeder;

class TraditionCoutumeSeeder extends Seeder
{
    public function run()
    {
        $traditions = [
            // Catégorie 1 : Rites et Cérémonies Traditionnelles
            [
                'titre' => 'Cérémonie Vodoun',
                'resume' => 'La cérémonie Vodoun est un rituel sacré du Bénin, au cœur des traditions spirituelles africaines. Elle honore les divinités et esprits vodoun à travers des chants, des danses mystiques et des offrandes. Animée par des prêtres initiés, cette célébration marque les liens profonds entre le visible et l’invisible, attirant fidèles, curieux et touristes en quête d’authenticité culturelle.',
                'contenu' => '<p>**Le Vodoun**, bien plus qu’une religion, est une tradition vivante qui façonne l’identité culturelle du Bénin. La **cérémonie Vodoun**, ancrée dans le respect des ancêtres et des forces spirituelles, est un moment intense de communion entre les hommes et les divinités.</p> <p>Ces rituels se déroulent dans des temples sacrés ou en plein air, autour d’un autel dédié aux esprits vodoun. Les prêtres vodoun, appelés **"vodounon"** ou **"vodounsi"** selon leur rang, guident les festivités avec des prières, des incantations et des danses rythmées par le son envoûtant des tambours rituels. Ces percussions, souvent accompagnées de chants en langue locale, servent à invoquer les divinités et à créer une connexion spirituelle.</p> <p>Les participants, vêtus de tenues traditionnelles aux couleurs éclatantes, entrent en transe sous l’effet de la musique et des invocations. Cette transe, considérée comme une manifestation de la présence divine, est un moment de révélation où l’initié reçoit des messages des esprits.</p> <p>Durant ces cérémonies, des **offrandes** (alcool, colas, ignames, huile de palme, poulets ou chèvres) sont faites aux divinités pour obtenir leur bénédiction, protection ou guérison. Certaines cérémonies peuvent durer plusieurs jours et rassembler toute une communauté, renforçant ainsi les liens sociaux et culturels.</p> <p>Le **10 janvier**, le Bénin célèbre officiellement la **Journée du Vodoun**, un événement majeur attirant des milliers de visiteurs du monde entier. Cette célébration met en lumière la richesse de cette tradition, mêlant rituels, chants, danses et spectacles de masques sacrés comme les Egungun.</p> <p>Aujourd’hui, le Vodoun continue d’influencer l’art, la musique et la spiritualité au Bénin. Il est un **patrimoine immatériel précieux**, inscrit dans la mémoire collective du peuple béninois et fascine de plus en plus les touristes en quête d’expériences culturelles authentiques.</p>',
                'categorie_id' => 1,
                'medias' => [
                    ['url' => '/images/traditions/vodoun_ceremonie_1.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/vodoun_ceremonie_2.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/vodoun_ceremonie_3.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/vodoun_ceremonie_4.jpg', 'type' => 'image'],
                    ['url' => '/videos/traditions/vodoun_ceremonie.mp4', 'type' => 'video'],
                ],
            ],
            [
                'titre' => 'Rite de Passage Adulte',
                'resume' => 'Le rite de passage à l’âge adulte est une cérémonie traditionnelle pratiquée par plusieurs ethnies béninoises, notamment les Fon et les Yoruba. Il marque la transition des jeunes vers la maturité à travers des épreuves initiatiques mêlant enseignements culturels, défis physiques et rituels sacrés. Ce passage symbolique est un moment fort de la vie communautaire, garantissant la transmission des valeurs ancestrales.',
                'contenu' => '<p>Dans de nombreuses cultures africaines, l’entrée dans l’âge adulte est un événement majeur qui ne se fait pas automatiquement avec l’âge, mais à travers un **rite de passage** soigneusement orchestré. Au Bénin, chez les **Fon** et les **Yoruba**, ces cérémonies sont essentielles pour préparer les jeunes à leurs responsabilités futures en tant qu’adultes intégrés à la société.</p> <p>Les rites varient d’une ethnie à l’autre, mais partagent des éléments communs : enseignements des aînés, épreuves physiques et spirituelles, et célébrations collectives.</p>
                            Les différentes étapes du rite de passage
                            1. L’isolement et l’apprentissage des traditions

                            <p>Avant la cérémonie principale, les jeunes initiés passent souvent une période d’isolement sous la supervision des sages et des chefs spirituels. Durant cette retraite, ils reçoivent des **enseignements sur les valeurs fondamentales**, les responsabilités sociales, la spiritualité et l’histoire de leur peuple.</p>
                            2. Les épreuves initiatiques

                            <p>Les initiés doivent traverser plusieurs défis symboliques destinés à tester leur courage, leur endurance et leur sagesse. Ces épreuves peuvent inclure :</p> <ul> <li>Des **épreuves physiques**, comme des courses d’endurance ou des tests de force.</li> <li>Des **épreuves spirituelles**, incluant des rituels de purification et des invocations aux ancêtres.</li> <li>Des **rites de marquage corporel**, comme des scarifications ou tatouages (dans certaines traditions), servant de **symbole d’appartenance** et de maturité.</li> </ul>
                            3. La cérémonie de reconnaissance

                            <p>Après avoir réussi les épreuves, les initiés sont officiellement reconnus comme adultes. Une grande fête est organisée en leur honneur avec des danses, des chants et des festins. Ils reçoivent souvent un **nouveau nom** qui marque leur changement de statut social.</p>
                            L’importance culturelle du rite de passage
                            <p>Ce rite joue un rôle clé dans la **transmission des traditions**, en assurant que les nouvelles générations comprennent leur **histoire, leurs responsabilités et leurs devoirs** envers leur communauté. Il renforce également la cohésion sociale, en consolidant les liens entre les jeunes et les anciens.</p> <p>Aujourd’hui, bien que ces rites soient parfois influencés par la modernité, ils restent **une étape incontournable dans l’identité culturelle béninoise** et continuent d’attirer l’attention des chercheurs, des anthropologues et des touristes curieux de découvrir ces traditions fascinantes.</p>',
                'categorie_id' => 1,
                'medias' => [
                    ['url' => '/images/traditions/rite_passage_1.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/rite_passage_2.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/rite_passage_3.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/rite_passage_4.jpg', 'type' => 'image'],
                    ['url' => '/videos/traditions/rite_passage.mp4', 'type' => 'video'],
                ],
            ],

            // Catégorie 2 : Danses Traditionnelles
            [
                'titre' => 'Danse Agbadja',
                'resume' => 'L’Agbadja est une danse traditionnelle du peuple Fon, originaire du sud du Bénin. Elle est exécutée lors de cérémonies importantes, notamment les funérailles, les mariages et les festivals culturels. Son rythme dynamique et ses mouvements expressifs en font une danse de cohésion sociale, véhiculant à la fois des messages spirituels et des scènes de la vie quotidienne. Aujourd’hui, l’Agbadja s’est modernisée tout en restant un symbole fort de l’identité culturelle béninoise, attirant aussi bien les locaux que les amateurs de danse traditionnelle à l’international.',
                'contenu' => '<p>Parmi les danses traditionnelles les plus emblématiques du Bénin, l’**Agbadja** occupe une place particulière. Elle est profondément ancrée dans la culture du peuple **Fon** et s’exécute lors des **grands événements sociaux et rituels**. </p> <p>Son **rythme percutant**, dicté par un ensemble de tambours spécifiques, et ses **mouvements fluides et cadencés** en font une danse à la fois expressive et énergique. À travers l’Agbadja, les danseurs transmettent **des émotions, des histoires et des croyances ancestrales**.</p>
                            Origine et signification de l’Agbadja
                            <p>L’Agbadja trouve ses racines dans les anciennes traditions du sud du Bénin. À l’origine, elle était principalement associée aux **funérailles**, où elle permettait d’honorer les défunts et d’accompagner leur passage vers l’au-delà.</p> <p>Avec le temps, la danse s’est élargie à d’autres occasions festives comme les **mariages, les baptêmes et les festivals traditionnels**, devenant un **symbole d’unité et d’expression collective**.</p>
                            Les éléments clés de la danse Agbadja
                            1. Les instruments de musique

                            <p>Le cœur de l’Agbadja repose sur ses instruments de percussion. Parmi les plus emblématiques, on retrouve :</p> <ul> <li>Le **tambour Agbadja** (ou "Atumpan"), qui donne le tempo de la danse.</li> <li>Les **cloches métalliques (Gankogui)**, marquant le rythme principal.</li> <li>Les **hochets et autres percussions**, qui ajoutent des variations sonores.</li> </ul>
                            2. Les mouvements et l’expression corporelle

                            <p>Les danseurs évoluent en **cercles ou en lignes**, en synchronisation avec les percussions. Chaque mouvement a une signification particulière :</p> <ul> <li>Des gestes fluides imitant des scènes de la vie quotidienne.</li> <li>Des pas cadencés symbolisant la **force et l’unité** du groupe.</li> <li>Des expressions faciales et des gestes des mains illustrant des **émotions profondes**.</li> </ul>
                            3. Les tenues traditionnelles

                            <p>Les danseurs portent généralement des **pagne colorés** et des **accessoires traditionnels**, reflétant l’identité culturelle du peuple Fon. Ces costumes varient en fonction des occasions (deuil, mariage, festival).</p>
                            L’Agbadja aujourd’hui : Une danse en évolution
                            <p>Bien que profondément ancrée dans la tradition, l’Agbadja a su s’adapter aux évolutions modernes. Elle est aujourd’hui enseignée dans des écoles de danse, présentée lors de festivals internationaux et parfois fusionnée avec des styles contemporains.</p> <p>Elle reste un **élément incontournable du patrimoine béninois**, fascinant aussi bien les locaux que les passionnés de culture africaine à travers le monde.</p>',
                'categorie_id' => 2,
                'medias' => [
                    ['url' => '/images/traditions/agbadja_1.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/agbadja_2.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/agbadja_3.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/agbadja_4.jpg', 'type' => 'image'],
                    ['url' => '/videos/traditions/agbadja.mp4', 'type' => 'video'],
                ],
            ],
            [
                'titre' => 'Danse Zinli',
                'resume' => 'La Zinli est une danse traditionnelle béninoise à caractère guerrier, exécutée par les hommes lors des festivals et cérémonies rituelles. Elle met en avant le courage et la bravoure, à travers des mouvements synchronisés et vigoureux. Armés de lances ou de bâtons, les danseurs simulent des combats, accompagnés par le rythme puissant des tambours et des chants évoquant des exploits héroïques. Plus qu\'une simple performance artistique, la Zinli est un symbole de résilience et d\'héritage martial, transmettant aux jeunes générations des valeurs essentielles à la culture béninoise.',
                'contenu' => 'Origine et signification de la danse Zinli
                            <p>La danse **Zinli** trouve ses racines dans les traditions guerrières du Bénin. Autrefois, elle servait à **préparer les combattants avant les batailles**, renforçant leur moral et leur cohésion. Aujourd’hui, elle est principalement pratiquée lors des **festivals culturels, cérémonies commémoratives et événements communautaires**.</p> <p>Elle incarne la **force, l’honneur et la persévérance**, des valeurs fondamentales dans la société traditionnelle béninoise. À travers elle, les anciens transmettent des **enseignements et légendes** aux nouvelles générations, perpétuant ainsi l’héritage de leurs ancêtres.</p>
                            Les éléments clés de la danse Zinli
                            1. Les instruments de musique

                            <p>Le rythme intense de la Zinli est donné par un ensemble de percussions :</p> <ul> <li>Les **tambours Zinli**, jouant des rythmes puissants et cadencés.</li> <li>Les **cloches métalliques (Gankogui)**, qui marquent le tempo.</li> <li>Les **chants rituels**, racontant des exploits héroïques et des légendes locales.</li> </ul>
                            2. Les mouvements et la mise en scène

                            <p>Chaque performance de la Zinli est une véritable **mise en scène guerrière** :</p> <ul> <li>Les danseurs exécutent des **mouvements vigoureux**, imitant des combats.</li> <li>Ils utilisent des **bâtons ou des lances** pour symboliser la lutte.</li> <li>Leur **coordination parfaite** reflète l\'entraînement et la discipline des anciens guerriers.</li> </ul>
                            3. Les tenues traditionnelles

                            <p>Les danseurs portent des **costumes ornés de motifs guerriers**, parfois complétés par des accessoires comme des **plumes, des pagnes colorés ou des peintures corporelles** pour accentuer l’aspect martial de la danse.</p>
                            La danse Zinli aujourd’hui : Un patrimoine vivant
                            <p>Bien que ses origines soient militaires, la **Zinli** a évolué pour devenir un **spectacle culturel fascinant**, apprécié lors des festivals et événements officiels.</p> <p>Elle demeure un **élément incontournable du patrimoine béninois**, attirant aussi bien les jeunes générations que les amateurs de traditions africaines.</p>',
                'categorie_id' => 2,
                'medias' => [
                    ['url' => '/images/traditions/zinli_1.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/zinli_2.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/zinli_3.jpg', 'type' => 'image'],
                    ['url' => '/images/traditions/zinli_4.jpg', 'type' => 'image'],
                    ['url' => '/videos/traditions/zinli.mp4', 'type' => 'video'],
                ],
            ]
        ];

        //     // Catégorie 3 : Musiques Traditionnelles
        //     [
        //         'titre' => 'Musique Sato',
        //         'resume' => 'Un genre musical vodoun joué avec des tambours sacrés.',
        //         'contenu' => '<p>La musique Sato, liée aux pratiques vodoun, utilise des tambours sacrés appelés "sato". Ces instruments divins produisent des rythmes complexes pour invoquer les esprits lors des cérémonies.</p>
        //                      <p>Chaque rythme a une signification spécifique, associée à une divinité, et facilite la transe des participants. Les tambourineurs jouent un rôle central dans ces rituels spirituels.</p>
        //                      <p>Transmise par des maîtres aux apprenants, la musique Sato reste essentielle à la culture béninoise, liant musique et spiritualité.</p>',
        //         'categorie_id' => 3,
        //         'medias' => [
        //             ['url' => '/images/traditions/sato_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/sato_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/sato_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/sato_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/sato.mp4', 'type' => 'video'],
        //         ],
        //     ],
        //     [
        //         'titre' => 'Chant des Griots',
        //         'resume' => 'Les griots préservent l’histoire par leurs chants traditionnels.',
        //         'contenu' => '<p>Les griots, conteurs et musiciens, sont des gardiens de l’histoire béninoise. Accompagnés de la kora ou du balafon, leurs chants relatent exploits ancestraux, légendes et événements marquants.</p>
        //                      <p>Respectés pour leur sagesse, ils unissent les communautés par leurs performances lors de mariages, funérailles ou célébrations. Leur art est une mémoire vivante.</p>
        //                      <p>Transmis de père en fils, le chant des griots reste un vecteur clé de la tradition orale et de l’éducation culturelle au Bénin.</p>',
        //         'categorie_id' => 3,
        //         'medias' => [
        //             ['url' => '/images/traditions/griots_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/griots_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/griots_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/griots_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/griots.mp4', 'type' => 'video'],
        //         ],
        //     ],

        //     // Catégorie 4 : Festivals et Célébrations
        //     [
        //         'titre' => 'Festival des Masques',
        //         'resume' => 'Un festival annuel mettant en avant les masques traditionnels.',
        //         'contenu' => '<p>Le Festival des Masques réunit des troupes du Bénin pour des performances de masques représentant divinités ou ancêtres. Ces danses et rituels racontent des histoires mythiques ou historiques.</p>
        //                      <p>Les artisans y vendent des masques sculptés, tandis que les spectacles attirent locaux et touristes. Le festival est une vitrine de la richesse culturelle béninoise.</p>
        //                      <p>Il favorise la préservation des traditions à travers des foires, conférences et ateliers, renforçant l’identité nationale.</p>',
        //         'categorie_id' => 4,
        //         'medias' => [
        //             ['url' => '/images/traditions/masques_festival_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/masques_festival_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/masques_festival_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/masques_festival_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/masques_festival.mp4', 'type' => 'video'],
        //         ],
        //     ],
        //     [
        //         'titre' => 'Fête du Vodoun',
        //         'resume' => 'Une célébration nationale du patrimoine vodoun.',
        //         'contenu' => '<p>La Fête du Vodoun, le 10 janvier, honore la religion traditionnelle du Bénin. Elle inclut cérémonies, danses et processions dans tout le pays, attirant adeptes et curieux.</p>
        //                      <p>Les temples accueillent des rituels, tandis que des défilés publics et spectacles mettent en lumière la diversité du Vodoun. C’est une occasion d’éducation et de découverte.</p>
        //                      <p>Symbolisant la fierté nationale, cette fête renforce la cohésion sociale et célèbre l’héritage culturel béninois.</p>',
        //         'categorie_id' => 4,
        //         'medias' => [
        //             ['url' => '/images/traditions/fete_vodoun_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/fete_vodoun_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/fete_vodoun_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/fete_vodoun_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/fete_vodoun.mp4', 'type' => 'video'],
        //         ],
        //     ],

        //     // Catégorie 5 : Coutumes Alimentaires
        //     [
        //         'titre' => 'Préparation du Tchoukoutou',
        //         'resume' => 'Une boisson traditionnelle à base de mil fermenté.',
        //         'contenu' => '<p>Le Tchoukoutou, boisson alcoolisée du Bénin, est préparé à partir de mil fermenté. Servi lors de mariages ou rituels, il symbolise l’hospitalité et la convivialité.</p>
        //                      <p>Sa fabrication, un savoir ancestral, implique de tremper, sécher et fermenter le mil sur plusieurs jours. Le résultat est une boisson acide et rafraîchissante.</p>
        //                      <p>Utilisé pour sceller des alliances ou honorer les ancêtres, le Tchoukoutou est un élément clé des traditions sociales et spirituelles.</p>',
        //         'categorie_id' => 5,
        //         'medias' => [
        //             ['url' => '/images/traditions/tchoukoutou_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/tchoukoutou_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/tchoukoutou_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/tchoukoutou_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/tchoukoutou.mp4', 'type' => 'video'],
        //         ],
        //     ],
        //     [
        //         'titre' => 'Cuisine du Akpan',
        //         'resume' => 'Un plat de maïs fermenté pour les grandes occasions.',
        //         'contenu' => '<p>L’Akpan, plat traditionnel béninois, est préparé à partir de maïs fermenté et cuit dans des feuilles de bananier. Il est fréquent lors de mariages ou funérailles.</p>
        //                      <p>Sa texture moelleuse et son goût acide en font un mets apprécié, souvent accompagné de sauces ou poissons fumés. Il incarne le partage et la générosité.</p>
        //                      <p>Chaque région a sa variante, et sa préparation, transmise en famille, reflète un savoir-faire culinaire précieux.</p>',
        //         'categorie_id' => 5,
        //         'medias' => [
        //             ['url' => '/images/traditions/akpan_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/akpan_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/akpan_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/akpan_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/akpan.mp4', 'type' => 'video'],
        //         ],
        //     ],

        //     // Catégorie 6 : Artisanat et Savoir-Faire
        //     [
        //         'titre' => 'Sculpture sur Bois',
        //         'resume' => 'Un art ancestral pour créer masques et objets rituels.',
        //         'contenu' => '<p>La sculpture sur bois au Bénin produit des masques, statuettes et objets sacrés ornés de motifs symboliques. Cet art ancien est à la fois utilitaire et spirituel.</p>
        //                      <p>Les artisans, utilisant des outils traditionnels, transmettent leurs techniques de génération en génération. Chaque œuvre porte une histoire ou une signification divine.</p>
        //                      <p>Prisé internationalement, cet artisanat contribue à la renommée culturelle du Bénin et à la préservation de son patrimoine.</p>',
        //         'categorie_id' => 6,
        //         'medias' => [
        //             ['url' => '/images/traditions/sculpture_bois_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/sculpture_bois_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/sculpture_bois_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/sculpture_bois_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/sculpture_bois.mp4', 'type' => 'video'],
        //         ],
        //     ],
        //     [
        //         'titre' => 'Tissage de Pagnes',
        //         'resume' => 'Un savoir-faire artisanal pour des textiles symboliques.',
        //         'contenu' => '<p>Le tissage de pagnes, souvent pratiqué par les femmes, utilise des métiers traditionnels pour créer des textiles colorés aux motifs complexes. Chaque dessin porte une signification.</p>
        //                      <p>Portés lors de cérémonies ou offerts en cadeau, ces pagnes reflètent l’identité et le statut social. Leur fabrication demande patience et habileté.</p>
        //                      <p>Transmis de mère en fille, cet art soutient l’autonomisation des femmes et la préservation culturelle.</p>',
        //         'categorie_id' => 6,
        //         'medias' => [
        //             ['url' => '/images/traditions/tissage_pagnes_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/tissage_pagnes_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/tissage_pagnes_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/tissage_pagnes_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/tissage_pagnes.mp4', 'type' => 'video'],
        //         ],
        //     ],

        //     // Catégorie 7 : Habillement et Mode Traditionnelle
        //     [
        //         'titre' => 'Tenue Royale Fon',
        //         'resume' => 'Vêtements royaux symbolisant pouvoir et prestige.',
        //         'contenu' => '<p>Les tenues royales Fon, portées par les rois lors de cérémonies, incluent pagnes décorés, tuniques brodées et coiffes ornées. Elles symbolisent l’autorité et la tradition.</p>
        //                      <p>Chaque élément, comme les perles ou plumes, porte une signification liée à la sagesse ou la protection. Leur confection mobilise des artisans spécialisés.</p>
        //                      <p>Transmises comme héritages, ces tenues préservent l’histoire et l’identité du peuple Fon.</p>',
        //         'categorie_id' => 7,
        //         'medias' => [
        //             ['url' => '/images/traditions/tenue_royale_fon_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/tenue_royale_fon_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/tenue_royale_fon_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/tenue_royale_fon_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/tenue_royale_fon.mp4', 'type' => 'video'],
        //         ],
        //     ],
        //     [
        //         'titre' => 'Boubou Traditionnel',
        //         'resume' => 'Une tenue ample et élégante pour les occasions spéciales.',
        //         'contenu' => '<p>Le boubou, robe traditionnelle en wax ou bazin, est porté par hommes et femmes lors de fêtes ou cérémonies. Il allie confort et élégance avec ses broderies colorées.</p>
        //                      <p>Les femmes l’associent à un foulard, les hommes à une coiffe. Confectionné sur mesure, il reflète l’identité culturelle de son porteur.</p>
        //                      <p>Symbole de fierté, le boubou perpétue un savoir-faire artisanal et reste un incontournable de la mode béninoise.</p>',
        //         'categorie_id' => 7,
        //         'medias' => [
        //             ['url' => '/images/traditions/boubou_1.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/boubou_2.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/boubou_3.jpg', 'type' => 'image'],
        //             ['url' => '/images/traditions/boubou_4.jpg', 'type' => 'image'],
        //             ['url' => '/videos/traditions/boubou.mp4', 'type' => 'video'],
        //         ],
        //     ],
        // ];

        foreach ($traditions as $data) {
            $tradition = TraditionCoutume::create([
                'titre' => $data['titre'],
                'resume' => $data['resume'],
                'contenu' => $data['contenu'],
                'categorie_id' => $data['categorie_id'],
            ]);

            foreach ($data['medias'] as $media) {
                $tradition->medias()->create([
                    'url' => $media['url'],
                    'type' => $media['type'],
                ]);
            }
        }
    }
}