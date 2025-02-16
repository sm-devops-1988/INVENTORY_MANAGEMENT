<?php
 
namespace App\Http\Controllers\API;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
 
class AuthController extends Controller
{
    /**
     * Authentifie l'utilisateur et génère un token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
 
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Les informations de connexion sont incorrectes.'], 401);
        }
 
        // Fetch the authenticated user with the store relationship
        $user = Auth::user()->load('store');
 
        // Generate a token for the user
        $token = $user->createToken('API Token')->plainTextToken;
 
        // Return the token and user data, including the store name
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'store_id' => $user->store_id,
                'store_name' => $user->store->name, // Include the store name
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }
 
    /**
     * Déconnexion de l'utilisateur.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
 
        return response()->json(['message' => 'Déconnexion réussie.']);
    }
 
    /**
     * Récupère les informations de l'utilisateur connecté.
     */
    public function user(Request $request)
    {
        // Fetch the authenticated user with the store relationship
        $user = $request->user()->load('store');
 
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'store_id' => $user->store_id,
            'store_name' => $user->store->name, // Include the store name
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }
}