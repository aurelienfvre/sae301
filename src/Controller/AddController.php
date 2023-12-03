<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class AddController extends AbstractController
{
    #[Route('/add', name: 'app_add', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $username = $session->get('user')['prenom'] ?? null;
        if ($request->isMethod('POST')) {
            // Récupération des données du formulaire
            $cardDate = $request->request->get('cardDate');
            $semestre = $request->request->get('semestre');
            $groupe = $request->request->get('groupe');
            $cardTitle = $request->request->get('cardTitle');
            $cardDetails = $request->request->get('cardDetails');
            $cardDetailItemsInput = $request->request->get('cardDetailItems');
            $cardDetailItems = explode(',', $cardDetailItemsInput);
            $cardEmail = $request->request->get('cardEmail');

            // Séparation des éléments par virgule

            $path = $this->getParameter('kernel.project_dir') . '/var/data/cards.json';
            $cards = file_exists($path) ? json_decode(file_get_contents($path), true) ?? [] : [];

            $newCard = [
                'card_class' => 'yellow', // ou autre selon la logique
                'card_id' => count($cards) + 1,
                'card_date' => $cardDate,
                'card_title' => $cardTitle,
                'card_details' => $cardDetails,
                'card_detail_items' => array_map('trim', $cardDetailItems),
                'card_email' => $cardEmail, // À définir, peut-être à partir d'un autre champ du formulaire
                'card_rank' => $semestre,
                'card_group' => $groupe,

            ];
            $cards[] = $newCard;

            file_put_contents($path, json_encode($cards));

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }
        $group_items = [
            ['value' => 'A', 'label' => 'A'],
            ['value' => 'B', 'label' => 'B'],
            ['value' => 'C', 'label' => 'C'],
            ['value' => 'D', 'label' => 'D'],
            ['value' => 'E', 'label' => 'E'],
            ['value' => 'F', 'label' => 'F'],
            ['value' => 'G', 'label' => 'G'],
            ['value' => 'H', 'label' => 'H'],

        ];
        $rankItems = [
            ['value' => 'S1', 'label' => 'S1'],
            ['value' => 'S2', 'label' => 'S2'],
            ['value' => 'S3', 'label' => 'S3'],
            ['value' => 'S4', 'label' => 'S4'],
            ['value' => 'S5', 'label' => 'S5'],
            ['value' => 'S6', 'label' => 'S6'],
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

        $cards = [
            [
                'card_class' => 'yellow',
                'card_id' => 1,
                'card_date' => '29 Nov. 2023',
                'card_title' => 'Culture artistique 🎨',
                'card_details' => 'Deux rendus :',
                'card_detail_items' => ['Rendu des esquisses', 'Rendu de la charte graphique'],
                'card_email' => 'johndoe@univ-reims.fr',
                'card_rank' => 'S1',
                'card_group' => 'B',
            ]
        ];
        // Données pour les dropdowns
        $dateItems = $this->getDateItemsFromCards($cards);
        $matiereItems = $this->getMatiereItemsFromCards($cards);


        return $this->render('add/index.html.twig', [
            'controller_name' => 'AddController',
            'current_page' => 'add',
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
            'card_rank_items' => $rankItems,
            'group_items' => $group_items,
            'card_title_colors' => $cardTitleColors,
            'card_detail_item_colors' => $cardDetailItemColors,
            'username' => $username,
            'date' => '28 Novembre 2023',
        ]);
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
