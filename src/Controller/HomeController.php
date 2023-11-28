<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $cards = [
            [
                'card_class' => 'yellow',
                'card_id' => 1,
                'card_date' => '29 Nov. 2023',
                'card_title' => 'Culture artistique 🎨',
                'card_details' => 'Deux rendus :',
                'card_detail_items' => ['Rendu des esquisses', 'Rendu de la charte graphique'],
                'card_email' => 'johndoe@univ-reims.fr',
            ],
            [
                'card_class' => 'blue',
                'card_id' => 2,
                'card_date' => '30 Nov. 2023',
                'card_title' => 'Développement Front 🖥️',
                'card_details' => 'Trois rendus :',
                'card_detail_items' => ['Rendu du code', 'Rendu du rapport', 'Rendu de la présentation'],
                'card_email' => 'hihi@gmail.com',
            ],
            // Ajoutez d'autres cartes ici si nécessaire
            [
            'card_class' => 'blue',
            'card_id' => 3,
            'card_date' => '30 Nov. 2023',
            'card_title' => 'Développement Back 🖥️',
            'card_details' => 'Trois rendus :',
            'card_detail_items' => ['Rendu du code', 'Rendu du rapport', 'Rendu de la présentation'],
            'card_email' => 'caca@hihi.com',
            ],

        ];

        // Tableaux de couleurs pour les cartes
        $cardColors = [
            'yellow' => '#FADB39',
            'blue' => '#007BFF',
            // Ajoutez d'autres couleurs pour d'autres classes ici
        ];

        // Ajoutez d'autres tableaux de couleurs ici
        $cardBorderColors = [
            'yellow' => '#FADB39',
            'blue' => '#007BFF',
        ];

        $cardDateBackgroundColors = [
            'yellow' => '#',
            'blue' => '#...',
        ];

        $cardMatiereBorderColors = [
            'yellow' => '#EAC917',
            'blue' => '#1264DC',
        ];

        $cardDetailsColors = [
            'yellow' => '#...',
            'blue' => 'white',
        ];

        $cardSendColors = [
            'yellow' => '#...',
            'blue' => 'white',
        ];

        $cardEmailAddressColors = [
            'yellow' => '#...',
            'blue' => 'white',
        ];
        $cardTitleColors = [
            'yellow' => '#', // exemple de couleur pour le titre
            'blue' => '#fff',
            // Autres couleurs pour d'autres classes
        ];
        $cardDetailItemColors = [
            'yellow' => '#', // exemple de couleur pour les éléments de détail
            'blue' => '#fff',
            // Autres couleurs pour d'autres classes
        ];

        // Données pour les dropdowns
        $dateItems = $this->getDateItemsFromCards($cards);
        $matiereItems = $this->getMatiereItemsFromCards($cards);

        $class_items = [
            ['value' => 'A', 'label' => 'A'],
            ['value' => 'B', 'label' => 'B'],
            ['value' => 'C', 'label' => 'C'],
            ['value' => 'D', 'label' => 'D'],
            ['value' => 'E', 'label' => 'E'],
            ['value' => 'F', 'label' => 'F'],
            ['value' => 'G', 'label' => 'G'],
            ['value' => 'H', 'label' => 'H'],
        ];

        // Rendu de la vue avec toutes les données
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'current_page' => 'home',
            'cards' => $cards,
            'card_colors' => $cardColors,
            'card_border_colors' => $cardBorderColors,
            'card_date_background_colors' => $cardDateBackgroundColors,
            'card_matiere_border_colors' => $cardMatiereBorderColors,
            'card_details_colors' => $cardDetailsColors,
            'card_send_colors' => $cardSendColors,
            'card_email_address_colors' => $cardEmailAddressColors,
            'date_items' => $dateItems,
            'matiere_items' => $matiereItems,
            'class_items' => $class_items,
            'card_title_colors' => $cardTitleColors,
            'card_detail_item_colors' => $cardDetailItemColors,
            'username' => 'Simon',
            'date' => '28 Novembre 2023',
        ]);
    }
    #[Route('/update-card/{id}', name: 'update_card', methods: ['POST'])]
    public function updateCard(Request $request, int $id): Response
    {
        // Récupérer les données envoyées
        $data = json_decode($request->getContent(), true);

        // Trouver la carte correspondante et la mettre à jour
        foreach ($this->cards as $key => $card) {
            if ($card['card_id'] == $id) {
                $this->cards[$key] = array_merge($card, $data);
                break;
            }
        }

        // Ici, vous devez sauvegarder les modifications dans votre base de données ou votre système de stockage

        return new JsonResponse(['status' => 'success', 'message' => 'Card updated successfully']);
    }


    private function getDateItemsFromCards($cards): array
    {
        $dates = array_map(function ($card) {
            return $card['card_date'];
        }, $cards);
        $dates = array_unique($dates);
        sort($dates);

        return array_map(function ($date) {
            return ['value' => strtolower($date), 'label' => $date];
        }, $dates);
    }

    private function getMatiereItemsFromCards($cards): array
    {
        $matieres = array_map(function ($card) {
            return $card['card_title'];
        }, $cards);
        $matieres = array_unique($matieres);
        sort($matieres);

        return array_map(function ($matiere) {
            return ['value' => strtolower($matiere), 'label' => $matiere];
        }, $matieres);
    }

}
