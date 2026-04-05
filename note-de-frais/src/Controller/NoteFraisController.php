<?php

namespace App\Controller;

use App\Entity\Justificatif;
use App\Entity\LigneFrais;
use App\Entity\NoteFrais;
use App\Repository\BudgetRepository;
use App\Repository\JustificatifRepository;
use App\Repository\LigneFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class NoteFraisController extends AbstractController
{
    #[Route('/note-de-frais', name: 'app_note_frais', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        BudgetRepository $budgetRepository,
        LigneFraisRepository $ligneFraisRepository,
        JustificatifRepository $justificatifRepository,
        MailerInterface $mailer
    ): Response {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $fichiers = $request->files->get('piecesJointes');

            if (!$fichiers || (is_array($fichiers) && count(array_filter($fichiers)) === 0)) {
                dd('au moins un justificatif est obligatoire');
            }

            $nomBudget = $this->convertirValeurBudget($data['budget'] ?? '');
            $budget = $budgetRepository->findOneBy(['nom' => $nomBudget]);

            if (!$budget) {
                dd('budget introuvable en base', $nomBudget);
            }

            $noteFrais = new NoteFrais();
            $noteFrais->setNomDemandeur($data['nomDemandeur'] ?? '');
            $noteFrais->setDateDemande(new \DateTime($data['dateDemande']));
            $noteFrais->setRaisonDepense($data['raisonDepense'] ?? '');
            $noteFrais->setTotalKm($this->nettoyerNombre($data['totalKm'] ?? '0'));
            $noteFrais->setMontantKm($this->nettoyerNombre($data['montantKm'] ?? '0'));
            $noteFrais->setTotalTransport($this->nettoyerNombre($data['totalTransport'] ?? '0'));
            $noteFrais->setTotalAutre($this->nettoyerNombre($data['totalAutre'] ?? '0'));
            $noteFrais->setTotalGeneral($this->nettoyerNombre($data['totalGeneral'] ?? '0'));
            $noteFrais->setMontantAbandon($this->nettoyerNombre($data['montantAbandon'] ?? '0'));
            $noteFrais->setMontantRembourse($this->nettoyerNombre($data['montantRembourse'] ?? '0'));
            $noteFrais->setIban(!empty($data['iban']) ? $data['iban'] : null);
            $noteFrais->setBic(!empty($data['bic']) ? $data['bic'] : null);
            $noteFrais->setStatut('brouillon');
            $noteFrais->setDateCreation(new \DateTimeImmutable());
            $noteFrais->setBudget($budget);

            $entityManager->persist($noteFrais);
            $entityManager->flush();

            $datesDepense = $data['dateDepense'] ?? [];
            $objetsDepense = $data['objetDepense'] ?? [];
            $kmsDepense = $data['kmDepense'] ?? [];
            $typesBareme = $data['typeBareme'] ?? [];
            $tauxKms = $data['tauxKm'] ?? [];
            $montantsKmLigne = $data['montantKmLigne'] ?? [];
            $transportsDepense = $data['transportDepense'] ?? [];
            $autresDepense = $data['autreDepense'] ?? [];
            $totauxLigne = $data['totalLigne'] ?? [];

            $nombreLignes = max(
                count($datesDepense),
                count($objetsDepense),
                count($kmsDepense),
                count($typesBareme),
                count($tauxKms),
                count($montantsKmLigne),
                count($transportsDepense),
                count($autresDepense),
                count($totauxLigne)
            );

            for ($i = 0; $i < $nombreLignes; $i++) {
                $dateDepense = $datesDepense[$i] ?? null;
                $objetDepense = trim($objetsDepense[$i] ?? '');
                $kilometres = $this->nettoyerNombre($kmsDepense[$i] ?? '0');
                $typeBareme = $typesBareme[$i] ?? null;
                $tauxKm = $this->nettoyerNombre($tauxKms[$i] ?? '0');
                $montantKm = $this->nettoyerNombre($montantsKmLigne[$i] ?? '0');
                $montantTransport = $this->nettoyerNombre($transportsDepense[$i] ?? '0');
                $montantAutre = $this->nettoyerNombre($autresDepense[$i] ?? '0');
                $totalLigne = $this->nettoyerNombre($totauxLigne[$i] ?? '0');

                $ligneVide =
                    empty($dateDepense) &&
                    $objetDepense === '' &&
                    (float) $kilometres === 0.0 &&
                    empty($typeBareme) &&
                    (float) $tauxKm === 0.0 &&
                    (float) $montantKm === 0.0 &&
                    (float) $montantTransport === 0.0 &&
                    (float) $montantAutre === 0.0 &&
                    (float) $totalLigne === 0.0;

                if ($ligneVide) {
                    continue;
                }

                $ligneFrais = new LigneFrais();

                if (!empty($dateDepense)) {
                    $ligneFrais->setDateDepense(new \DateTime($dateDepense));
                }

                $ligneFrais->setObjetDepense($objetDepense !== '' ? $objetDepense : null);
                $ligneFrais->setKilometres($kilometres);
                $ligneFrais->setTypeBareme(!empty($typeBareme) ? $typeBareme : null);
                $ligneFrais->setTauxKm($tauxKm);
                $ligneFrais->setMontantKm($montantKm);
                $ligneFrais->setMontantTransport($montantTransport);
                $ligneFrais->setMontantAutre($montantAutre);
                $ligneFrais->setTotalLigne($totalLigne);
                $ligneFrais->setNoteFrais($noteFrais);

                $entityManager->persist($ligneFrais);
            }

            $dossierUpload = $this->getParameter('kernel.project_dir') . '/public/uploads/justificatifs';
            if (!is_dir($dossierUpload)) {
                mkdir($dossierUpload, 0777, true);
            }

            $cheminsJustificatifs = [];

            if ($fichiers) {
                if (!is_array($fichiers)) {
                    $fichiers = [$fichiers];
                }

                foreach ($fichiers as $fichier) {
                    if (!$fichier) {
                        continue;
                    }

                    $nomOriginal = $fichier->getClientOriginalName();
                    $nomStocke = uniqid() . '-' . preg_replace('/[^A-Za-z0-9.\-_]/', '-', $nomOriginal);
                    $cheminRelatif = 'uploads/justificatifs/' . $nomStocke;
                    $cheminAbsolu = $dossierUpload . '/' . $nomStocke;

                    try {
                        $fichier->move($dossierUpload, $nomStocke);
                    } catch (FileException $e) {
                        dd('erreur upload fichier', $e->getMessage());
                    }

                    $justificatif = new Justificatif();
                    $justificatif->setNomFichier($nomOriginal);
                    $justificatif->setCheminFichier($cheminRelatif);
                    $justificatif->setDateCreation(new \DateTimeImmutable());
                    $justificatif->setNoteFrais($noteFrais);

                    $entityManager->persist($justificatif);

                    $cheminsJustificatifs[] = [
                        'nom' => $nomOriginal,
                        'chemin' => $cheminAbsolu,
                    ];
                }
            }

            $entityManager->flush();

            $lignes = $ligneFraisRepository->findBy(['noteFrais' => $noteFrais], ['id' => 'ASC']);
            $justificatifs = $justificatifRepository->findBy(['noteFrais' => $noteFrais], ['id' => 'ASC']);

            $pdfContent = $this->genererPdf(
                $noteFrais,
                $lignes,
                $justificatifs,
                $data['signatureData'] ?? null
            );

            $email = (new Email())
                ->from('esamecken@gmail.com')
                ->to('esamecken@gmail.com')
                ->subject('Nouvelle note de frais #' . $noteFrais->getId())
                ->text(
                    "Une nouvelle note de frais a été envoyée.\n\n" .
                    "Nom : " . $noteFrais->getNomDemandeur() . "\n" .
                    "Budget : " . $noteFrais->getBudget()?->getNom() . "\n" .
                    "Total : " . $noteFrais->getTotalGeneral()
                )
                ->attach($pdfContent, 'note-frais-' . $noteFrais->getId() . '.pdf', 'application/pdf');

            foreach ($cheminsJustificatifs as $justificatifMail) {
                if (file_exists($justificatifMail['chemin'])) {
                    $email->attachFromPath($justificatifMail['chemin'], $justificatifMail['nom']);
                }
            }

            $mailer->send($email);

            return $this->redirectToRoute('app_note_frais_show', [
                'id' => $noteFrais->getId(),
            ]);
        }

        return $this->render('note_frais/index.html.twig');
    }

    #[Route('/note-de-frais/{id}', name: 'app_note_frais_show', methods: ['GET'])]
    public function show(
        NoteFrais $noteFrais,
        LigneFraisRepository $ligneFraisRepository,
        JustificatifRepository $justificatifRepository
    ): Response {
        $lignes = $ligneFraisRepository->findBy(['noteFrais' => $noteFrais], ['id' => 'ASC']);
        $justificatifs = $justificatifRepository->findBy(['noteFrais' => $noteFrais], ['id' => 'ASC']);

        return $this->render('note_frais/previsualiser.html.twig', [
            'noteFrais' => $noteFrais,
            'lignes' => $lignes,
            'justificatifs' => $justificatifs,
        ]);
    }

    #[Route('/note-de-frais/{id}/pdf', name: 'app_note_frais_pdf', methods: ['GET'])]
    public function pdf(
        NoteFrais $noteFrais,
        LigneFraisRepository $ligneFraisRepository,
        JustificatifRepository $justificatifRepository
    ): Response {
        $lignes = $ligneFraisRepository->findBy(['noteFrais' => $noteFrais], ['id' => 'ASC']);
        $justificatifs = $justificatifRepository->findBy(['noteFrais' => $noteFrais], ['id' => 'ASC']);

        return new Response(
            $this->genererPdf($noteFrais, $lignes, $justificatifs),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="note-frais-' . $noteFrais->getId() . '.pdf"',
            ]
        );
    }

    private function genererPdf(
        NoteFrais $noteFrais,
        array $lignes = [],
        array $justificatifs = [],
        ?string $signatureData = null
    ): string {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = $this->renderView('note_frais/pdf.html.twig', [
            'noteFrais' => $noteFrais,
            'lignes' => $lignes,
            'justificatifs' => $justificatifs,
            'signatureData' => $signatureData,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function nettoyerNombre(?string $valeur): string
    {
        if ($valeur === null || $valeur === '') {
            return '0';
        }

        return str_replace(',', '.', $valeur);
    }

    private function convertirValeurBudget(string $valeur): string
    {
        $budgets = [
            'administratif' => 'Administratif',
            'bibliotheque' => 'Bibliothèque',
            'formation' => 'Formation',
            'matos-explo-speleo-canyon' => 'Matos Explo / Spéléo / Canyon',
            'matos-autre' => 'Matos autre',
            'weekends-sorties' => 'Week-ends et sorties',
        ];

        return $budgets[$valeur] ?? $valeur;
    }
}