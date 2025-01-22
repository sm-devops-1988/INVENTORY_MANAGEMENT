<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;  // Ajoutez cette ligne

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | Ce contrôleur gère l'inscription des nouveaux utilisateurs ainsi que
    | leur validation et création. Nous allons gérer l'authentification et la
    | réponse JSON manuellement ici.
    |
    */

    /**
     * Où rediriger les utilisateurs après leur inscription.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Crée une nouvelle instance du contrôleur.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Obtenir un validateur pour la requête d'inscription entrante.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'store_id' => ['required', 'exists:stores,id'], // Assurez-vous que store_id existe dans la table stores
        ]);
    }

    /**
     * Crée un nouvel utilisateur après une inscription valide.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
{
    // Créer un utilisateur
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'store_id' => $data['store_id'],
    ]);

    // Authentifier l'utilisateur après l'inscription
    Auth::login($user); // Authentifier l'utilisateur

    // Créer un jeton API pour l'utilisateur après l'enregistrement
    $token = $user->createToken('API Token')->plainTextToken;

    // Retourner l'utilisateur et le token
    return [$user, $token];
}

    /**
     * Afficher le formulaire d'inscription.
     *
     * @return \Illuminate\View\View
     */
    protected function showRegistrationForm()
    {
        $stores = Store::all(); // Récupérer tous les magasins
        return view('auth.register', ['stores' => $stores]);
    }

    /**
     * Gère l'inscription de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
{
    // Valider les données
    $this->validator($request->all())->validate();

    // Créer l'utilisateur et obtenir le token
    list($user, $token) = $this->create($request->all());

    // Si la requête est faite via l'API (JSON), retourner la réponse JSON avec le token
    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Utilisateur inscrit avec succès',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Si la requête est faite via un navigateur, rediriger vers la page d'accueil avec un message
    return redirect()->route('home')->with([
        'message' => 'Utilisateur inscrit avec succès',
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
}

}
