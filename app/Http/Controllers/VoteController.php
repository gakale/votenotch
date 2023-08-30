<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Exception;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request ,  $id)
    {
        $candidate = Candidate::findOrFail($id);  // Récupérer le candidat à partir de l'ID
        $transaction_id = uniqid();
        $apikey = config('cinetpay.apikey');
        $site_id = config('cinetpay.site_id');
        $secret_key = config('cinetpay.secret_key');
        $return_url = route('cinetpay.return');


        $data = [
            'apikey' => $apikey,
            'site_id' => $site_id,
            'secret_key'=> $secret_key,
            'transaction_id' => $transaction_id,
            'amount' => 100, // Montant du vote
            'currency' => 'XOF',
            'description' => 'Vote pour ' . $candidate->name,  // Utiliser le nom du candidat
            'notify_url' => route('cinetpay.notify'),
            'return_url' => $return_url,
            'channels' => 'ALL',
        ];

        // appelle l'API de CinetPay pour générer le paiement
        $response = Http::post('https://api-checkout.cinetpay.com/v2/payment', $data);

        if ($response->successful()) {
            $payment_url = $response->json()['data']['payment_url'];
            return redirect($payment_url);
        } else {
            return back()->with('error', 'Erreur lors de l\'initialisation du paiement.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Notify URL
     */
    public function handleCinetPayNotification(Request $request)
    {
        // Valider les données reçues (ceci est un exemple simple, vous devrez peut-être ajouter des validations supplémentaires)
        $request->validate([
            'transaction_id' => 'required',
            'payment_status' => 'required',
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        // Récupérer les données
        $transaction_id = $request->input('transaction_id');
        $payment_status = $request->input('payment_status');
        $candidate_id = $request->input('candidate_id');

        // Vérifier le statut du paiement
        if ($payment_status == 'ACCEPTED') {
            // Enregistrer le vote dans la base de données
            Vote::create([
                'candidate_id' => $candidate_id,
                'payment_status' => true,
                'payment_reference' => $transaction_id,
            ]);

            // Mettre à jour le nombre de votes pour le candidat
            $candidate = Candidate::find($candidate_id);
            $candidate->increment('votes_count');

            return response()->json(['message' => 'Vote enregistré avec succès']);
        } else {
            // Gérer le cas où le paiement a échoué
            return response()->json(['message' => 'Échec du paiement'], 400);
        }
    }

    public function handleCinetPayReturn(Request $request)
    {
        // Récupérer les données retournées par CinetPay
        $transaction_id = $request->input('transaction_id');

        // Initialisation des paramètres CinetPay
        $apikey = config('cinetpay.apikey');
        $site_id = config('cinetpay.site_id');

        // Créer une instance de la classe CinetPay (ou utiliser une bibliothèque CinetPay si disponible)
        $CinetPay = new CinetPay($site_id, $apikey);

        try {
            // Vérifier l'état de la transaction
            $CinetPay->getPayStatus($transaction_id, $site_id);
            $message = $CinetPay->chk_message;
            $code = $CinetPay->chk_code;

            // Vérifier si la transaction a réussi
            if ($code == '00') {
                // Transaction réussie
                // Mettre à jour la base de données, délivrer le service, etc.
                return redirect()->route('success.page')->with('message', 'Paiement réussi');
            } else {
                // Transaction échouée
                // Mettre à jour la base de données, informer l'utilisateur, etc.
                return redirect()->route('failure.page')->with('message', 'Paiement échoué : ' . $message);
            }
        } catch (Exception $e) {
            // Gérer les exceptions
            return redirect()->route('error.page')->with('message', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Page de succès
     */
    public function successPage()
    {
        return view('success')->with('message', 'Votre paiement a été effectué avec succès.');
    }

    /**
     * Page d'échec
     */
    public function failurePage()
    {
        return view('failure')->with('message', 'Votre paiement a échoué.');
    }

    /**
     * Page d'erreur
     */
    public function errorPage()
    {
        return view('error')->with('message', 'Une erreur est survenue pendant le processus de paiement.');
    }


}
