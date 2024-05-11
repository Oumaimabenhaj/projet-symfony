<?php

namespace App\Controller;
use App\Service\MyGmailMailerService;
use App\Entity\Medicament;
use App\Entity\Categorie;
use App\Entity\PropertySearch;
use App\Form\MedicamentType;
use App\Form\PropertySearchType;
use App\Repository\MedecinRepository;
use App\Repository\MedicamentRepository;
use Doctrine\ORM\Cache\EntityCacheKey;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MedicamentController extends AbstractController
{
  
   
    #[Route('/medicament', name: 'app_medicament1')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);
    
        $em = $doctrine->getManager();
        $currentPage = $request->query->getInt('page', 1);
        $itemsPerPage = 8;
        $offset = ($currentPage - 1) * $itemsPerPage;
    
        $commentRepository = $em->getRepository(Medicament::class);
    
        // Si le formulaire est soumis et valide, effectuer la recherche
        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $propertySearch->getNom();
            if ($nom) {
                // Si le nom est fourni, rechercher par nom
                $medicament = $commentRepository->findBy(['nom_med' => $nom], null, $itemsPerPage, $offset);
            } else {
                // Sinon, afficher tous les médicaments paginés
                $medicament = $commentRepository->findBy([], null, $itemsPerPage, $offset);
            }
        } else {
            // Si le formulaire n'est pas soumis, afficher tous les médicaments paginés
            $medicament = $commentRepository->findBy([], null, $itemsPerPage, $offset);
        }
    
        // Calculer le nombre total de pages
        $totalItems = $commentRepository->count([]);
        $totalPages = ceil($totalItems / $itemsPerPage);
    
        $repo1 = $doctrine->getRepository(Categorie::class);
        $categorie = $repo1->findAll();
    
        return $this->render('medicament/FontOffice.html.twig', [
            'form' => $form->createView(),
            'list' => $medicament,
            'listCategorie' => $categorie,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }
    #[Route('/addMedicament', name: 'app_addMedicament')]
    public function addMedicament(Request $req, ManagerRegistry $doctrine,SluggerInterface $slugger): Response
    { 
        // Créer une nouvelle instance de l'entité Medicament
        $medicament = new Medicament();
        
        // Créer un formulaire pour l'entité Medicament en utilisant le type de formulaire MedicamentType
        $form = $this->createForm(MedicamentType::class, $medicament,);
     
        // Gérer la soumission du formulaire
        $form->handleRequest($req);
        
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() ) {

            $image = $form->get('image')->getData();
        
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newname = $safeFilename .md5(uniqid()).'.'.$image->getClientOriginalExtension();
            
                // Move the uploaded file to the destination directory
                try {
                    $image->move('C:\Users\laame\Desktop\abdslem\PIDev\public\uploads', $newname);
                } catch (FileException $e) {
                    // Handle the exception appropriately, e.g., log the error
                    // and return an error response to the user.
                }
            
                $medicament->setImage($newname);
            }
            
            
            
            // Obtenir le gestionnaire d'entités
            $em = $doctrine->getManager();
            
            // Persister la nouvelle entité Medicament
            $em->persist($medicament);
            
            // Enregistrer les modifications dans la base de données
            $em->flush();
            
            // Rediriger vers la route pour afficher la liste des Medicaments
            return $this->redirectToRoute('app_afficherMedicament');
        }
        
        // Rendre le modèle de formulaire s'il n'est pas valide ou non soumis
        return $this->renderForm("medicament/AjouterMedicament/add1.html.twig", ["myForm" => $form]);
    }
    #[Route('/afficherMedicament', name: 'app_afficherMedicament')]
    
        public function listMedicaments(Request $request,  ManagerRegistry $doctrine)
{
    $propertySearch = new PropertySearch();
    $form = $this->createForm(PropertySearchType::class, $propertySearch);
    $form->handleRequest($request);

    $em = $doctrine->getManager();
    $currentPage = $request->query->getInt('page', 1);
    $itemsPerPage = 8;
    $offset = ($currentPage - 1) * $itemsPerPage;

    $commentRepository = $em->getRepository(Medicament::class);

    // Si le formulaire est soumis et valide, effectuer la recherche
    if ($form->isSubmitted() && $form->isValid()) {
        $nom = $propertySearch->getNom();
        if ($nom) {
            // Si le nom est fourni, rechercher par nom
            $medicament = $commentRepository->findBy(['nom_med' => $nom], null, $itemsPerPage, $offset);
        } else {
            // Sinon, afficher tous les médicaments paginés
            $medicament = $commentRepository->findBy([], null, $itemsPerPage, $offset);
        }
    } else {
        // Si le formulaire n'est pas soumis, afficher tous les médicaments paginés
        $medicament = $commentRepository->findBy([], null, $itemsPerPage, $offset);
    }

    // Calculer le nombre total de pages
    $totalItems = $commentRepository->count([]);
    $totalPages = ceil($totalItems / $itemsPerPage);


    return $this->render('medicament/ConsulterMedicament/list2.html.twig', [
        'form' => $form->createView(),
        'list' => $medicament,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
    ]);
}



    #[Route('/afficherMedicament1', name: 'app_afficherMedicament1')]
    public function afficher1(ManagerRegistry $doctrine): Response
    {
        // Obtenir le dépôt pour l'entité Medicament
        $repo = $doctrine->getRepository(Medicament::class);
        
        // Récupérer toutes les entités Medicament de la base de données
        $medicament = $repo->findAll();
        
        // Rendre le modèle pour afficher la liste des Medicaments
        return $this->render('medicament/ConsulterMedicament/list2.html.twig', ['list' => $medicament]);
    }
    #[Route('/afficherMedicament2', name: 'app_afficherMedicament2')]
    public function afficher(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $currentPage = $request->query->getInt('page', 1); 

        $itemsPerPage = 7; 
        $offset = ($currentPage - 1) * $itemsPerPage; 

        $commentRepository = $em->getRepository(Medicament::class);

        
        $medicament = $commentRepository->findBy([], null, $itemsPerPage, $offset);

        $totalItems = $commentRepository->count([]);

        $totalPages = ceil($totalItems / $itemsPerPage);

        return $this->render('medicament/ConsulterMedicament/list2.html.twig', [
            'list' => $medicament,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }
    
    #[Route('/editMedicament/{id}', name: 'app_editMedicament')]
public function edit(MedicamentRepository $repository, $id, Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger)
{
    // Trouver l'entité Medicament par son ID
    $medicament = $repository->find($id);
    
    // Récupérer le nom du fichier image actuel
    $currentImage = $medicament->getImage();
    
    // Créer un formulaire pour éditer l'entité Medicament
    $form = $this->createForm(MedicamentType::class, $medicament);
    
    // Gérer la soumission du formulaire
    $form->handleRequest($request);
    
    // Vérifier si le formulaire est soumis et valide
    if ($form->isSubmitted() ) {
        $image = $form->get('image')->getData();
    
        // Vérifier si une nouvelle image a été téléchargée
        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newname = $safeFilename . '_' . md5(uniqid()) . '.' . $image->getClientOriginalExtension();
        
            // Déplacer le fichier téléchargé vers le répertoire de destination
            try {
                $image->move('C:\Users\laame\Desktop\abdslem\PIDev\public\uploads', $newname);
            } catch (FileException $e) {
                // Gérer l'exception de manière appropriée, par exemple, en enregistrant une erreur dans les journaux
                return $this->redirectToRoute('app_editMedicament', ['id' => $id]);
            }
        
            // Mettre à jour le nom du fichier image dans l'entité Medicament
            $medicament->setImage($newname);
        } else {
            // Si aucune nouvelle image n'est téléchargée, conserver le nom de l'image actuelle
            $medicament->setImage($currentImage);
        }

        // Obtenir le gestionnaire d'entités
        $em = $doctrine->getManager();
        
        // Enregistrer les modifications dans la base de données
        $em->flush();
        
        // Rediriger vers la route pour afficher la liste des Medicaments
        return $this->redirectToRoute('app_afficherMedicament');
    }

    // Rendre le modèle de formulaire pour éditer l'entité Medicament
    return $this->render('medicament/ConsulterMedicament/edit.html.twig', [
        'myForm' => $form->createView(),
        'currentImage' => $currentImage, // Passer le nom du fichier image actuel à la vue
    ]);
}

    
    #[Route('/deleteMedicament/{id}', name: 'app_deleteMedicament')]
    public function delete($id, MedicamentRepository $repository, ManagerRegistry $doctrine)
    {
        // Trouver l'entité Medicament par son ID
        $medicament = $repository->find($id);
        
        // Obtenir le gestionnaire d'entités
        $em = $doctrine->getManager();
        
        // Supprimer l'entité Medicament
        $em->remove($medicament);
        
        // Enregistrer les modifications dans la base de données
        $em->flush();
        $this->addFlash('success', 'Médicament est supprimé avec succès.');
        // Rediriger vers la route pour afficher la liste des Medicaments
        return $this->redirectToRoute('app_afficherMedicament');
    }
    #[Route('/showMedicament/{id}', name: 'app_show')]
    public function show($id,MedicamentRepository $repo): Response
    {
        $medicaments = $repo->find($id);
        return $this->render('medicament/DetailMedicament.html.twig',['id'=>$id,
        'list' => $medicaments,
        ]);
    }
 
    #[Route('/stat', name: 'app_stat')]
public function statistiquesMedicament(ManagerRegistry $doctrine,MedicamentRepository $medicamentRepository): Response
{
    $entityManager = $doctrine->getManager();
    
    // Récupérer les statistiques des médicaments par catégorie et état
    $top5Medicaments = $medicamentRepository->getTop5ExpiringMedicaments();
    $medicamentStats = $entityManager->getRepository(Medicament::class)->getMedicamentStats();
    $medicamentStats1 = $entityManager->getRepository(Medicament::class)->getMedicamentStats1();
    $categorieStats = $entityManager->getRepository(Medicament::class)->getCategorieStats();
    // Convertir les résultats en un format adapté pour Chart.js
    $labels = [];
    $data = [];

    foreach ($medicamentStats as $medicamentStat) {
        $labels[] = $medicamentStat['etat'];
        $data[] = $medicamentStat['nombreMedicament'];
    }
    //////////////////////////////////////////
    $labels1 = [];
    $data1 = [];

    foreach ($medicamentStats1 as $medicamentStat) {
        $labels1[] = $medicamentStat['nom_med'];
        $data1[] = $medicamentStat['totalQte'];
    }
///////////////////////////////////////////////////////
$labels2 = [];
    $data2 = [];

    foreach ($categorieStats as $medicamentStat1) {
        $labels2[] = $medicamentStat1['categorie'];
        $data2[] = $medicamentStat1['nombreMedicament'];
    }
    return $this->render(
        'medicament/ConsulterMedicament/stat.html.twig',
        ['labels' => json_encode($labels), 'data' => json_encode($data),
        'labels1' => json_encode($labels1), 'data1' => json_encode($data1),
        'labels2' => json_encode($labels2), 'data2' => json_encode($data2),
        'medicaments' => $top5Medicaments,]
    );
}

    


}