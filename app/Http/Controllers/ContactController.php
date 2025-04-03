<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Affiche la page de contact
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Traite le formulaire de contact
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Envoyer un email (à configurer selon tes besoins)
        // Mail::to('contact@tourisme-benin.com')->send(new ContactFormMail($validated));

        return redirect()->route('contact.success')->with('success', 'Votre message a été envoyé avec succès!');
    }

    /**
     * Affiche la page de succès après l'envoi du formulaire
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        return view('contact.success');
    }
}