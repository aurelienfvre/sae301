<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SettingsController extends AbstractController
{

    #[Route('/settings', name: 'settings', methods: ['GET', 'POST'])]
    public function index(Request $request, SessionInterface $session): Response {
        $userData = $session->get('user');
        $errors = [];

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom', $userData['nom'] ?? null);
            $prenom = $request->request->get('prenom', $userData['prenom'] ?? null);
            $semestre = $request->request->get('semestre', $userData['semestre'] ?? null);
            $groupe = $request->request->get('groupe', $userData['groupe'] ?? null);
            $photo = $request->files->get('photo');
            $newFilename = $userData['photo'] ?? null;
            $emailPart1 = $request->request->get('emailPart1', '');
            $emailPart2 = $request->request->get('emailPart2', '');
            $email = $emailPart1 . '.' . $emailPart2 . '@etudiant.univ-reims.fr';

            if ($photo) {
                $newFilename = uniqid().'.'.$photo->guessExtension();
                try {
                    $photo->move($this->getParameter('photos_directory'), $newFilename);
                } catch (FileException $e) {
                    $errors['photo'] = 'Erreur lors du téléchargement de la photo: ' . $e->getMessage();
                }
            }

            $path = $this->getParameter('kernel.project_dir') . '/var/data/users.json';
            $users = json_decode(file_get_contents($path), true);

            foreach ($users as $key => &$storedUser) {
                if (isset($storedUser['id']) && isset($userData['id']) && $storedUser['id'] === $userData['id']) {
                    $storedUser = array_merge($storedUser, [
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'email' => $email,
                        'semestre' => $semestre,
                        'groupe' => $groupe,
                        'photo' => $newFilename
                    ]);

                    $userData = array_merge($userData, [
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'email' => $email,
                        'semestre' => $semestre,
                        'groupe' => $groupe,
                        'photo' => $newFilename
                    ]);
                    break;
                }
            }

            file_put_contents($path, json_encode($users));
            $session->set('user', $userData);

            if (empty($errors)) {
                $this->addFlash('success', 'Profil mis à jour avec succès.');
                return $this->redirectToRoute('settings');
            }
        }


        // Préparation des données pour le formulaire
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

        return $this->render('settings/index.html.twig', [
            'controller_name' => 'SettingsController',
            'userData' => $userData,
            'errors' => $errors,
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
            'current_page' => 'settings',

            // ...autres variables nécessaires pour le formulaire...
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
