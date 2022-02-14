<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\Element;
use App\Entity\Icone;
use App\Entity\Point;
use App\Entity\TypeCalque;
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

        // TODO : créer une entité pour le formulaire
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
        // TODO changer le nom des variables (mais a priori on changement la façon de passer les valeurs)
        // TODO : ne passer que la liste des noms des calques

        $calques = $em->getRepository('App:TypeCalque')->findAll();

        $calquesNomTab = [];
        foreach ($calques as $calque) {
            array_push($calquesNomTab, $calque->getNom());
        }


        $erElements = $em->getRepository('App:TypeCalque')->findAllElementsToShowOnER();
        $autoElements = $em->getRepository('App:TypeCalque')->findAllElementsToShowOnAutoroute();
        $piElements = $em->getRepository('App:TypeCalque')->findAllElementsToShowOnPI();

        $allElements = $em->getRepository('App:TypeCalque')->findAllElementsToShow();

        return $this->render('envoi-donnees-JS.html.twig', [
            'calquesNomsList' => $calquesNomTab,
            'erElements' => $erElements,
            'autoElements' => $autoElements,
            'piElements' => $piElements,
            'allElements' => $allElements
        ]);
    }

//    /**
//     * @param EntityManagerInterface $em
//     * @Route("/envoi-calques", name="envoi_calques")
//     * @return JsonResponse
//     */
//    public function envoiCalques(EntityManagerInterface $em): JsonResponse
//    {
//        $elements = $em->getRepository('App:TypeCalque')->findAllElementsToShow();
//        return new JsonResponse($elements);
//    }


    /**
     * Affichage de la liste des calques créés pour la modification/suppression
     * @Route("/calques-list", name="calques_list")
     * @IsGranted("ROLE_USER")
     */
    public function calquesListAction(EntityManagerInterface $em): Response
    {
        $calques = $em->getRepository('App:TypeCalque')->findAll();

        return $this->render('/map/calques-list.html.twig', [
            'calques' => $calques,
        ]);
    }


    /**
     * Ajout d'un nouveau calque forcément du type "AUTRE"
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
     * Modifier uniquement le nom du calque et pas le type
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

    // Ajout d'un nouvel élément
    /**
     * Choix du calque sur lequel ajouter un élément
     * @Route("/choice-calque", name="choice_calque")
     * @IsGranted("ROLE_USER")
     */
    public function choiceCalqueAction(EntityManagerInterface $em, Request $request): Response
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

            return $this->redirectToRoute('add_element', ['idCalque'=>$idCalqueChoisi]);
        }

        return $this->render('/map/choix-calque.html.twig', [
            'choixCalqueForm' => $choixCalqueForm->createView()
        ]);
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
                /*
                    $elementForm = $this->createForm(AutorouteType::class, $element, [
                    'data_class' => null,
                    'data' => $data,
                ]);*/
                break;
            case 'TRAVAUX':
                $elementForm = $this->createForm(TravauxType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones));
                /*
                $elementForm = $this->createForm(TravauxType::class, $element, [
                    'data_class' => null,
                    'data' => $liensIcones,
                ]);*/
                $typeTravaux = $em->getRepository('App:TypeElement')->findByTypeCalque($calqueChoisi)[0];
                $element->setTypeElement($typeTravaux);
                break;
            case 'PI':
                $elementForm = $this->createForm(PIType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones));
                /*
                $elementForm = $this->createForm(PIType::class, $element, [
                    'data_class' => null,
                    'data' => $liensIcones,
                ]);*/
                $typePI = $em->getRepository('App:TypeElement')->findByTypeCalque($calqueChoisi)[0];
                $element->setTypeElement($typePI);
                break;
            case 'AUTRE':
                $elementForm = $this->createForm(ElementType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones));
                /*
                $elementForm = $this->createForm(ElementType::class, $element, [
                    'data_class' => null,
                    'data' => $liensIcones,
                ]);*/
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
     * Ajout d'un nouvel élément
     * @Route("/map/edit-element-{idElement}", name="edit_element")
     * @IsGranted("ROLE_USER")
     */
    public function editElementAction(EntityManagerInterface $em, Request $request, int $idElement): Response
    {
        $elementClique = $em->getRepository('App:Element')->find($idElement);
        $idtypeElementC = $elementClique->getTypeElement();
        $typeElement = $em->getRepository('App:TypeElement')->find($idtypeElementC);
        $idTypeCalqueC = $typeElement->getTypeCalque();
        $typeCalque = $em->getRepository('App:TypeCalque')->find($idTypeCalqueC);
        $typeTypeCalqueC = $typeCalque->getType();

        switch ($typeTypeCalqueC) {
            case 'ER':
                $elementForm = $this->createForm(ERType::class, $elementClique);
                break;
            case 'TRAVAUX':
                $elementForm = $this->createForm(TravauxType::class, $elementClique);
                break;
            case 'AUTOROUTE':
                $elementForm = $this->createForm(AutorouteType::class, $elementClique);
                break;
            case 'PI':
                $elementForm = $this->createForm(PIType::class, $elementClique);
                break;
            case 'AUTRE':
                $elementForm = $this->createForm(DefaultElementType::class, $elementClique);
                break;
        }

        $elementForm->add('modifier', SubmitType::class, ['label' => 'Modifier cet élément']);
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
}
