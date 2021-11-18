<?php

namespace App\Controller;

use App\Entity\ElementAutoroute;
use App\Entity\EtablissementRepertorie;
use App\Entity\Travaux;
use App\Form\CalqueType;
use App\Form\ElementAutorouteType;
use App\Form\EtablissementRepertorieType;
use App\Form\TravauxType;
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
    /**
     * @Route("/", name="accueil")
     */
    public function indexAction2(): Response
    {
        return $this->redirectToRoute('map');
    }

    // Affichage de la carte avec le formulaire de recherche
    /**
     * @Route("/map", name="map")
     */
    public function indexAction(): Response
    {
        $rechercheForm = $this->createFormBuilder(null, ['attr' => ['id' => 'rechercheForm']])
            ->add('adresseRecherche')
            ->add('commune')
            ->add('Selectionner', SubmitType::class, [
                'label' => 'Rechercher'])
            ->getForm();

        return $this->render('map/index.html.twig', [
            'rechercheForm' => $rechercheForm->createView()
        ]);
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
            $this->addFlash('success', 'Le calque a bien été ajouté !');
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
            $this->addFlash('success', 'Le calque a bien été supprimé !');
        }
        return $this->redirectToRoute('calques_list');;
    }

    /**
     * @Route("/map/edit-calque-{id}", name="edit_calque")
     */
    public function editCalqueAction(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $calque = $em->getRepository('App:Calque')->find($id);
        $form = $this->createForm(CalqueType::class, $calque);
        $form->add('Modifier', SubmitType::class, ['label' => 'Modifier le calque']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($calque);
            $em->flush();
            return $this->redirectToRoute('calques_list');
        }

        return $this->render('map/add-calque.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Ajouter un établissement répertorié
    /**
     * @Route("/map/add-er-{idCalque}", name="add_er")
     */
    public function addERAction(EntityManagerInterface $em, Request $request, int $idCalque): Response
    {
        $er = new EtablissementRepertorie();

        // on ajoute le calque auquel il est lié au nouvel établissement
        $calque = $em->getRepository('App:Calque')->find($idCalque);
        $er->setCalque($calque);

        $form = $this->createForm(EtablissementRepertorieType::class, $er);

        // on ajoute le champ de choix du type d'établissement (choix issu de la table TypeEtablissementRepertorie de la BD)
        $typeChoices = $em->getRepository('App:TypeEtablissementRepertorie')->findAll();
        $typeChoicesTab = [];
        $options = [];
        foreach($typeChoices as $type)  {
            $typeChoicesTab[] = $type->getNomType();
            $options[$type->getNomType()] = $type->getNomType();
        }
        $form->add('type', ChoiceType::class, [
            'choices'  => $options,
        ]);

        // on ajoute le bouton de soumission
        $form->add('Ajouter', SubmitType::class, ['label' => 'Ajouter un nouveau ER']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($er);
            $em->flush();
            $this->addFlash('success', 'L\'établissement a bien été ajouté !');
            return $this->redirectToRoute('map');
        }

        return $this->render('map/add-er.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Ajout d'un nouvel élément
    /**
     * @Route("/choice-calque", name="choice_calque")
     */
    public function ajouterElementAction(EntityManagerInterface $em, Request $request): Response
    {
        // on créé le formulaire de choix de calque directement dans le controller (à partir des données de la BD)
        $calques = $em->getRepository('App:Calque')->findAll();
        $calquesTab = [];
        $options = [];
        foreach($calques as $calque)  {
            $calquesTab[] = $calque->getNom();
            $options[$calque->getNom()] = $calque->getId();
        }
        $choixCalqueForm = $this->createFormBuilder()
            ->add('calque', ChoiceType::class, ['choices' => $options])
            ->add('Selectionner', SubmitType::class, [
                'label' => 'Sélectionner ce calque'])
            ->getForm();

        $choixCalqueForm->handleRequest($request);

        if ($choixCalqueForm->isSubmitted() && $choixCalqueForm->isValid()) {
            // on récupère l'id du calque sur lequel on veut ajouter un élément
            $idCalqueChoisi = $request->request->get('form')['calque'];

            // TODO : gestion des cas où on veut ajouter un élément d'un ER

            // on créé le formulaire d'ajout en fonction de l'id du calque
            // TODO : le faire dynamiquement
           /*foreach($calques as $calque)  {
                if ($calque->getId() == $idCalqueChoisi) {

                }
            }
            */
            switch ($idCalqueChoisi) {
                case '1':
                    $element = new Travaux();
                    $formTypeClass = TravauxType::class;
                    break;
                case '2':
                    return $this->redirectToRoute('add_er', ['idCalque' => $idCalqueChoisi]);
                case '4':
                    $element = $em->getRepository(ElementAutoroute::class)->find(1);
                    //$element = new ElementAutoroute();
                    $formTypeClass = ElementAutorouteType::class;
                    break;
            }
           //var_dump('on est ici');
            $element->setCalque($calqueChoisi);

            $form = $this->createForm($formTypeClass, $element);
            $form->add('Ajouter', SubmitType::class, ['label' => 'Ajouter']);

            $form->handleRequest($request);

            if ($form->isSubmitted()) { var_dump('soumis');}

                        if ($form->isSubmitted() && $form->isValid()) {
                            var_dump('coucou');
                            $em->persist($element);
                            $em->flush();
                            return $this->redirectToRoute('map');
                        }

            // on redirige vers la page d'ajout avec le formulaire d'ajout correspondant
            return $this->render('/map/add-element.html.twig', [
                'element' => $element,
                'calque' => $calqueChoisi,
                'form' => $form->createView()
            ]);
        }

        return $this->render('/map/choix-calque.html.twig', [
            'calques' => $calques,
            'calquesTab' =>$calquesTab,
            'choixCalqueForm' => $choixCalqueForm->createView()
        ]);
    }
}
