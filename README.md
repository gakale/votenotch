# Système de Vote avec Paiement via Notch Pay

Ce projet Laravel intègre un système de vote avec une fonctionnalité de paiement utilisant l'API Notch Pay.

## Fonctionnalités

- Les utilisateurs peuvent voter pour un candidat en effectuant un paiement.
- Le système utilise l'API Notch Pay pour gérer les transactions.

## Installation

1. Cloner le dépôt :

2. Installer les dépendances via Composer :

3. Configurer l'environnement :
- Copier `.env.example` en `.env` et configurer les paramètres de la base de données et autres variables d'environnement.
- Ajouter votre clé API Notch Pay dans `.env` :
  ```
  NOTCHPAY_API_KEY=votre_clé_api
  ```

## Utilisation

1. Les utilisateurs remplissent un formulaire avec leur email et choisissent un candidat.
2. Ils sont redirigés vers Notch Pay pour effectuer le paiement.
3. Une fois le paiement effectué, leur vote est enregistré dans la base de données.

## Sécurité

- Assurez-vous de valider et de nettoyer toutes les entrées utilisateur.
- Ne stockez jamais de clés API ou d'autres informations sensibles directement dans le code.

## Contribution

Les contributions sont les bienvenues. Veuillez créer une issue ou une pull request pour toute contribution.
