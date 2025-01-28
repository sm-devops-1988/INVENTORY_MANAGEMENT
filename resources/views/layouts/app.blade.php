<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-boxes"></i> Gestion d'inventaire
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventories.index') }}">
                            <i class="fas fa-box-open"></i> Inventaires
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="inventoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-clipboard-list"></i> Résultats d'inventaire
                        </a>
                        <div class="dropdown-menu" aria-labelledby="inventoryDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-list"></i> Inventaire libre
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-store"></i> Inventaire par magasin
                            </a>
                            <a class="dropdown-item" href="{{ route('StoreInventoryItem.index') }}">
                                <i class="fas fa-tasks"></i> Inventaire unifié
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="fas fa-users"></i> Utilisateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('stores.index') }}">
                            <i class="fas fa-store"></i> Magasins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Charger jQuery en premier -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Ensuite, Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Ensuite, DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Enfin, votre script personnalisé -->
    <script>
        jQuery(document).ready(function() {
            jQuery('#inventoriesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json", // Traduction en français
                    "search": "Rechercher :", // Texte de la barre de recherche
                    "lengthMenu": "Afficher _MENU_ entrées", // Texte du menu déroulant
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées", // Informations sous le tableau
                    "paginate": {
                        "first": "Première", // Bouton "Première"
                        "last": "Dernière", // Bouton "Dernière"
                        "next": "Suivant", // Bouton "Suivant"
                        "previous": "Précédent" // Bouton "Précédent"
                    }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [3] } // Désactiver le tri sur la colonne "Actions" (index 3)
                ],
                "order": [[2, "desc"]] // Trier par défaut par la colonne "Créé le" (ordre décroissant)
            });
        });
    </script>
</body>

</html>