<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Rendu du Projet ToDo List (Laravel 5.8)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        h1, h2, h3, h4 {
            color: #333;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 4px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<h1>Compte Rendu du Projet ToDo List</h1>

<h2>Introduction</h2>
<p>
    Ce projet est une application de gestion de tâches (ToDo List) développée avec Laravel 5.8, car vous avez demandé d'utiliser la commande make:auth, qui n'existe pas dans les versions récentes. Il utilise PHP 7.1 et MySQL 5.7. L'objectif de ce projet est de fournir une interface utilisateur (UI) pour gérer les tâches, ainsi qu'une API REST pour permettre des opérations CRUD (Create, Read, Update, Delete) sur les tâches.    </p>

<h2>Technologies Utilisées</h2>
<ul>
    <li><strong>Laravel</strong>: Framework PHP pour une structure MVC et une gestion rapide des fonctionnalités.</li>
    <li><strong>PHP</strong>: Version 7.1, compatible avec Laravel 5.8.</li>
    <li><strong>MySQL</strong>: Base de données relationnelle pour stocker les tâches.</li>
</ul>

<h2>Installation et Configuration</h2>
<ol>
    <li><strong>Création du projet Laravel</strong><br>
        La création du projet s’est faite via Composer :
        <code>composer create-project --prefer-dist laravel/laravel="5.8.*" todolist</code>
    </li>
    <li><strong>Configuration de la Base de Données</strong><br>
        Une base de données nommée <code>todolist</code> a été créée dans MySQL et connectée au projet via le fichier <code>.env</code>.
    </li>
    <li><strong>Authentification</strong><br>
        Afin d’activer l’authentification, j'ai utilisé les commandes Laravel :
        <code>php artisan make:auth</code><br>
        <code>php artisan migrate</code>
    </li>
</ol>

<h2>Structure du Projet et Fonctionnalités</h2>
<ol>
    <li><strong>Modèle Task</strong><br>
        Pour représenter les tâches, j'ai créé un modèle <code>Task</code> avec migration :
        <code>php artisan make:model Task -m</code><br>
        Ensuite, j'ai exécuté une migration personnalisée pour créer la table des tâches :
        <code>php artisan migrate</code>
    </li>
    <li><strong>Contrôleur TaskController</strong><br>
        J'ai créé un contrôleur de ressources pour gérer les opérations CRUD de l’interface utilisateur et de l'API REST :
        <code>php artisan make:controller TaskController --resource</code>
    </li>
    <li><strong>Migration et Structure de la Table Tasks</strong><br>
        La table <code>tasks</code> contient les colonnes <code>id</code>, <code>title</code>, <code>description</code>, <code>completed</code> (booléen indiquant si la tâche est terminée), ainsi que les colonnes de timestamps pour enregistrer les dates de création et de modification.<br>
        La migration a été configurée pour créer cette structure, et la commande <code>php artisan migrate</code> a permis d’exécuter le tout.
    </li>
    <li><strong>Interface Utilisateur</strong><br>
        Étant donné que vous avez demandé l'utilisation de <code>make:auth</code> et des interfaces, j'ai intégré Laravel monolithique avec Blade pour générer les vues de l’application. Ces vues permettent aux utilisateurs de s'authentifier, de voir, ajouter, éditer et supprimer des tâches.
    </li>
        <li><strong>Création du contrôleur ExportController</strong><br>
            Commande utilisée :
            <code>php artisan make:controller ExportController --resource</code>
        </li>
        <li><strong>Packages utilisés pour l'export</strong><br>
            Pour gérer l'exportation des fichiers, j'ai utilisé les packages suivants :
            <ul>
                <li><strong>PDF</strong>: <code>barryvdh/laravel-dompdf</code> - Ce package est utilisé pour générer des fichiers PDF à partir de vues HTML.
                    <br>Commande d'installation :
                    <code>composer require barryvdh/laravel-dompdf</code>
                    <code>php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"</code> (pour publier les configurations du package)

                </li>
                <li><strong>Excel et CSV</strong>: <code>maatwebsite/excel</code> - Ce package permet d'exporter des fichiers Excel et CSV.
                    <br>Commande d'installation :
                    <code>composer require maatwebsite/excel</code>
                    <code>php artisan make:factory UserFactory --model=User</code>
                </li>
            </ul>
        </li>
    <li><strong>API RESTful</strong><br>
        J'ai également créé des routes d'API pour exposer les opérations CRUD de <code>Task</code> en REST. Les routes sont définies dans le fichier <code>routes/api.php</code>, et elles suivent les standards REST.<br>
        Exemple de routes CRUD:

        <code> Route::get('/tasks/{idUser}', [TaskApiController::class, 'index']);</code><br>
        <code>    Route::delete('/tasks/{id}', [TaskApiController::class, 'destroy']);
        </code><br>
        <code>    Route::post('/tasks', [TaskApiController::class, 'store']);
        </code><br>
        <code>    Route::get('/tasks/findOne/{id}', [TaskApiController::class, 'show']);
        </code><br>
        <code>    Route::put('/tasks/{id}', [TaskApiController::class, 'update']);
        </code>
    </li>
</ol>

<h2>Recherche Libre</h2>
<li>
    Ajout d'une fonctionnalité de recherche libre permettant à l'utilisateur de rechercher des tâches par titre ou par description.
</li><li>
    Ajout d'une fonctionnalité permettant de réinitialiser la recherche.      </li>

<h2>Test Unitaire</h2>

<li>
    Ajouter des tests unitaires pour garantir que toutes les fonctionnalités du projet fonctionnent correctement et éviter les régressions lors de futures mises à jour.
</li>

<h2>Améliorations et Propositions</h2>
<li><strong>Pagination</strong><br>
    On peut Ajouter la pagination sur la liste des tâches pour améliorer les performances lorsque le nombre de tâches augmente.
</li>

<h2>Interface</h2>
<li><strong>Page Login</strong><br>
    <div style="text-align:center">
        <img style="width:70%" src="{{ public_path('login.png') }}">    </div>
</li>
<li><strong>Page Register</strong><br>
    <div style="text-align:center">
        <img style="width:70%" src="{{ public_path('registre.png') }}">
    </div>
</li><li><strong>Page List Taches</strong><br>
    <div style="text-align:center">
        <img style="width:70%" src="{{ public_path('home.png') }}">
    </div>
</li><li><strong>Page Ajouter tache</strong><br>
    <div style="text-align:center">
        <img style="width:70%" src="{{ public_path('add.png') }}">
    </div>
</li>
</body>
</html>
