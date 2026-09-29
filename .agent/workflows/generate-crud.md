# Workflow: Generate CRUD

This workflow is designed to automate the creation of a complete CRUD (Create, Read, Update, Delete) module for a new entity within the Tanjung Jaya Corporation project. It ensures consistency with the existing project architecture, coding standards, and UI/UX guidelines.

## Inputs
- **Entity Name**: The singular, TitleCase name of the entity (e.g., `Product`, `CustomerOrder`).
- **Attributes**: A list of attributes (columns) with their data types (e.g., `name:string`, `description:text`, `price:integer`).

## Steps

### 1. Database Migration & Model
Generate the model and migration using Laravel Artisan.
```bash
php artisan make:model {Entity} -m
```
- **Migration**: Define the table schema in `database/migrations/..._create_{entities}_table.php` based on the provided attributes. Ensure you include `$table->id()` and `$table->timestamps()`.
- **Model**: In `app/Models/{Entity}.php`:
  - Define `protected $fillable = [...]` or `protected $guarded = ['id'];`.
  - Define any necessary relationships (e.g., `belongsTo`, `hasMany`).

### 2. Form Requests
Generate dedicated Form Requests for validation to keep controllers clean.
```bash
php artisan make:request Store{Entity}Request
php artisan make:request Update{Entity}Request
```
- In both request classes, set `authorize()` to return `true` (or implement specific role logic if required).
- In `rules()`, define the validation rules for the entity's attributes. Use `unique:{table},name,'.$this->route('{entity}')` for updates if applicable.

### 3. Controller
Generate the controller inside the Admin namespace.
```bash
php artisan make:controller Admin/{Entity}Controller
```
- Inject the form requests into the `store` and `update` methods.
- **index()**: Fetch data (use pagination or `get()`), return `view('admin.{entities}.index', compact('{entities}'))`.
- **create()**: Return `view('admin.{entities}.create')`.
- **store(Store{Entity}Request $request)**: Create the record, redirect to `index` with a success message (`->with('success', '...')`).
- **show($id)**: Fetch the record, return `view('admin.{entities}.show', compact('{entity}'))`.
- **edit($id)**: Fetch the record, return `view('admin.{entities}.edit', compact('{entity}'))`.
- **update(Update{Entity}Request $request, $id)**: Update the record, redirect to `index` with a success message.
- **destroy($id)**: Delete the record, redirect to `index` with a success message. (Note: Check for relational constraints before deleting, e.g., if a category has products, prevent deletion and return an error message).

### 4. Routes
Register the resource route in `routes/web.php` under the Admin middleware and prefix group.
```php
// In routes/web.php inside the Admin prefix group:
use App\Http\Controllers\Admin\{Entity}Controller as Admin{Entity}Controller;

Route::resource('{entities}', Admin{Entity}Controller::class);
```

### 5. Views (UI/UX Guidelines)
Create the views in `resources/views/admin/{entities}/`.
All views must extend `<x-app-layout>` and use `<x-slot name="header">` for the page title. 
Use the project's standard TailwindCSS styling (e.g., `bg-emerald-600` for primary buttons, `slate` for text/borders).

#### `index.blade.php`
- Display a header with title and a "Tambah {Entity}" button linking to `create`.
- Show success/error flash messages using existing alert components or standard HTML.
- Display a data table. Use `bg-white rounded-2xl shadow-sm border border-slate-200` for the table container.
- Include "Edit" and "Hapus" (Delete) buttons in the action column. Delete buttons must use a form with `@method('DELETE')` and a confirmation prompt.
- Handle empty states gracefully.

#### `create.blade.php` & `edit.blade.php`
- Display a form inside a white, rounded card (`bg-white p-6 rounded-2xl shadow-sm border border-slate-200`).
- Use standard labels (`text-sm font-medium text-slate-700`) and inputs (`focus:ring-emerald-500 focus:border-emerald-500`).
- Display validation errors below each field or at the top of the form.
- Provide "Simpan" (Save) and "Batal" (Cancel/Back) buttons.

#### `show.blade.php`
- Display detailed information of the entity in a read-only format (e.g., a card with a description list).
- Provide a "Kembali" (Back) button to return to the index page.

*(Note: For very simple dictionary entities with only 1-2 fields like Categories, you may opt to use AlpineJS modals in the `index.blade.php` for create/edit instead of separate views, mimicking the `CategoryController` approach.)*
