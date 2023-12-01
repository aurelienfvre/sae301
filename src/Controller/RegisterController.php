<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class RegisterController extends AbstractController
{

    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request, SessionInterface $session): Response {
        $session = $request->getSession();
        $username = $session->get('user')['prenom'] ?? null;
        $errors = [];

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $semestre = $request->request->get('semestre', 'Non spécifié');
            $groupe = $request->request->get('groupe', 'Non spécifié');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirmPassword');

            if (!preg_match("/^[a-z]+\.[a-z]+@etudiant\.univ-reims\.fr$/", $email)) {
                $errors['email'] = 'Mail incorrect, doit être sous le format prenom.nom@etudiant.univ-reims.fr';
            }

            $path = $this->getParameter('kernel.project_dir') . '/var/data/users.json';
            $users = file_exists($path) ? json_decode(file_get_contents($path), true) ?? [] : [];

            foreach ($users as $user) {
                if ($user['email'] === $email) {
                    $errors['email'] = 'Un compte avec cet email existe déjà.';
                    break;
                }
            }

            if (strlen($password) < 8) {
                $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errors['password_maj'] = 'Le mot de passe doit contenir au moins une majuscule.';
            }
            if (!preg_match('/[^a-zA-Z\d]/', $password)) {
                $errors['password_special'] = 'Le mot de passe doit contenir au moins un caractère spécial.';
            }
            if ($password !== $confirmPassword) {
                $errors['password_confirm'] = 'Les mots de passe ne correspondent pas.';
            }

            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                if (!file_exists($path)) {
                    file_put_contents($path, json_encode([]));
                }
                $users[] = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'semestre' => $semestre,
                    'groupe' => $groupe
                ];
                file_put_contents($path, json_encode($users));
                $session->set('user', ['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'semestre' => $semestre, 'groupe' => $groupe]);
                $this->addFlash('success', 'Inscription réussie. Vous êtes maintenant connecté.');
                return $this->redirectToRoute('app_home');
            }
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
            ],
            [
                'card_class' => 'blue',
                'card_id' => 2,
                'card_date' => '30 Nov. 2023',
                'card_title' => 'Développement Front 🖥️',
                'card_details' => 'Trois rendus :',
                'card_detail_items' => ['Rendu du code', 'Rendu du rapport', 'Rendu de la présentation'],
                'card_email' => 'hihi@gmail.com',
                'card_rank' => 'S2',
                'card_group' => 'B',
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
                'card_rank' => 'S3',
                'card_group' => 'C',
            ],

        ];
        $cardColors = [
            'yellow' => '#FADB39',
            'blue' => '#007BFF',
        ];
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
            'yellow' => '#',
            'blue' => '#fff',
        ];
        $cardDetailItemColors = [
            'yellow' => '#',
            'blue' => '#fff',
        ];
        $dateItems = $this->getDateItemsFromCards($cards);
        $matiereItems = $this->getMatiereItemsFromCards($cards);

        return $this->render('register/index.html.twig', [
            'username' => $username,
            'controller_name' => 'RegisterController',
            'current_page' => 'register',
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
            'errors' => $errors,
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

