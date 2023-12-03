<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $username = $session->get('user')['prenom'] ?? null;
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
        // Chemin vers le fichier cards.json
        $path = $this->getParameter('kernel.project_dir') . '/var/data/cards.json';
        $cards = file_exists($path) ? json_decode(file_get_contents($path), true) ?? [] : [];

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




        // Rendu de la vue avec toutes les données
        return $this->render('home/index.html.twig', [
            'username' => $username,
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
            'card_rank_items' => $rankItems,
            'group_items' => $group_items,
            'card_title_colors' => $cardTitleColors,
            'card_detail_item_colors' => $cardDetailItemColors,
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



    #[Route('/logout', name: 'user_logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->remove('user'); // Effacer les informations de l'utilisateur de la session

        // Rediriger vers la page d'accueil ou de connexion
        return $this->redirectToRoute('app_home');
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
