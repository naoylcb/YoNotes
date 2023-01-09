<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Function that manages the display of user's notes
     *
     * @param NoteRepository $noteRepository
     * @return Response
     */
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(NoteRepository $noteRepository): Response
    {
        $notes = $noteRepository->findBy(
            ['author' => $this->getUser()]
        );

        return $this->render('home/index.html.twig', [
            'notes' => $notes
        ]);
    }
}
