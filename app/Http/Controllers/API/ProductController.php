<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    // Données fictives issues de votre maquette
    private function getMockProducts() {
        return [
            [
                'id' => 1,
                'name' => 'Tomate fraîche',
                'price' => 1000,
                'unit' => 'kg',
                'description' => 'Tomate fraîche et naturelle, cultivée sans produits chimiques.',
                'image_url' => 'https://images.unsplash.com/photo-1595855759920-86582396756a?w=500',
                'status' => 'En stock',
                'vendor_name' => 'Ferme de la Plaine'
            ],
            [
                'id' => 2,
                'name' => 'Oignon rouge',
                'price' => 800,
                'unit' => 'kg',
                'description' => 'Oignons rouges frais et croquants.',
                'image_url' => 'https://images.unsplash.com/photo-1618512496248-a07fe8376604?w=500',
                'status' => 'En stock',
                'vendor_name' => 'Ferme de la Plaine'
            ],
            [
                'id' => 3,
                'name' => 'Carotte',
                'price' => 700,
                'unit' => 'kg',
                'description' => 'Carottes riches en vitamines.',
                'image_url' => 'https://images.unsplash.com/photo-1598170845058-32b996a6957b?w=500',
                'status' => 'En stock',
                'vendor_name' => 'Ferme de la Plaine'
            ],
            [
                'id' => 4,
                'name' => 'Chou vert',
                'price' => 600,
                'unit' => 'pièce',
                'description' => 'Chou vert bio bien pommé.',
                'image_url' => 'https://images.unsplash.com/photo-1581074817932-af423ba4566e?w=500',
                'status' => 'En stock',
                'vendor_name' => 'Ferme de la Plaine'
            ]
        ];
    }

    // Écran 2 : Récupérer tous les produits
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getMockProducts()
        ], 200)->header('Access-Control-Allow-Origin', '*'); 
        // Le header évite les blocages CORS avec votre fichier index.html
    }

    // Écran 3 : Récupérer le détail d'un produit spécifique
    public function show($id): JsonResponse
    {
        $products = $this->getMockProducts();
        $product = collect($products)->firstWhere('id', $id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }

        return response()->json(['success' => true, 'data' => $product], 200)->header('Access-Control-Allow-Origin', '*');
    }
}