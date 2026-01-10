<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\Response;

class GeneratePackageController extends Controller
{
    public function generate2($name)
    {
        $nameStudly = Str::studly($name);
        $nameLower = Str::snake($name);
        $basePath = storage_path("app/temp_package/{$nameLower}");

        // 1️⃣ Clean old temp folder
        if (File::exists($basePath)) {
            File::deleteDirectory($basePath);
        }

        // 2️⃣ Create folder structure
        $folders = [
            "src/Config",
            "src/Http/Controllers",
            "src/Models",
            "src/database/migrations",
            "src/resources/lang/en",
            "src/resources/views",
            "src/routes",
        ];

        foreach ($folders as $folder) {
            File::makeDirectory($basePath.'/'.$folder, 0755, true);
        }

        // 3️⃣ Create files with demo content

        // composer.json
        File::put($basePath.'/composer.json', json_encode([
            "name" => "vendor/{$nameLower}",
            "autoload" => [
                "psr-4" => [
                    "{$nameStudly}\\" => "src/"
                ]
            ],
            "extra" => [
                "laravel" => [
                    "providers" => [
                        "{$nameStudly}\\{$nameStudly}ServiceProvider"
                    ]
                ]
            ]
        ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

        // ServiceProvider
        File::put($basePath."/src/{$nameStudly}ServiceProvider.php", "<?php
            namespace {$nameStudly};

            use Illuminate\Support\ServiceProvider;

            class {$nameStudly}ServiceProvider extends ServiceProvider
            {
                public function boot()
                {
                    \$this->loadRoutesFrom(__DIR__.'/routes/web.php');
                    \$this->loadRoutesFrom(__DIR__.'/routes/api.php');
                    \$this->loadViewsFrom(__DIR__.'/resources/views', '{$nameLower}');
                    \$this->loadTranslationsFrom(__DIR__.'/resources/lang', '{$nameLower}');
                    \$this->loadMigrationsFrom(__DIR__.'/database/migrations');
                    \$this->publishes([
                        __DIR__.'/Config' => config_path('{$nameLower}'),
                    ], '{$nameLower}-config');
                }

                public function register()
                {
                    \$this->mergeConfigFrom(__DIR__.'/Config/config.php', '{$nameLower}');
                }
            }");

                    // Controller
                    File::put($basePath."/src/Http/Controllers/{$nameStudly}Controller.php", "<?php
            namespace {$nameStudly}\Http\Controllers;

            use App\Http\Controllers\Controller;

            class {$nameStudly}Controller extends Controller
            {
                public function index()
                {
                    return '{$nameStudly} works!';
                }
            }");

                    // Model
                    File::put($basePath."/src/Models/{$nameStudly}.php", "<?php
            namespace {$nameStudly}\Models;

            use Illuminate\Database\Eloquent\Model;

            class {$nameStudly} extends Model
            {
                protected \$fillable = [];
            }");

        // Migration
        $migrationName = date('Y_m_d_His')."_create_{$nameLower}_table.php";
        File::put($basePath."/src/database/migrations/{$migrationName}", "<?php
            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Support\Facades\Schema;

            return new class extends Migration {
                public function up()
                {
                    Schema::create('{$nameLower}', function (Blueprint \$table) {
                        \$table->id();
                        \$table->string('name')->nullable();
                        \$table->timestamps();
                    });
                }

                public function down()
                {
                    Schema::dropIfExists('{$nameLower}');
                }
            };");

        // Routes
        File::put($basePath."/src/routes/web.php", "<?php
            use Illuminate\Support\Facades\Route;
            use {$nameStudly}\Http\Controllers\\{$nameStudly}Controller;

            Route::get('/{$nameLower}', [{$nameStudly}Controller::class, 'index']);");

                    File::put($basePath."/src/routes/api.php", "<?php
            use Illuminate\Support\Facades\Route;
            use {$nameStudly}\Http\Controllers\\{$nameStudly}Controller;

            Route::prefix('api/{$nameLower}')->group(function() {
                Route::get('/', [{$nameStudly}Controller::class, 'index']);
            });");

                    // Lang
                    File::put($basePath."/src/resources/lang/en/message.php", "<?php
            return [
                'welcome' => '{$nameStudly} package loaded successfully',
            ];");

                    // Config
                    File::put($basePath."/src/Config/config.php", "<?php
            return [
                'key' => 'value',
            ];");

        // README
        File::put($basePath."/README.md", "# {$nameStudly} Package\n\nAuto-generated Laravel package.");

        // 4️⃣ Zip the folder
        $zipFile = storage_path("app/{$nameLower}.zip");
        if (File::exists($zipFile)) {
            File::delete($zipFile);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($basePath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($basePath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        // 5️⃣ Return ZIP as response
        return response()->download($zipFile, "{$nameLower}.zip")->deleteFileAfterSend(true);
    }





    public function generate($name)
    {
        // 🔹 Sanitize input to prevent malicious paths
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
        if (!$name) {
            abort(400, 'Invalid package name.');
        }

        $nameStudly = Str::studly($name);
        $nameLower = Str::snake($name);
        $tableName = Str::plural($nameLower);
        $basePath = storage_path("app/temp_package/{$nameLower}");

        // 1️⃣ Clean old temp folder
        if (File::exists($basePath)) {
            File::deleteDirectory($basePath);
        }

        // 2️⃣ Create folder structure
        $folders = [
            "src/Config",
            "src/Http/Controllers",
            "src/Models",
            "src/database/migrations",
            "src/resources/lang/en",
            "src/resources/views",
            "src/routes",
        ];

        foreach ($folders as $folder) {
            File::makeDirectory($basePath.'/'.$folder, 0755, true);
        }

        // 3️⃣ Create files with demo content

        // composer.json
        File::put($basePath.'/composer.json', json_encode([
            "name" => "vendor/{$nameLower}",
            "autoload" => [
                "psr-4" => [
                    "{$nameStudly}\\" => "src/"
                ]
            ],
            "extra" => [
                "laravel" => [
                    "providers" => [
                        "{$nameStudly}\\{$nameStudly}ServiceProvider"
                    ]
                ]
            ]
        ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

        // ServiceProvider
        File::put($basePath."/src/{$nameStudly}ServiceProvider.php", "<?php
            namespace {$nameStudly};

            use Illuminate\Support\ServiceProvider;

            class {$nameStudly}ServiceProvider extends ServiceProvider
            {
                public function boot()
                {
                    \$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
                    \$this->loadRoutesFrom(__DIR__.'/../routes/api.php');
                    \$this->loadViewsFrom(__DIR__.'/../resources/views', '{$nameLower}');
                    \$this->loadTranslationsFrom(__DIR__.'/../resources/lang', '{$nameLower}');
                    \$this->loadMigrationsFrom(__DIR__.'/../database/migrations');
                    \$this->publishes([
                        __DIR__.'/../Config' => config_path('{$nameLower}'),
                    ], '{$nameLower}-config');
                }

                public function register()
                {
                    \$this->mergeConfigFrom(__DIR__.'/../Config/config.php', '{$nameLower}');
                }
            }");

        // Controller
        File::put($basePath."/src/Http/Controllers/{$nameStudly}Controller.php", "<?php
            namespace {$nameStudly}\Http\Controllers;

            use App\Http\Controllers\Controller;

            class {$nameStudly}Controller extends Controller
            {
                public function index()
                {
                    return '{$nameStudly} works!';
                }
            }");

        // Model
        File::put($basePath."/src/Models/{$nameStudly}.php", "<?php
            namespace {$nameStudly}\Models;

            use Illuminate\Database\Eloquent\Model;

            class {$nameStudly} extends Model
            {
                protected \$fillable = [];
                protected \$table = '{$tableName}';
            }");

        // Migration
        $migrationName = date('Y_m_d_His')."_create_{$tableName}_table.php";
        File::put($basePath."/src/database/migrations/{$migrationName}", "<?php
            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Support\Facades\Schema;

            return new class extends Migration {
                public function up()
                {
                    Schema::create('{$tableName}', function (Blueprint \$table) {
                        \$table->id();
                        \$table->string('name')->nullable();
                        \$table->timestamps();
                    });
                }

                public function down()
                {
                    Schema::dropIfExists('{$tableName}');
                }
            };");

        // Routes
        File::put($basePath."/src/routes/web.php", "<?php
            use Illuminate\Support\Facades\Route;
            use {$nameStudly}\Http\Controllers\\{$nameStudly}Controller;

            Route::get('/{$nameLower}', [{$nameStudly}Controller::class, 'index']);");

                    File::put($basePath."/src/routes/api.php", "<?php
            use Illuminate\Support\Facades\Route;
            use {$nameStudly}\Http\Controllers\\{$nameStudly}Controller;

            Route::prefix('api/{$nameLower}')->group(function() {
                Route::get('/', [{$nameStudly}Controller::class, 'index']);
            });");

                    // Lang
                    File::put($basePath."/src/resources/lang/en/message.php", "<?php
            return [
                'welcome' => '{$nameStudly} package loaded successfully',
            ];");

                    // Config
                    File::put($basePath."/src/Config/config.php", "<?php
            return [
                'key' => 'value',
            ];");

        // README
        File::put($basePath."/README.md", "# {$nameStudly} Package\n\nAuto-generated Laravel package.");

        // 4️⃣ Zip the folder
        $zipFile = storage_path("app/{$nameLower}.zip");
        if (File::exists($zipFile)) {
            File::delete($zipFile);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
            abort(500, 'Failed to create ZIP file.');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($basePath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($basePath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        // 5️⃣ Return ZIP as response
        return response()->download($zipFile, "{$nameLower}.zip")->deleteFileAfterSend(true);
    }

    public function generateFeature(Request $request)
    {
        $featureName = Str::studly($request->input('name')); // Post, Comment
        $featureLower = Str::snake($featureName); // post, comment
        $fieldsInput = $request->input('fields'); // ['title:string', 'body:text']
        $basePath = storage_path("app/temp_feature/{$featureLower}");

        // Clean old folder
        if (File::exists($basePath)) File::deleteDirectory($basePath);
        File::makeDirectory($basePath.'/src', 0755, true);
        File::makeDirectory($basePath.'/src/Models', 0755, true);
        File::makeDirectory($basePath.'/src/Http/Controllers', 0755, true);
        File::makeDirectory($basePath.'/src/database/migrations', 0755, true);
        File::makeDirectory($basePath.'/resources/views/'.$featureLower, 0755, true);
        File::makeDirectory($basePath.'/routes', 0755, true);

        // --- 1️⃣ Migration ---
        $tableName = Str::plural($featureLower);
        $migrationName = date('Y_m_d_His')."_create_{$tableName}_table.php";

        $migrationFields = "";
        $sqlColumns = [];
        foreach ($fieldsInput as $f) {
            [$name, $type] = explode(':', $f);
            $migrationFields .= "            \$table->{$type}('{$name}');\n";
            $sqlColumns[] = "`$name` $type";
        }

        File::put($basePath."/src/database/migrations/{$migrationName}", "<?php
            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Support\Facades\Schema;

            return new class extends Migration {
                public function up()
                {
                    Schema::create('{$tableName}', function (Blueprint \$table) {
                        \$table->id();
            {$migrationFields}            \$table->timestamps();
                    });
                }

                public function down()
                {
                    Schema::dropIfExists('{$tableName}');
                }
            };");

        // --- 2️⃣ Model ---
        File::put($basePath."/src/Models/{$featureName}.php", "<?php
            namespace App\Models;

            use Illuminate\Database\Eloquent\Model;

            class {$featureName} extends Model
            {
                protected \$fillable = ['" . implode("','", array_map(fn($f)=>explode(':',$f)[0], $fieldsInput)) . "'];
                protected \$table = '{$tableName}';
            }");

                // --- 3️⃣ Controller ---
                File::put($basePath."/src/Http/Controllers/{$featureName}Controller.php", "<?php
            namespace App\Http\Controllers;

            use App\Models\\{$featureName};
            use Illuminate\Http\Request;

            class {$featureName}Controller extends Controller
            {
                public function index() { return view('{$featureLower}.index', ['data' => {$featureName}::all()]); }
                public function create() { return view('{$featureLower}.create'); }
                public function store(Request \$r) {
                    {$featureName}::create(\$r->only(['" . implode("','", array_map(fn($f)=>explode(':',$f)[0], $fieldsInput)) . "']));
                    return redirect()->route('{$featureLower}.index');
                }
                public function edit({$featureName} \$item) { return view('{$featureLower}.edit', ['item'=>\$item]); }
                public function update(Request \$r, {$featureName} \$item) { \$item->update(\$r->only(['" . implode("','", array_map(fn($f)=>explode(':',$f)[0], $fieldsInput)) . "'])); return redirect()->route('{$featureLower}.index'); }
                public function view({$featureName} \$item) { return view('{$featureLower}.view', ['item'=>\$item]); }
                public function delete({$featureName} \$item) { \$item->delete(); return redirect()->route('{$featureLower}.index'); }
            }");

        // --- 4️⃣ Blade views ---
        $bladeTemplates = ['index','create','edit','view'];
        foreach ($bladeTemplates as $view) {
            File::put($basePath."/resources/views/{$featureLower}/{$view}.blade.php", "<!-- {$view} view for {$featureName} -->");
        }

        // --- 5️⃣ Routes ---
        File::put($basePath."/routes/web.php", "<?php
            use {$featureName}Controller;
            use Illuminate\Support\Facades\Route;

            Route::prefix('{$featureLower}')->group(function() {
                Route::get('/', [{$featureName}Controller::class, 'index'])->name('{$featureLower}.index');
                Route::get('/create', [{$featureName}Controller::class, 'create'])->name('{$featureLower}.create');
                Route::post('/store', [{$featureName}Controller::class, 'store'])->name('{$featureLower}.store');
                Route::get('/{item}/edit', [{$featureName}Controller::class, 'edit'])->name('{$featureLower}.edit');
                Route::post('/{item}/update', [{$featureName}Controller::class, 'update'])->name('{$featureLower}.update');
                Route::get('/{item}', [{$featureName}Controller::class, 'view'])->name('{$featureLower}.view');
                Route::delete('/{item}/delete', [{$featureName}Controller::class, 'delete'])->name('{$featureLower}.delete');
            });");

        // --- 6️⃣ SQL Command ---
        $sqlCommand = "CREATE TABLE `{$tableName}` (\n  id BIGINT AUTO_INCREMENT PRIMARY KEY,\n  ".implode(",\n  ", $sqlColumns).",\n  created_at TIMESTAMP,\n  updated_at TIMESTAMP\n);";
        File::put($basePath."/sql_command.txt", $sqlCommand);

        // --- 7️⃣ Zip everything ---
        $zipFile = storage_path("app/{$featureLower}.zip");
        if (File::exists($zipFile)) File::delete($zipFile);

        $zip = new \ZipArchive;
        if ($zip->open($zipFile, \ZipArchive::CREATE) !== TRUE) abort(500, 'Failed to create ZIP.');

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($basePath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($basePath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        return response()->download($zipFile, "{$featureLower}.zip")->deleteFileAfterSend(true);
    }

    public function generateAlterTable(Request $request)
    {
        $table = $request->input('table_name'); // users
        $columns = $request->input('columns'); // array of columns with name & type
        $after = $request->input('after'); // initial "after" column

        if (!$table || !is_array($columns) || !$after) {
            return response()->json(['error'=>'Invalid input'], 400);
        }

        $migrationClass = "Alter" . Str::studly($table) . "Table";
        $migrationName = date('Y_m_d_His') . "_alter_{$table}_table.php";

        $migrationUp = "";
        $migrationDown = "";
        $sqlCommand = "";

        $currentAfter = $after;

        foreach ($columns as $col) {
            $colName = $col['name'];
            $colType = $col['type'];

            // --- Migration ---
            $afterPart = $currentAfter ? "->after('{$currentAfter}')" : "";
            $migrationUp .= "        Schema::table('{$table}', function (Blueprint \$table) {\n";
            $migrationUp .= "            \$table->{$colType}('{$colName}'){$afterPart};\n";
            $migrationUp .= "        });\n";

            $migrationDown .= "        Schema::table('{$table}', function (Blueprint \$table) {\n";
            $migrationDown .= "            \$table->dropColumn('{$colName}');\n";
            $migrationDown .= "        });\n";

            // --- SQL Command ---
            $sqlAfter = $currentAfter ? "AFTER `{$currentAfter}`" : "FIRST";
            $sqlCommand .= "ALTER TABLE `{$table}` ADD COLUMN `{$colName}` {$colType} {$sqlAfter};\n";

            // Update currentAfter to the column just added
            $currentAfter = $colName;
        }

        // --- Build migration content ---
        $migrationContent = "<?php
        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration {
            public function up()
            {
        {$migrationUp}    }

            public function down()
            {
        {$migrationDown}    }
        };
        ";

        // --- Return JSON ---
        return response()->json([
            'migration_name' => $migrationName,
            'migration_content' => $migrationContent,
            'sql_command' => $sqlCommand
        ]);
    }


}

