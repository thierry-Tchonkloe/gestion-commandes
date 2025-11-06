# 🛒 Application de Gestion de Commandes

## 📝 Description

Application full-stack de gestion de commandes produits construite avec Laravel (backend API), Vue.js (frontend), et MySQL/PostgreSQL.

### 🎯 Fonctionnalités principales

- ✅ Authentification utilisateur (inscription, connexion, déconnexion)
- ✅ Gestion complète des produits (CRUD)
- ✅ Système de panier d'achat
- ✅ Création et suivi des commandes
- ✅ Pagination et recherche de produits
- ✅ Gestion des stocks avec transactions atomiques
- ✅ Validation des données côté serveur et client
- ✅ Tests unitaires et d'intégration

---

## 🏗️ Architecture

```
order-management-app/
├── backend/          # API Laravel
│   ├── app/
│   ├── database/
│   ├── routes/
│   └── tests/
└── frontend/         # SPA Vue.js
    ├── src/
    └── tests/
```

### Stack technique

**Backend:**
- Laravel 10+
- Laravel Sanctum (authentification JWT)
- MySQL
- PHPUnit (tests)

**Frontend:**
- Vue.js 3 (Composition API)
- Pinia (state management)
- Vue Router
- Vitest (tests)

---

## 🚀 Installation

### Prérequis

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL
### Installation du Backend

```bash
# 1. Cloner le repository
git clone <repository-url>
cd gestion-commandes/backend

# 2. Installer les dépendances
composer install

# 3. Copier le fichier d'environnement
cp .env.example .env

# 4. Générer la clé d'application
php artisan key:generate

# 5. Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion-commandes
DB_USERNAME=root
DB_PASSWORD=

# 6. Créer la base de données
mysql -u root -p -e "CREATE DATABASE gestion-commandes"

# 7. Lancer les migrations
php artisan migrate


# 9. Démarrer le serveur
php artisan serve
```

Le backend sera accessible sur `http://localhost:8000`

### Installation du Frontend

```bash
# 1. Naviguer vers le dossier frontend
cd ../frontend

# 2. Installer les dépendances
npm install

# 3. Configurer l'URL de l'API (si différente)
# Modifier src/services/api.ts ligne 1

# 4. Démarrer le serveur de développement
npm run dev
```

Le frontend sera accessible sur `http://localhost:5173`

---

## 📚 API Documentation

### Authentification

#### Register
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user"
  },
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

### Produits

#### Liste des produits (paginée)
```http
GET /api/products?page=1&search=laptop
Authorization: Bearer {token}
```

#### Créer un produit
```http
POST /api/products
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Laptop Dell XPS 13",
  "sku": "DELL-XPS-13-2024",
  "price": 1299.99,
  "stock": 50,
  "description": "Ultrabook performant"
}
```

#### Mettre à jour un produit
```http
PUT /api/products/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Laptop Dell XPS 13 Updated",
  "sku": "DELL-XPS-13-2024",
  "price": 1199.99,
  "stock": 45,
  "description": "Ultrabook performant - Prix réduit"
}
```

#### Supprimer un produit
```http
DELETE /api/products/{id}
Authorization: Bearer {token}
```

### Commandes

#### Liste des commandes de l'utilisateur
```http
GET /api/orders?page=1
Authorization: Bearer {token}
```

#### Créer une commande
```http
POST /api/orders
Authorization: Bearer {token}
Content-Type: application/json

{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    },
    {
      "product_id": 3,
      "quantity": 1
    }
  ]
}
```

**Response:**
```json
{
  "id": 1,
  "user_id": 1,
  "total": 2599.98,
  "status": "PENDING",
  "created_at": "2024-11-06T10:30:00.000000Z",
  "items": [
    {
      "id": 1,
      "product_id": 1,
      "quantity": 2,
      "unit_price": 1299.99,
      "product": {
        "id": 1,
        "name": "Laptop Dell XPS 13",
        "sku": "DELL-XPS-13-2024"
      }
    }
  ]
}
```

#### Détail d'une commande
```http
GET /api/orders/{id}
Authorization: Bearer {token}
```

---

## 🧪 Tests

### Tests Backend (PHPUnit)

```bash
cd backend

# Lancer tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter AuthenticationTest
php artisan test --filter ProductTest
php artisan test --filter OrderTest

# Avec couverture de code
php artisan test --coverage
```

**Exemples de tests:**

```php
// tests/Feature/AuthenticationTest.php
public function test_user_can_register()
{
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['user', 'token']);
}

// tests/Feature/OrderTest.php
public function test_order_creation_decrements_stock()
{
    $product = Product::factory()->create(['stock' => 10]);
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->postJson('/api/orders', [
        'items' => [
            ['product_id' => $product->id, 'quantity' => 3]
        ]
    ]);

    $response->assertStatus(201);
    $this->assertEquals(7, $product->fresh()->stock);
}
```

### Tests Frontend (Vitest)

```bash
cd frontend

# Lancer tous les tests
npm run test:unit

# Mode watch
npm run test:unit -- --watch

# Avec couverture
npm run test:unit -- --coverage
```

**Exemples de tests:**

```javascript
// tests/unit/ProductCard.spec.js
import { mount } from '@vue/test-utils'
import ProductCard from '@/components/ProductCard.vue'

describe('ProductCard', () => {
  it('renders product information correctly', () => {
    const product = {
      id: 1,
      name: 'Test Product',
      sku: 'TEST-001',
      price: 99.99,
      stock: 10
    }
    
    const wrapper = mount(ProductCard, {
      props: { product }
    })
    
    expect(wrapper.text()).toContain('Test Product')
    expect(wrapper.text()).toContain('TEST-001')
    expect(wrapper.text()).toContain('$99.99')
  })
})
```

---

## 🗄️ Modèle de données

### Users
```sql
- id: bigint (PK)
- name: varchar(255)
- email: varchar(255) UNIQUE
- password: varchar(255)
- role: varchar(50) DEFAULT 'user'
- created_at: timestamp
- updated_at: timestamp
```

### Products
```sql
- id: bigint (PK)
- name: varchar(255)
- sku: varchar(100) UNIQUE
- price: decimal(10,2)
- stock: int
- description: text
- created_at: timestamp
- updated_at: timestamp
```

### Orders
```sql
- id: bigint (PK)
- user_id: bigint (FK → users.id)
- total: decimal(10,2)
- status: enum('PENDING','PAID','CANCELLED')
- created_at: timestamp
- updated_at: timestamp
```

### Order_items
```sql
- id: bigint (PK)
- order_id: bigint (FK → orders.id)
- product_id: bigint (FK → products.id)
- quantity: int
- unit_price: decimal(10,2)
- created_at: timestamp
- updated_at: timestamp
```

---

## 🎨 Screenshots

### Page de connexion
![Login](docs/screenshots/login.png)

### Liste des produits
![Products](docs/screenshots/products.png)

### Panier
![Cart](docs/screenshots/cart.png)

### Commandes
![Orders](docs/screenshots/orders.png)

---

## 🔐 Sécurité

### Mesures implémentées

1. **Authentification**
   - Tokens JWT via Laravel Sanctum
   - Mots de passe hashés avec bcrypt
   - Protection CSRF

2. **Validation**
   - Validation côté serveur avec FormRequest
   - Validation côté client
   - Sanitisation des entrées

3. **Autorisation**
   - Middleware auth:sanctum
   - Vérification de propriété des ressources
   - Rôles utilisateurs (admin/user)

4. **Base de données**
   - Transactions atomiques pour les commandes
   - Contraintes d'intégrité référentielle
   - Index pour optimisation

5. **API**
   - Rate limiting
   - CORS configuré
   - Headers de sécurité

---

## 🚀 Fonctionnalités bonus implémentées

### ✅ Système de rôles
- Distinction admin/user
- Middleware de vérification des rôles
- Interface admin pour gestion produits

### ✅ Import CSV
```bash
php artisan products:import products.csv
```

### ✅ Docker
```bash
docker-compose up -d
```

### ✅ Documentation API (Swagger)
Accessible sur `/api/documentation`

---

## 📊 Choix techniques

### Backend

**Laravel Sanctum vs Passport**
- Choix: **Sanctum** pour sa simplicité et sa légèreté
- Parfait pour les SPA
- Moins de configuration que Passport

**Transactions DB**
- Utilisation de `DB::transaction()` pour garantir l'atomicité
- `lockForUpdate()` pour éviter les race conditions sur le stock

**Service Layer**
- `OrderService` pour isoler la logique métier complexe
- Facilite les tests unitaires
- Meilleure maintenabilité

### Frontend

**Pinia vs Vuex**
- Choix: **Pinia** pour sa simplicité
- Meilleure intégration TypeScript
- API plus intuitive

**Composition API vs Options API**
- Choix: **Composition API** pour:
  - Meilleure réutilisabilité du code
  - Performance optimisée
  - Syntaxe moderne

---

## 🐛 Debugging

### Backend

```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Debug DB queries
php artisan telescope:install

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Frontend

```bash
# Vue DevTools
# Installer l'extension Chrome/Firefox

# Debug mode
npm run dev -- --debug
```

---

## 📈 Performance

### Optimisations implémentées

1. **Database**
   - Index sur colonnes fréquemment recherchées
   - Eager loading (`with()`) pour éviter N+1
   - Pagination pour limiter les résultats

2. **API**
   - Cache des réponses fréquentes
   - Compression gzip
   - Rate limiting

3. **Frontend**
   - Lazy loading des routes
   - Debounce sur la recherche
   - Virtual scrolling pour grandes listes

---

## 🔄 CI/CD

### GitHub Actions

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  laravel-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Run tests
        run: php artisan test

  vue-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Run tests
        run: npm run test:unit
```

---

## 📝 TODO / Améliorations futures

- [ ] Notifications en temps réel (Laravel Echo + Pusher)
- [ ] Export PDF des commandes
- [ ] Historique des modifications de stock
- [ ] Dashboard analytique
- [ ] Mode sombre
- [ ] Internationalisation (i18n)
- [ ] PWA (Progressive Web App)
- [ ] Paiement en ligne (Stripe)

---

## 👥 Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

---

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---

## 👨‍💻 Auteur

**Votre Nom**
- GitHub: [@votre-username](https://github.com/votre-username)
- Email: votre.email@example.com

---

## 🙏 Remerciements

- Laravel Framework
- Vue.js Team
- Communauté open source

---

## 📞 Support

Pour toute question ou problème:
- Ouvrir une issue sur GitHub
- Email: support@example.com
- Documentation: https://docs.example.com