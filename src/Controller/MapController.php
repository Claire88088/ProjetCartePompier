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
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $calques = $em->getRepository('App:Calque')->findAll();

        return $this->render('map/index.html.twig', [
            'calques' => $calques,
        ]);
    }

    // Affichage de la liste des calques créés pour la modification/suppression
    /**
     * @Route("/calques-list", name="calques_list")
     */
    public function calquesListAction(EntityManagerInterface $em, Request $request): Response
    {
        $calques = $em->getRepository('App:Calque')->findAll();
        $calquesTab = [];
        $options = [];

        foreach($calques as $calque)  {
            $calquesTab[] = $calque->getNom();
            $options[$calque->getNom()] = $calque->getNom();
        }

        return $this->render('/map/calques-list.html.twig', [
            'calques' => $calques,
            'calquesTab' =>$calquesTab,
        ]);
    }


    // Ajout d'un nouveau calque
    /**
     * @Route("/map/add-calque", name="add_calque")
     */
    public function addCalqueAction(EntityManagerInterface $em, Request $request): Response
    {
        $calques = $em->getRepository('App:Calque')->findAll();
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
            'calques'=> $calques,
            'form' => $form->createView()
        ]);
    }

    // Supprimer un calque
    /**
     * @Route("/map/del-calque-{id}", name="del_calque")
     */
    public function deleteCalqueAction(EntityManagerInterface $em, int $id): Response
    {
        $calques = $em->getRepository('App:Calque')->findAll();
        $calque = $em->getRepository('App:Calque')->find($id);

        if (!$calque) {
            throw $this->createNotFoundException('Aucun Calque a supprimer');
        } else {
            $em->remove($calque);
            $em->flush();
        }
        return $this->render('map/index.html.twig', ['calques' => $calques]);;
    }

    // Editer un calque
    /**
     * @Route("/map/edit-calque-{id}", name="edit_calque")
     */
    public function editCalqueAction(EntityManagerInterface $em, Request $request): Response
    {
    }

}
