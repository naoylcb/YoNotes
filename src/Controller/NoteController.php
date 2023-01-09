<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    /**
     * Function that manages the addition of a note
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/note/new', name: 'app_add_note', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $note = new Note();
        $note->setAuthor($this->getUser());

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($note);
            $manager->flush($note);

            $this->addFlash('info', 'La note a bien été créé !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('note/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Function that manages the edition of a note
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param NoteRepository $noteRepository 
     * @param $id
     * @return Response
     */
    #[Route('/note/edit/{id}', name: 'app_edit_note', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $manager, NoteRepository $noteRepository, $id): Response
    {
        $note = $noteRepository->find($id);

        if($note) {
            $form = $this->createForm(NoteType::class, $note);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $manager->persist($note);
                $manager->flush($note);

                $this->addFlash('info', 'La note a bien été modifié !');

                return $this->redirectToRoute('app_home');
            }

            return $this->render('note/index.html.twig', [
                'form' => $form->createView(),
                'note' => $note,
            ]);
        } else {
            return $this->redirectToRoute('app_home');
        }
    }

    /**
     * Function that manages the deletion of a note
     *
     * @param EntityManagerInterface $manager
     * @param NoteRepository $noteRepository 
     * @param $id
     * @return Response
     */
    #[Route('/note/delete/{id}', name: 'app_delete_note', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, NoteRepository $noteRepository, $id): Response
    {
        $note = $noteRepository->find($id);

        if($note) {
            $manager->remove($note);
            $manager->flush($note);

            $this->addFlash('info', 'La note a bien été supprimé !');
        }

        return $this->redirectToRoute('app_home');
    }
}
