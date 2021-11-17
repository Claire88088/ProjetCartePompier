<?php

namespace App\Controller;

use App\Form\CalqueType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Calque;

class MapController extends AbstractController
{
    // Affichage de la carte
    /**
     * @Route("/map", name="map")
     */
    public function indexAction(): Response
    {
        return $this->render('map/index.html.twig');
    }

    // Envoi des données nécessaires à JS
    public function envoiDonneesJSAction(EntityManagerInterface $em): Response
    {
        $calques = $em->getRepository('App:Calque')->findAll();

        return $this->render('envoi-donnees-JS.html.twig', [
            'calques' => $calques,
        ]);
    }

    // Affichage de la liste des calques créés pour la modification/suppression
    /**
     * @Route("/calques-list", name="calques_list")
     */
    public function calquesListAction(EntityManagerInterface $em): Response
    {
        $calques = $em->getRepository('App:Calque')->findAll();

        return $this->render('/map/calques-list.html.twig', [
            'calques' => $calques,
        ]);
    }

    // Ajout d'un nouveau calque
    /**
     * @Route("/map/add-calque", name="add_calque")
     */
    public function addCalqueAction(EntityManagerInterface $em, Request $request): Response
    {
        $calque = new Calque();
        $form = $this->createForm(CalqueType::class, $calque);
        $form->add('Ajouter', SubmitType::class, ['label' => 'Ajouter un nouveau calque']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($calque);
            $em->flush();
            return $this->redirectToRoute('map');
        }

        return $this->render('map/add-calque.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Supprimer un calque
    /**
     * @Route("/map/del-calque-{id}", name="del_calque")
     */
    public function deleteCalqueAction(EntityManagerInterface $em, int $id): Response
    {
        $calque = $em->getRepository('App:Calque')->find($id);

        if (!$calque) {
            throw $this->createNotFoundException('Aucun calque à supprimer');
        } else {
            $em->remove($calque);
            $em->flush();
        }
        return $this->redirectToRoute('calques_list');;
    }

    // Editer un calque
    /**
     * @Route("/map/edit-calque-{id}", name="edit_calque")
     */
    public function editCalqueAction(EntityManagerInterface $em, Request $request): Response
    {
    }

}
