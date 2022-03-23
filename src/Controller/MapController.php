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
use App\Form\IconeType;
use App\Form\PIType;
use App\Form\PointType;
use App\Form\TravauxType;
use App\Form\TypeElementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
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
        $defaultCommune = $em->getRepository('App:Commune')->findMinId();
        $defaultLatAndLong = [$defaultCommune->getLatitude(), $defaultCommune->getLongitude()];

        $calques = $em->getRepository('App:TypeCalque')->findAll();

        $calquesNomTab = [];
        foreach ($calques as $calque) {
            array_push($calquesNomTab, $calque->getNom());
        }
        $allElements = $em->getRepository('App:TypeCalque')->findAllElementsToShow();

        $sessionAttr = $_SESSION['_sf2_attributes'];
        $c = false;
        $role = "";
        if(array_key_exists('_security.last_username', $sessionAttr)) {
            $c = true;
            $nomUtilisateur = $_SESSION['_sf2_attributes']['_security.last_username'];
            $role = $em->getRepository('App:Users')->findRoleByName($nomUtilisateur)[0]['roles'][0];
        }

        return $this->render('envoi-donnees-JS.html.twig', [
            'defaultLatAndLong' => $defaultLatAndLong,
            'calquesNomsList' => $calquesNomTab,
            'allElements' => $allElements,
            'isConnected' => $c,
            'role' => $role
        ]);
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

    /**
     * Affichage de la liste des éléments à partir du type choisi
     * @IsGranted("ROLE_USER")
     */
    public function listElementsAction(EntityManagerInterface $em, int $idTypeElt): Response
    {
        $elementsByType = $em->getRepository('App:Element')->findByTypeElement($idTypeElt);
        return $this->render('/map/list-elements.html.twig', [
            'elementsByType' => $elementsByType,
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
        return $this->redirectToRoute('map');;
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
     * Ajout d'un nouvel élément à partir du type choisi
     * @Route("/map/add-element-{idCalque}-{idTypeElt}", name="add_element_from_type")
     * @IsGranted("ROLE_USER")
     */
    public function addElementFromTypeAction(EntityManagerInterface $em, Request $request, int $idCalque, int $idTypeElt, SluggerInterface $slugger): Response
    {
        $calqueChoisi = $em->getRepository('App:TypeCalque')->find($idCalque);
        $typeCalqueChoisi = $calqueChoisi->getType();

        $typeEltChoisi = $em->getRepository('App:TypeElement')->find($idTypeElt);

        $point = new Point();
        $element = new Element();
        $element->setTypeElement($typeEltChoisi);

        // on créé le formulaire en fonction du type d'élément
        $elementForm = $this->createFormFromTypeCalque($typeCalqueChoisi, $element, $em);

        $elementForm->add('ajouter', SubmitType::class, ['label' => 'Ajouter cet élément']);
        $elementForm->handleRequest($request);

        if ($elementForm->isSubmitted() && $elementForm->isValid()) {
            if (isset($_FILES[key($_FILES)]["name"]["photo"])) {
                $this->photoTreatment($elementForm, $slugger, $element);
            }
            if (isset($_FILES[key($_FILES)]["name"]["lien"])) {
                $this->pdfTreatment($elementForm, $slugger, $element);
            }

            $em->persist($element);

            // on ajoute les données au Point
            $point->setElement($element);
            $coordGPS = $request->request->get(key($_POST))['coordonnees'];
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
            'nomTypeElt' => $typeEltChoisi->getNom(),
            'nomCalque' => $calqueChoisi->getNom(),
        ]);
    }


    /**
     * Modification d'un élément
     * @Route("/map/edit-element-{idElement}", name="edit_element")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editElementAction(EntityManagerInterface $em, Request $request, int $idElement, SluggerInterface $slugger): Response
    {
        $elementClique = $em->getRepository('App:Element')->find($idElement);
        $nomElement = $elementClique->getNom();

        $lien = $elementClique->getLien();
        $photo = $elementClique->getPhoto();

        $idtypeElementC = $elementClique->getTypeElement();
        $typeElement = $em->getRepository('App:TypeElement')->find($idtypeElementC);
        $idTypeCalqueC = $typeElement->getTypeCalque();
        $typeCalque = $em->getRepository('App:TypeCalque')->find($idTypeCalqueC);
        $typeTypeCalqueC = $typeCalque->getType();
        $calqueNom = $typeCalque->getNom();
        $elementCliquePointLat = $elementClique->getPoints()[0]->getLatitude();
        $elementCliquePointLong = $elementClique->getPoints()[0]->getLongitude();

        // on créé le formulaire en fonction du type d'élément
        $elementForm = $this->createFormFromTypeCalque($typeTypeCalqueC, $elementClique, $em);

        $elementForm->add('modifier', SubmitType::class, ['label' => 'Modifier']);
        $elementForm->handleRequest($request);

        if ($elementForm->isSubmitted() && $elementForm->isValid()) {
            // si on a des fichiers téléchargés (photo ou pdf)
            if ($_FILES[key($_FILES)]["name"]["photo"]) {
                $this->photoTreatment($elementForm, $slugger, $elementClique);
            }
            if ($_FILES[key($_FILES)]["name"]["lien"]) {
                $this->pdfTreatment($elementForm, $slugger, $elementClique);
            }

            $em->persist($elementClique);
            $em->flush();

            $this->addFlash('success', "L'élément a bien été modifié");
            return $this->redirectToRoute('map');
        }

        return $this->render('map/edit-element.html.twig', [
            'nomElement' => $nomElement,
            'idElement' => $idElement,
            'form' => $elementForm->createView(),
            'latitude' => $elementCliquePointLat,
            'longitude' => $elementCliquePointLong,
            'nomCalque' => $calqueNom,
            'photo' => $photo,
            'lien' => $lien
        ]);
    }

    /**
     * Crée un formulaire en fonction du type de calque choisi
     * @param String $typeCalqueChoisi
     * @param Element $element
     * @param EntityManagerInterface $em
     * @return Form
     */
    private function createFormFromTypeCalque(String $typeCalqueChoisi, Element $element, EntityManagerInterface $em): Form
    {
        $icones = $em->getRepository('App:Icone')->findAll();

        // on récupère les liens des icones
        $liensIcones = [];
        foreach($icones as $icone)  {
            $liensIcones[$icone->getLien()] = $icone;
        }

        switch ($typeCalqueChoisi) {
            case 'ER':
                $elementForm = $this->createForm(ERType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'choice_attr' => function ($icone, $key, $index) {
                        return ['icone' =>  $icone->getUnicode()];
                    }
                ));
                break;
            case 'AUTOROUTE':
                $elementForm = $this->createForm(AutorouteType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'choice_attr' => function ($icone, $key, $index) {
                        return ['icone' =>  $icone->getUnicode()];
                    }
                ));
                break;
            case 'TRAVAUX':
                $elementForm = $this->createForm(TravauxType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'choice_attr' => function ($icone, $key, $index) {
                        return ['icone' =>  $icone->getUnicode()];
                    }
                ));
                break;
            case 'PI':
                $elementForm = $this->createForm(PIType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'choice_attr' => function ($icone, $key, $index) {
                        return ['icone' =>  $icone->getUnicode()];
                    }
                ));
                break;
            case 'AUTRE':
                $elementForm = $this->createForm(ElementType::class, $element);
                $elementForm->add('icone', ChoiceType::class, array(
                    'choices' => $liensIcones,
                    'choice_attr' => function ($icone, $key, $index) {
                        return ['icone' =>  $icone->getUnicode()];
                    }
                ));
                break;
        }

        return $elementForm;
    }

    /**
     * Traitement d'une photo : enregistrement dans le dossier upload et ajout à l'élément
     * @param Form $elementForm
     * @param SluggerInterface $slugger
     * @param Element $element
     * @return void
     */
    private function photoTreatment(Form $elementForm, SluggerInterface $slugger, Element $element) {
        $photoFile = $elementForm->get('photo')->getData();
        if ($photoFile) {
            $originalPhotoFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safePhotoFilename = $slugger->slug($originalPhotoFilename);
            $newPhotoName = $safePhotoFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

            $photoFile->move($this->getParameter('uploads_photos'), $newPhotoName);

            $element->setPhoto($newPhotoName);
        }
    }

    /**
     * Traitement d'un pdf : enregistrement dans le dossier upload et ajout à l'élément
     * @param Form $elementForm
     * @param SluggerInterface $slugger
     * @param Element $element
     * @return void
     */
    private function pdfTreatment(Form $elementForm, SluggerInterface $slugger, Element $element) {
        $pdfFile = $elementForm->get('lien')->getData();
        if ($pdfFile) {
            $originalPdfFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safePdfFilename = $slugger->slug($originalPdfFilename);
            $newPdfName = $safePdfFilename . '-' . uniqid() . '.' . $pdfFile->guessExtension();

            $pdfFile->move($this->getParameter('uploads_pdf'), $newPdfName);

            $element->setLien($newPdfName);
        }
    }

    /**
     * Suppression d'un élément
     * @Route("/map/delete-element-{idElement}", name="delete_element")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteElementAction(EntityManagerInterface $em, int $idElement): Response
    {
        $elementClique = $em->getRepository('App:Element')->find($idElement);
        if ($elementClique->getPhoto()) {
            $this->deletePhotoAction($em, $idElement);
        }

        if ($elementClique->getLien()) {
            $this->deletePdfAction($em, $idElement);
        }

        $em->remove($elementClique);
        $em->flush();

        $this->addFlash('success', "L'élément a bien été supprimé");
        return $this->redirectToRoute('map');
    }

    /**
     * Suppression de la photo d'un élément
     * @Route("/map/delete-photo-{idElement}", name="delete_photo")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deletePhotoAction(EntityManagerInterface $em, int $idElement)
    {
        $elementClique = $em->getRepository('App:Element')->find($idElement);
        $photo = $elementClique->getPhoto();

        unlink($this->getParameter('uploads_photos').'/'. $photo); //suppression du fichier
        $elementClique->setPhoto(null);
        $em->persist($elementClique);
        $em->flush();

        $this->addFlash('success', "La photo a bien été supprimée");
        return $this->redirectToRoute('edit_element', [ 'idElement' => $idElement ]);
    }

    /**
     * Suppression du pdf d'un élément
     * @Route("/map/delete-pdf-{idElement}", name="delete_pdf")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deletePdfAction(EntityManagerInterface $em, int $idElement)
    {
        $elementClique = $em->getRepository('App:Element')->find($idElement);
        $lien = $elementClique->getLien();

        unlink($this->getParameter('uploads_pdf').'/'. $lien); //suppression du fichier
        $elementClique->setLien(null);
        $em->persist($elementClique);
        $em->flush();

        $this->addFlash('success', "Le pdf a bien été supprimé");
        return $this->redirectToRoute('edit_element', [ 'idElement' => $idElement ]);
    }

    /**
     * Lister les icones
     * @Route("/map/list_icones", name="list_icones")
     * @IsGranted("ROLE_ADMIN")
     */
    public function listIconesAction(EntityManagerInterface $em): Response
    {
        $icones = $em->getRepository('App:Icone')->findAllByUnicodeDesc();

        return $this->render('map/list-icones.html.twig', [
            'icones' => $icones
        ]);
    }

    /**
     * Ajout d'une icone
     * @Route("/map/add-icone", name="add_icone")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addIconeAction(EntityManagerInterface $em, Request $request): Response
    {
        $icone = new Icone;
        $form = $this->createForm(IconeType::class, $icone);
        $form->add('Ajouter', SubmitType::class, ['label' => 'Ajouter une nouvelle icône']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Nom de l'icone qui servira a renommer les fichiers passés dans les upload
            $nameIcone = $form->get('nom')->getData();
            // Extensions voulu pour les fichiers font
            $extensions = ["eot", "svg", "ttf", "woff", "woff2"];
            // Tableau booléen qui contiendra true si l'extension correspond à celles désirées et false sinon
            $extensionsBool = [];

            // Si le nom de l'icone corresponds à la regex
            if (preg_match('/^[a-zA-Z0-9]*$/', $nameIcone)) {

                // On récupère les fichiers font qui serviront à construire l'icone
                $iconeFiles = $form->get('icone')->getData();
                // Taille du tableau
                $limite = count($iconeFiles);
                // Extension du fichier

                // On parcoure chaque fichiers pour avoir les extensions
                for ($i = 0; $i < $limite; $i++) {

                    // On récupère l'extension du fichier dans le tableau iconeFiles grace à l'explode
                    $extension = explode('.', $iconeFiles[$i]->getClientOriginalName())[1];

                    // Rempli le tableau de booléen par true ou false si l'extension du fichier est compris dans celles acceptées
                    if (in_array($extension, $extensions)) {
                        $extensionsBool[$i] = true;
                    } else {
                        $extensionsBool[$i] = false;
                    }
                }

                // Si dans le tableau il s'avère qu'une extension est false alors on renvoie une erreur et on retourne à l'ajout de l'icone
                if (in_array(false, $extensionsBool)) {
                    $this->addFlash('danger', "Les fichiers font n'ont pas les bonnes extensions, dans l'ordre, .eot, .svg, .ttf, .woff, .woff2");
                    return $this->redirectToRoute('add_icone');
                    // Sinon si tout est à true alors les extensions correspondent
                } else {
                    for ($i = 0; $i < $limite; $i++) {
                        $extension = explode('.', $iconeFiles[$i]->getClientOriginalName())[1];
                        $nameFont = $nameIcone . "." . $extension;
                        try {
                            $iconeFiles[$i]->move(
                                $this->getParameter('uploads_icones'),
                                $nameFont
                            );

                        } catch (FileException $e) {
                            // Catch des erreurs si il y en a
                        }

                        // Quand on arrive au dernier tour de boucle
                        if ($i === ($limite-1)) {
                            // On récupère le fichier svg qui servira de preview
                            $iconeSVG = $form->get('lien')->getData();
                            // On reconstruit son nom
                            $namePreview = "preview-" . $nameIcone . ".svg";
                            // On set son nom en base
                            $icone->setLien($namePreview);

                            // On le déplace dans le fichier des icones
                            try {
                                $iconeSVG->move(
                                    $this->getParameter('uploads_icones'),
                                    $namePreview
                                );

                            } catch (FileException $e) {
                                // Catch des erreurs si il y en a
                            }

                            $em->persist($icone);
                            $em->flush();
                            $this->addFlash('success', 'L\'icône a bien été ajoutée !');
                            return $this->redirectToRoute('map');
                        }
                    }
                }
            } else {
                $this->addFlash('danger', "Le nom ne peut contenir que des lettres de a à Z (en majuscule ou minuscule) et des chiffres de 0 à 9");
                return $this->redirectToRoute('add_icone');
            }
        }

        return $this->render('map/add-icone.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Suppression d'une icone
     * @Route("/map/delete-icone-{idIcone}", name="delete_icone")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteIconeAction(EntityManagerInterface $em, int $idIcone): Response
    {
        $icone = $em->getRepository('App:Icone')->find($idIcone);
        $preview = $icone->getLien();
        $name = $icone->getNom();

        unlink($this->getParameter('uploads_icones').'/'. $preview);

        $extensions = [".eot", ".svg", ".ttf", ".woff", ".woff2"];
        for ($i = 0; $i < 5; $i++) {
            unlink($this->getParameter('uploads_icones').'/'. $name.$extensions[$i]);
        }

        if (is_null($icone->getElements()[0])) {
            $em->remove($icone);
            $em->flush();
            $this->addFlash('success', "L'icône a bien été supprimée");
            return $this->redirectToRoute('list_icones');
        }
        else {
            $this->addFlash('danger', "L'icône ne peut pas être supprimée car elle est utilisée");
            return $this->redirectToRoute('list_icones');
        }
    }

}
