<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WhatsAppController extends Controller
{
    private $phoneNumberId;
    private $accessToken;

    public function __construct()
    {
        // À configurer dans votre fichier .env
        $this->phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
        $this->accessToken = env('WHATSAPP_ACCESS_TOKEN');
    }

    /**
     * Vérification du webhook par Meta (étape de configuration obligatoire)
     */
    public function verifyWebhook(Request $request)
    {
        $verifyToken = env('WHATSAPP_VERIFY_TOKEN');

        if ($request->get('hub_mode') === 'subscribe' && $request->get('hub_verify_token') === $verifyToken) {
            return response($request->get('hub_challenge'), 200);
        }

        return response('Token de vérification invalide', 403);
    }

    /**
     * Réception et traitement des messages entrants
     */
    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        // Journaliser pour le débogage
        Log::info('WhatsApp Webhook Data: ', $data);

        // Vérifier si le message contient du texte
        if (isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
            $message = $data['entry'][0]['changes'][0]['value']['messages'][0];
            $from = $message['from']; // Numéro de téléphone de l'utilisateur
            
            if ($message['type'] === 'text') {
                $userText = trim($message['text']['body']);
                
                $this->processChatbotLogic($from, $userText);
            }
        }

        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Logique de l'AgroBot (Gestion d'état simple via le Cache)
     */
    private function processChatbotLogic($to, $text)
    {
        // Clé unique pour suivre l'état de la discussion de ce numéro
        $cacheKey = "user_step_" . $to;
        $step = Cache::get($cacheKey, 'init');

        if ($step === 'init') {
            // L'utilisateur cherche un produit
            Cache::put($cacheKey, 'awaiting_quantity', 300); // Expirera après 5 min
            Cache::put("user_product_" . $to, $text, 300);

            $reply = "Quelle quantité de *" . $text . "* souhaitez-vous acheter ?\n_(Tapez \"passer\" pour voir toutes les offres)_";
            $this->sendWhatsAppMessage($to, $reply);
        } 
        elseif ($step === 'awaiting_quantity') {
            $product = Cache::get("user_product_" . $to, "produit");
            
            // Ici, vous devriez normalement chercher dans votre table `Product` (créée précédemment)
            // $offres = Product::where('name', 'like', "%$product%")->get();

            $reply = "Voici les offres pour *" . $product . "* — quantité demandée : *" . $text . "* :\n\n";
            $reply .= "1. Coopérative Sine Saloum : 15,000 CFA\n2. Groupement Ndoucoumane : 14,500 CFA";

            $this->sendWhatsAppMessage($to, $reply);

            // Réinitialisation de la conversation
            Cache::forget($cacheKey);
            Cache::forget("user_product_" . $to);
        }
    }

    /**
     * Envoi de message via l'API Cloud de Meta
     */
    private function sendWhatsAppMessage($to, $messageText)
    {
        $url = "https://graph.facebook.com/v17.0/{$this->phoneNumberId}/messages";

        $response = Http::withToken($this->accessToken)->post($url, [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $messageText
            ]
        ]);

        if (!$response->successful()) {
            Log::error('Erreur d\'envoi WhatsApp: ' . $response->body());
        }
    }
}