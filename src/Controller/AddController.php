<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class AddController extends AbstractController
{
    #[Route('/add', name: 'app_add')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $username = $session->get('user')['prenom'] ?? null;
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
            'class_items' => $class_items,
            'card_title_colors' => $cardTitleColors,
            'card_detail_item_colors' => $cardDetailItemColors,
            'username' => $username,
            'date' => '28 Novembre 2023',
        ]);
    }
    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request): Response {
        $data = json_decode($request->getContent(), true);
        $nom = $data['nom'];
        $prenom = $data['prenom'];
        $email = $data['email'];
        $password = $data['password'];
        $confirmPassword = $data['confirmPassword'];

        // Validation
        if (!preg_match("/^[a-z]+\.[a-z]+@etudiant\.univ-reims\.fr$/", $email)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid email format']);
        }

        if ($password !== $confirmPassword) {
            return new JsonResponse(['status' => 'error', 'message' => 'Passwords do not match']);
        }

        // Hashage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Enregistrement de l'utilisateur
        $path = $this->getParameter('kernel.project_dir') . '/var/data/users.json';
        $users = json_decode(file_get_contents($path), true);
        $users[] = ['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'password' => $hashedPassword];
        file_put_contents($path, json_encode($users));

        return new JsonResponse(['status' => 'success', 'message' => 'User registered successfully']);
    }

    #[Route('/login', name: 'user_login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        // Logique de connexion...
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];

        // Lire le fichier JSON pour les utilisateurs
        $path = $this->getParameter('kernel.project_dir') . '/var/data/users.json';
        $users = json_decode(file_get_contents($path), true);

        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $session = $request->getSession();
                $session->set('user', $user);
                return new JsonResponse(['status' => 'success', 'message' => 'User logged in successfully']);
            }
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Invalid credentials']);
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
