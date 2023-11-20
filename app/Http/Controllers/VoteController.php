<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use CinetPay\CinetPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
    public function store(Request $request, $id)
{
    // recuperer les donnees du formulaire
    $number = $request->input('number');
    $email = $request->input('email');
    $amount = $request->input('amount');

    // Récupérer le candidat à partir de l'ID
    $candidate = Candidate::findOrFail($id);


    // Enregistrer le vote dans la base de données
    Vote::create([
        'candidate_id' => $id,
        'payment_status' => false,
        'payment_reference' => null,
        'number' => $number,
        'email' => $email,
        // ... Autres champs ...
    ]);

    // Préparation des données pour Notch Pay
    $fields = [
        'email' => $email,
        'amount' => $amount, // Ou autre montant selon la logique de votre application
        'currency' => 'XOF',
        'number'=> $number,
        // ... Autres champs nécessaires ...
    ];

    // Envoi de la requête à Notch Pay
    $client = new \GuzzleHttp\Client();
    $response = $client->post('https://api.notchpay.co/payments/initialize', [
        'headers' => [
            'Authorization' => env('NOTCHPAY_API_KEY'),
            'Accept' => 'application/json',
        ],
        'json' => $fields
    ]);

    if ($response->getStatusCode() == 201) {
        $data = json_decode($response->getBody(), true);
        return redirect($data['authorization_url']);
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
   

    /**
     * Return URL
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * ToDo: Le traitement d'une transaction réussie ou échouée ne doit pas se faire dans cette méthode, elle doit plutôt se faire uniquement dans la méthode handleCinetPayNotification (Avec verification au niveau de la base de données et de CinetPay)
     */


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
