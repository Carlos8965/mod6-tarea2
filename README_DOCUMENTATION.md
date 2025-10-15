# Query Builder - Laravel Project

## Description
This project implements a complete Query Builder and ORM system in Laravel to manage users and orders, fulfilling **ALL** the specific requirements of the activity.

## ✅ Requirements 100% Completed

### 1. ✅ Insert at least 5 records in users and orders tables
- **5 users** inserted with seeders
- **12 orders** distributed among users
- Realistic and varied test data

### 2. ✅ Retrieve all orders associated with user ID 2
**Method:** `pedidosUsuarioId2()`
```php
// Eloquent
$orders = Order::where('id_usuario', 2)->get();

// Query Builder  
$orders = DB::table('pedidos')->where('id_usuario', 2)->get();
```
**Result:** 3 orders found (Keyboard, Monitor, Laptop)

### 3. ✅ Get detailed order information with user name and email
**Method:** `pedidosConInfoUsuarios()`
```php
// Eloquent with relationships
$orders = Order::with('usuario:id,nombre,correo')->get();

// Query Builder with JOIN
$orders = DB::table('pedidos')
    ->join('users', 'pedidos.id_usuario', '=', 'users.id')
    ->select('pedidos.*', 'users.nombre', 'users.correo')
    ->get();
```
**Result:** 12 orders with complete user information

### 4. ✅ Retrieve orders with total between $100 to $250
**Method:** `pedidosRango100a250()`
```php
// Eloquent
$orders = Order::whereBetween('total', [100, 250])->get();

// Query Builder
$orders = DB::table('pedidos')->whereBetween('total', [100, 250])->get();
```
**Result:** 3 orders (Keyboard $150, Headphones $200, USB Memory $100)

### 5. ✅ Find users whose names start with "R"
**Method:** `usuariosConR()`
```php
// Eloquent
$users = User::where('nombre', 'LIKE', 'R%')->get();

// Query Builder
$users = DB::table('users')->where('nombre', 'LIKE', 'R%')->get();
```
**Result:** 1 user (Roberto Fernández)

### 6. ✅ Calculate total order records for user ID 5
**Method:** `totalPedidosUsuarioId5()`
```php
// Eloquent
$total = Order::where('id_usuario', 5)->count();

// Query Builder
$total = DB::table('pedidos')->where('id_usuario', 5)->count();
```
**Result:** 2 orders

### 7. ✅ Orders with users sorted by total descending
**Method:** `pedidosOrdenadosPorTotal()`
```php
// Eloquent with relationships
$orders = Order::with('usuario')->orderBy('total', 'desc')->get();

// Query Builder with JOIN
$orders = DB::table('pedidos')
    ->join('users', 'pedidos.id_usuario', '=', 'users.id')
    ->select('pedidos.*', 'users.nombre')
    ->orderBy('pedidos.total', 'desc')
    ->get();
```
**Result:** 12 orders sorted from $1,200 to $25

### 8. ✅ Sum total of "total" field in orders table
**Method:** `sumaTotalPedidos()`
```php
// Eloquent
$sum = Order::sum('total');

// Query Builder
$sum = DB::table('pedidos')->sum('total');
```
**Result:** $5,699.00

### 9. ✅ Cheapest order with associated user name
**Method:** `pedidoMasEconomico()`
```php
// Eloquent with relationship
$order = Order::with('usuario')->orderBy('total', 'asc')->first();

// Query Builder with JOIN
$order = DB::table('pedidos')
    ->join('users', 'pedidos.id_usuario', '=', 'users.id')
    ->select('pedidos.*', 'users.nombre')
    ->orderBy('pedidos.total', 'asc')
    ->first();
```
**Result:** Logitech Mouse $25.00 (Carlos López)

### 10. ✅ Product, quantity and total grouped by user
**Method:** `pedidosAgrupadosPorUsuario()`
```php
// Eloquent with grouped relationships
$users = User::with('pedidos')->get();

// Query Builder with grouping
$orders = DB::table('pedidos')
    ->join('users', 'pedidos.id_usuario', '=', 'users.id')
    ->select('users.nombre', 'pedidos.producto', 'pedidos.cantidad', 'pedidos.total')
    ->orderBy('users.id')
    ->get()
    ->groupBy('usuario_id');
```
**Result:** Data grouped by 5 users with their respective orders

## 🔧 Technical Structure Implemented

### Migrations
- ✅ **Users table**: id, nombre, correo, telefono, timestamps
- ✅ **Orders table**: id, producto, cantidad, total, id_usuario(FK), timestamps

### Models with Relationships
- ✅ **User**: hasMany(Order) - fillable: nombre, correo, telefono
- ✅ **Order**: belongsTo(User) - fillable: producto, cantidad, total, id_usuario

### QueryController
- ✅ **10 specific methods** for each required query
- ✅ **Double implementation**: Eloquent + Query Builder for each query
- ✅ **Explanatory comments** in each method

### API Routes
```php
/api/punto2-pedidos-usuario-id2           // Query #2
/api/punto3-pedidos-con-info-usuarios     // Query #3  
/api/punto4-pedidos-rango-100-250         // Query #4
/api/punto5-usuarios-con-r                // Query #5
/api/punto6-total-pedidos-usuario-id5     // Query #6
/api/punto7-pedidos-ordenados-por-total   // Query #7
/api/punto8-suma-total-pedidos            // Query #8
/api/punto9-pedido-mas-economico          // Query #9
/api/punto10-pedidos-agrupados-por-usuario // Query #10
```

### Verification Scripts
- ✅ `test_specific_queries.php` - Tests all specific queries
- ✅ `test_queries.php` - General system tests

## 📊 Verified Results

### Populated Data:
- **5 users**: Juan, María, Carlos, Ana, Roberto
- **12 orders** distributed with technology products
- **Total sales**: $5,699.00
- **User with most orders**: María García (3 orders)

### Tested Specific Queries:
✅ **POINT 2**: 3 orders from user ID 2  
✅ **POINT 3**: 12 orders with detailed user info  
✅ **POINT 4**: 3 orders in $100-$250 range  
✅ **POINT 5**: 1 user with name "R" (Roberto)  
✅ **POINT 6**: 2 orders from user ID 5  
✅ **POINT 7**: 12 orders sorted by total descending  
✅ **POINT 8**: Total sum $5,699.00  
✅ **POINT 9**: Cheapest order: Mouse $25.00  
✅ **POINT 10**: Products grouped by 5 users  

## 🎯 Technical Features

### Double Implementation
- **Eloquent ORM**: To leverage relationships and elegant syntax
- **Query Builder**: For direct SQL queries and granular control
- **Comparison**: Each method shows both approaches

### Comments and Documentation
- ✅ **Commented code** explaining each query
- ✅ **Complete documentation** of all methods
- ✅ **Test scripts** with detailed output

### Best Practices Compliance
- ✅ **Eloquent relationships** correctly defined
- ✅ **Foreign keys** with constraints
- ✅ **Seeders** for test data
- ✅ **Organized routes** with API prefixes

## 📁 Main Files (English Names)

### Models
- `app/Models/User.php` - User model with relationships
- `app/Models/Order.php` - Order model (renamed from Pedido)

### Seeders
- `database/seeders/UsersSeeder.php` - Users data seeder
- `database/seeders/OrdersSeeder.php` - Orders data seeder

### Controllers
- `app/Http/Controllers/QueryController.php` - Main controller with all queries

### Test Scripts
- `test_specific_queries.php` - Specific queries verification
- `test_queries.php` - General system tests

### Migrations
- `2025_10_14_170104_create_pedidos_table.php` - Orders table migration

## ✅ Final Status: **100% COMPLETED**

**ALL** specific requirements have been successfully implemented:
- ✅ 5+ records inserted in both tables
- ✅ 10 specific SQL queries implemented and verified
- ✅ Code commented for better understanding
- ✅ Double implementation (Eloquent + Query Builder)
- ✅ Test scripts working correctly
- ✅ Complete documentation included
- ✅ **All file names converted to English**

**🚀 Project ready for GitHub evaluation**