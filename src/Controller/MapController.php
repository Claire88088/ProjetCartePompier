<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\Element;
use App\Entity\Icone;
use App\Entity\Point;
use App\Entity\TypeCalque;
use App\Entity\TypeElement;
use App\Form\AutorouteType;
use App\Form\CalqueType;
use App\Form\DefaultElementType;
use App\Form\ElementAutorouteType;
use App\Form\ElementAutreType;
use App\Form\ElementERType;
use App\Form\ElementPIType;
use App\Form\ElementTravauxType;
use App\Form\ElementType;
use App\Form\ERType;
use App\Form\PIType;
use App\Form\PointType;
use App\Form\TravauxType;
use App\Form\TypeElementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class MapController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function indexAction2(): Response
    {
        return $this->redirectToRoute('map');
    }

    /**
     * Affichage de la carte avec le formulaire de recherche
     * @Route("/map", name="map")
     */
    public function indexAction(EntityManagerInterface $em): Response
    {
        return $this->render('map/index.html.twig');
    }

    public function rechercheFormAction(EntityManagerInterface $em): Response
    {

        $rechercheForm = $this->createFormBuilder(null, ['attr' => ['id' => 'rechercheForm']])
            ->add('commune', EntityType::class, array(
                'class' => Commune::class,
                'choices' => $em->getRepository(Commune::class)->findAll(),
                'mapped' => false,
                'choice_attr' => function($choice) {
                    return [
                        'latitude' => $choice->__toStringLat(),
                        'longitude' => $choice->__toStringLong()
                    ];
                },
                'label' => 'Choisir une commune (si recherche hors Châtellerault)'
            ))
            ->getForm();

        return $this->render('recherche-form.html.twig', [
            'rechercheForm' => $rechercheForm->createView(),
        ]);
    }

    // Envoi des données nécessaires à JS
    public function envoiDonneesJSAction(EntityManagerInterface $em): Response
    {
        $calques = $em->getRepository('App:TypeCalque')->findAll();

        $calquesNomTab = [];
        foreach ($calques as $calque) {
            array_push($calquesNomTab, $calque->getNom());
        }

        $allElements = $em->getRepository('App:TypeCalque')->findAllElementsToShow();

        return $this->render('envoi-donnees-JS.html.twig', [
            'calquesNomsList' => $calquesNomTab,
            'allElements' => $allElements,
        ]);
    }

    /**
     * Affichage de la liste des calques créés pour la modification/suppression
     * @Route("/calques-list", name="calques_list")
     * @IsGranted("ROLE_USER")
     */
    public function calquesListAction(EntityManagerInterface $em): Response
    {
        $calques = $em->getRepository('App:TypeCalque')->findAll();
        $typesEltWithCalque = $em->getRepository('App:TypeElement')->findAllWithCalque();

        return $this->render('/map/calques-list.html.twig', [
            'calques' => $calques,
            'typesEltWithCalque' => $typesEltWithCalque
        ]);
    }


    /**
     * Ajout d'un nouveau calque
     * forcément du type "AUTRE"
     * @Route("/map/add-calque", name="add_calque")
     * @IsGranted("ROLE_USER")
     */
    public function addCalqueAction(EntityManagerInterface $em, Request $request): Response
    {
        $calque = new TypeCalque();
        $calque->setType('AUTRE');
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

    /**
     * Modifier un calque
     * uniquement le nom et pas le type
     * @Route("/map/edit-calque-{id}", name="edit_calque")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editCalqueAction(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $calque = $em->getRepository('App:TypeCalque')->find($id);
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

    /**
     * Supprimer un calque
     * @Route("/map/del-calque-{id}", name="del_calque")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteCalqueAction(EntityManagerInterface $em, int $id): Response
    {
        $calque = $em->getRepository('App:TypeCalque')->find($id);

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
     * Choix du calque sur lequel ajouter un élément ou un type d'élément
     * @Route("/choice-calque-{eltToCreate}", name="choice_calque")
     * @IsGranted("ROLE_USER")
     */
    public function choiceCalqueAction(Request $request, String $eltToCreate): Response
    {
        // on choisit sur quel calque on veut mettre l'élément
        $choixCalqueForm = $this->createFormBuilder()
            ->add('calque', EntityType::class, [
                'class' => TypeCalque::class,
                'mapped' => false,
            ])
            ->add('Selectionner', SubmitType::class, [
                'label' => 'Sélectionner ce calque'])
            ->getForm();

        $choixCalqueForm->handleRequest($request);

        if ($choixCalqueForm->isSubmitted() && $choixCalqueForm->isValid()) {
            $idCalqueChoisi = $request->request->get('form')['calque'];

            if ($eltToCreate == 'typeElt') {
                return $this->redirectToRoute('add_type_element', ['idCalque'=>$idCalqueChoisi]);
            }
            if ($eltToCreate == 'element') {
                return $this->redirectToRoute('add_element', ['idCalque' => $idCalqueChoisi]);
            }
        }

        return $this->render('/map/choix-calque.html.twig', [
            'choixCalqueForm' => $choixCalqueForm->createView(),
            'typeACreer' => $eltToCreate
        ]);
    }


    /**
     * Ajout d'un nouveau type d'éléments
     * forcément du type "AUTRE"
     * @Route("/map/add-type-element-{idCalque}", name="add_type_element")
     * @IsGranted("ROLE_USER")
     */
    public function addTypeEltAction(EntityManagerInterface $em, Request $request, int $idCalque): Response
    {
        $calqueChoisi = $em->getRepository('App:TypeCalque')->find($idCalque);

        $typeElt = new TypeElement();
        $typeElt->setTypeCalque($calqueChoisi)->setType('AUTRE');
        $form = $this->createForm(TypeElementType::class, $typeElt);
        $form->add('Ajouter', SubmitType::class, ['label' => 'Ajouter un nouveau type d\'élément']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeElt);
            $em->flush();
            $this->addFlash('success', 'Le type d\'élément a bien été ajouté !');
            return $this->redirectToRoute('map');
        }

        return $this->render('map/add-type-element.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Modifier un type d'élément
     * uniquement le nom et pas le type
     * @Route("/map/edit-type-element-{id}", name="edit_type_element")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editTypeEltAction(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $typeElt = $em->getRepository('App:TypeElement')->find($id);
        $form = $this->createForm(TypeElementType::class, $typeElt);
        $form->add('Modifier', SubmitType::class, ['label' => 'Modifier le type d\'élément']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeElt);
            $em->flush();
            return $this->redirectToRoute('map');
        }

        return $this->render('map/add-type-element.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Supprimer un type d'élément
     * @Route("/map/del-type-element-{id}", name="del_type_element")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteTypeEltAction(EntityManagerInterface $em, int $id): Response
    {
        $typeElt = $em->getRepository('App:TypeElement')->find($id);

        if (!$typeElt) {
            throw $this->createNotFoundException('Aucun type d\'élément à supprimer');
        } else {
            $em->remove($typeElt);
            $em->flush();
            $this->addFlash('success', 'Le type d\'élément a bien été supprimé !');
        }
        return $this->redirectToRoute('map');;
    }

    /**
     * Ajout d'un nouvel élément
     * @Route("/map/add-element-{idCalque}", name="add_element")
     * @IsGranted("ROLE_USER")
     */
    public function addElementAction(EntityManagerInterface $em, Request $request, int $idCalque, SluggerInterface $slugger): Response
    {
        $calqueChoisi = $em->getRepository('App:TypeCalque')->find($idCalque);
        $icones = $em->getRepository('App:Icone')->findAll();
        $typeCalqueChoisi = $calqueChoisi->getType();

        $point = new Point();
        $element = new Element();

        // on récupère les types d'éléments possibles pour le type de calque choisi
        $typesElt = $em->getRepository('App:TypeElement')->findByTypeCalque($calqueChoisi);
        $options = [];
        foreach($typesElt as $type)  {
            $options[$type->getNom()] = $type;
        }

        // on récupère les liens des icones
        $liensIcones = [];
        foreach($icones as $icone)  {
            $liensIcones[$icone->getLien()] = $icone;
        }
        /*
            $data = [];
            $data['typeEltChoices'] = $options;
            $data['iconeChoices'] = $liensIcones;
          */
        // on créé le formulaire en fonction du type de calque
        switch ($typeCalqueChoisi) {
            case 'ER':
                $elementForm = $this->createForm(ERType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones));
                $elementForm->add('typeElement', ChoiceType::class, [
                    'choices'  => $options,
                ]);

                /*
                $elementForm = $this->createForm(ERType::class, $element, [
                    'data_class' => null,
                    'data' => $data,
                ]);*/

                break;
            case 'AUTOROUTE':
                $elementForm = $this->createForm(AutorouteType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones));
                $elementForm->add('typeElement', ChoiceType::class, [
                    'choices'  => $options,
                ]);
                break;
            case 'TRAVAUX':
                $elementForm = $this->createForm(TravauxType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones));
                $typeTravaux = $em->getRepository('App:TypeElement')->findByTypeCalque($calqueChoisi)[0];
                $element->setTypeElement($typeTravaux);
                break;
            case 'PI':
                $elementForm = $this->createForm(PIType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones));
                $typePI = $em->getRepository('App:TypeElement')->findByTypeCalque($calqueChoisi)[0];
                $element->setTypeElement($typePI);
                break;
            case 'AUTRE':
                $elementForm = $this->createForm(ElementType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones));
                break;

        }

        $elementForm->add('ajouter', SubmitType::class, ['label' => 'Ajouter cet élément']);
        $elementForm->handleRequest($request);

        if ($elementForm->isSubmitted() && $elementForm->isValid()) {
            // ici name correspond au nom du formulaire
            /*foreach ($_POST as $name => $value) {*/
            foreach ($_POST as $name => $value) /*if ($elementForm->isSubmitted() && $elementForm->isValid()) */{

                $Photo = "";
                $Lien = "";

                $photoFile = null;
                $pdfFile = null;

                $newPhotoName = null;
                $newPdfName = null;

                $issetPhoto = isset($_FILES[$name]["name"]['photo']);
                $issetLien = isset($_FILES[$name]["name"]['lien']);

                if ($issetPhoto && $issetLien) {
                    $Photo = $_FILES[$name]["name"]["photo"];
                    $Lien = $_FILES[$name]["name"]["lien"];
                } else if ($issetPhoto && !$issetLien) {
                    $Photo = $_FILES[$name]["name"]["photo"];
                } else if (!$issetPhoto && $issetLien) {
                    $Lien = $_FILES[$name]["name"]["lien"];
                }

                // Test si dans le POST, il y'a des envois de fichiers
                if ($Photo !== "" && $Lien === "") {
                    $photoFile = $elementForm->get('photo')->getData();
                    if ($photoFile) {
                        $originalPhotoFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safePhotoFilename = $slugger->slug($originalPhotoFilename);
                        $newPhotoName = $safePhotoFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();
                    }
                } else if ($Lien !==  "" && $Photo === "") {
                    $pdfFile = $elementForm->get('lien')->getData();
                    if ($pdfFile) {
                        $originalPdfFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safePdfFilename = $slugger->slug($originalPdfFilename);
                        $newPdfName = $safePdfFilename . '-' . uniqid() . '.' . $pdfFile->guessExtension();
                    }
                } else if ($Lien !== "" && $Photo !== "") {
                    $photoFile = $elementForm->get('photo')->getData();
                    $pdfFile = $elementForm->get('lien')->getData();
                    if ($photoFile && $pdfFile) {
                        $originalPhotoFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safePhotoFilename = $slugger->slug($originalPhotoFilename);
                        $newPhotoName = $safePhotoFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                        $originalPdfFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safePdfFilename = $slugger->slug($originalPdfFilename);
                        $newPdfName = $safePdfFilename . '-' . uniqid() . '.' . $pdfFile->guessExtension();
                    }
                }
                // Move the file to the directory where photos/pdf are stored
                try {
                    if ($photoFile) {
                        $photoFile->move(
                            $this->getParameter('uploads_photos'),
                            $newPhotoName
                        );
                    }
                    if ($pdfFile) {
                        $pdfFile->move(
                            $this->getParameter('uploads_pdf'),
                            $newPdfName
                        );
                    }
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $element->setPhoto($newPhotoName);
                $element->setLien($newPdfName);
            }

            $em->persist($element);

            // on ajoute les données au Point
            $point->setElement($element);
            $coordGPS = $request->request->get($name)['coordonnees'];
            $longitude = $coordGPS['longitude'];
            $latitude = $coordGPS['latitude'];
            $point->setLongitude($longitude);
            $point->setLatitude($latitude);
            $point->setRang(1);

            $em->persist($point);

            $em->flush();
            $this->addFlash('success', 'L\'élément a bien été ajouté !');
            return $this->redirectToRoute('map');
        }

        return $this->render('map/add-element.html.twig', [
            'form' => $elementForm->createView(),
            'nomCalque' => $calqueChoisi->getNom(),
        ]);
    }

    /**
     * Modification d'un élément
     * @Route("/map/edit-element-{idElement}", name="edit_element")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editElementAction(EntityManagerInterface $em, Request $request, int $idElement): Response
    {
        $elementClique = $em->getRepository('App:Element')->find($idElement);
        $idtypeElementC = $elementClique->getTypeElement();
        $typeElement = $em->getRepository('App:TypeElement')->find($idtypeElementC);
        $idTypeCalqueC = $typeElement->getTypeCalque();
        $typeCalque = $em->getRepository('App:TypeCalque')->find($idTypeCalqueC);
        $typeTypeCalqueC = $typeCalque->getType();

        $icones = $em->getRepository('App:Icone')->findAll();
        $liensIcones = [];
        foreach($icones as $icone)  {
            $liensIcones[$icone->getLien()] = $icone;
        }

        switch ($typeTypeCalqueC) {
            case 'ER':
                $elementForm = $this->createForm(ERType::class, $elementClique);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'mapped' => true));
                break;
            case 'TRAVAUX':
                $elementForm = $this->createForm(TravauxType::class, $elementClique);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'mapped' => true));
                break;
            case 'AUTOROUTE':
                $elementForm = $this->createForm(AutorouteType::class, $elementClique);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'mapped' => true));
                break;
            case 'PI':
                $elementForm = $this->createForm(PIType::class, $elementClique);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'mapped' => true));
                break;
            case 'AUTRE':
                $elementForm = $this->createForm(DefaultElementType::class, $elementClique);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'mapped' => true));
                break;
        }

        $elementForm->add('modifier', SubmitType::class, ['label' => 'Modifier']);
        $elementForm->handleRequest($request);

        if ($elementForm->isSubmitted() && $elementForm->isValid()) {
            $em->persist($elementClique);
            $em->flush();

            $this->addFlash('success', "L'élement a bien été modifié");
            return $this->redirectToRoute('map');
        }

        return $this->render('map/edit-element.html.twig', [
            'idElement' => $idElement,
            'form' => $elementForm->createView()
        ]);
    }

    /**
     * Suppression d'un élément
     * @Route("/map/delete-element-{idElement}", name="delete_element")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteElementAction(EntityManagerInterface $em, int $idElement): Response
    {
        $elementClique = $em->getRepository('App:Element')->find($idElement);
        $em->remove($elementClique);
        $em->flush();
        $this->addFlash('success', "L'élement a bien été supprimé");
        return $this->redirectToRoute('map');
    }

    // Affichage de la liste des calques et des types d'éléments
    public function listAction(EntityManagerInterface $em): Response
    {
        $calques = $em->getRepository('App:TypeCalque')->findAll();
        $typesEltWithCalque = $em->getRepository('App:TypeElement')->findAllWithCalque();

        return $this->render('/map/list.html.twig', [
            'calques' => $calques,
            'typesEltWithCalque' => $typesEltWithCalque
        ]);
    }

}
