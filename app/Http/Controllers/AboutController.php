<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Affiche la page À propos
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Données pour la page À propos
        $aboutData = [
            'title' => 'À Propos du Tourisme au Bénin',
            'description' => 'Découvrez l\'histoire et la vision de notre plateforme dédiée au tourisme béninois.',
            'image' => 'images/4.jpeg',

            'mission' => 'Notre mission est de promouvoir les merveilles culturelles et naturelles du Bénin tout en soutenant les communautés locales.',

            'team' => [
                [
                    'name' => 'LOKO Pétrarque',
                    'position' => 'Fondateur & CEO',
                    'bio' => 'Passionné par le patrimoine béninois depuis son enfance, LOKO a créé cette plateforme pour partager les richesses de son pays.',
                    'image' => 'images/5.jpeg'
                ],
                [
                    'name' => 'KASSOUIN Hugues',
                    'position' => 'Directeur Adjoint',
                    'bio' => 'Experte en tourisme culturel, Aïcha conçoit des circuits uniques qui mettent en valeur l\'authenticité béninoise.',
                    'image' => 'images/6.png'
                ],
                [
                    'name' => 'KIM André',
                    'position' => 'Responsable Partenariats',
                    'bio' => 'Pascal travaille avec les communautés locales pour créer des opportunités économiques durables à travers le tourisme.',
                    'image' => 'images/8.jpeg'
                ]
            ],
            'statistics' => [
                ['value' => '15+', 'label' => 'Sites touristiques partenaires'],
                ['value' => '1200+', 'label' => 'Voyageurs satisfaits'],
                ['value' => '25+', 'label' => 'Communautés locales soutenues'],
                ['value' => '8+', 'label' => 'Années d\'expérience']
            ],
            'values' => [
                [
                    'title' => 'Authenticité',
                    'description' => 'Nous proposons des expériences véritables qui reflètent l\'âme du Bénin.'
                ],
                [
                    'title' => 'Durabilité',
                    'description' => 'Nous nous engageons à préserver l\'environnement et les traditions locales.'
                ],
                [
                    'title' => 'Communauté',
                    'description' => 'Nous travaillons main dans la main avec les communautés locales pour un tourisme équitable.'
                ],
                [
                    'title' => 'Excellence',
                    'description' => 'Nous visons l\'excellence dans chaque aspect de nos services.'
                ]
            ],
            'testimonials' => [
                [
                    'name' => 'Castoline',
                    'country' => 'Tanzanie',
                    'text' => 'Une expérience inoubliable à la découverte des palais royaux d\'Abomey. L\'organisation était parfaite !',
                    'image' => 'images/1.png', // Chemin relatif depuis public/
                ],




                [
                    'name' => 'Lopèse Ornella',
                    'country' => 'Côte d\'Ivoire',
                    'text' => 'Le village lacustre de Ganvié m\'a totalement fasciné. Merci pour cette immersion authentique.',
                    'image' => 'images/1.png', // Chemin relatif depuis public/
                ]
            ]
        ];

        // Renvoyer la vue avec les données
        return view('about', compact('aboutData'));
    }
}