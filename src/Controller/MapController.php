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

    // Affichage de la liste des calques créés
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

        // Création du formulaire de choix de calque directement dans le controller
        $choiceCalqueForm = $this->createFormBuilder()
            ->add('calque', ChoiceType::class, ['choices' => $options])
            ->add('Selectionner', SubmitType::class, [
                'label' => 'Sélectionner ce calque'])
            ->getForm();

        /* Création du formulaire de choix de calque à partir d'une classe ChoiceCalqueType
        $choiceCalqueForm = $this->createForm(ChoiceCalqueType::class, ['test'=>'valeurTest']);
        $choiceCalqueForm->add('Ajouter', SubmitType::class, ['label' => 'Ajouter un nouveau calque']);
        */
        $choiceCalqueForm->handleRequest($request);

        if ($choiceCalqueForm->isSubmitted() && $choiceCalqueForm->isValid()) {
            // on récupère le nom du calque sur lequel on veut ajouter un élément
            $nomCalque = strtolower($request->request->get('form')['calque']);

            // on redirige vers la page qui contient le formulaire d'ajout correspondant
            return $this->redirectToRoute('calque_add_element', ['nomCalque' => $nomCalque]);
        }

        return $this->render('/map/calques-list.html.twig', [
            'calques' => $calques,
            'calquesTab' =>$calquesTab,
            'choiceCalqueForm' => $choiceCalqueForm->createView()
        ]);
    }

    // Ajout d'un nouveau claque
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

    // Ajout d'un nouvel élément sur un calque existant
    /**
     * @Route("/map/calque-{nomCalque}/add", name="calque_add_element")
     */
    public function addElementOnCalqueAction(EntityManagerInterface $em, Request $request, $nomCalque): Response
    {
        // en fonction du calque choisi : on ajoute le bon formulaire dans le block Twig

        // en fonction du calque on renvoie vers le template correspondant au formulaire à afficher
        return $this->render('map/calque-'.$nomCalque.'-add-element.html.twig', ['nomCalque' => $nomCalque]);
    }
}
