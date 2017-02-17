<?php

namespace App\Console\Commands\Scaffold;

use Illuminate\Console\Command;
use Faker\Factory;
use Illuminate\Filesystem\FileNotFoundException;

class Scaffold
{
    /**
     * @var array
     */
    private $laravelClasses = array();

    /**
     * @var Model
     */
    private $model;

    /**
     * @var Migration
     */
    private $migration;

    /**
     * @var string
     */
    private $controllerType;

    /**
     * @var bool
     */
    private $fromFile;

    /**
     * @var FileCreator
     */
    private $fileCreator;

    /**
     * @var array
     */
    protected $configSettings;

    /**
     * @var \Illuminate\Console\Command
     */
    protected $command;

    /**
     * @var bool
     */
    private $columnAdded = false;

    /**
     * @var bool
     */
    private $onlyMigration = false;

    /**
     * @var bool
     */
    private $namespaceGlobal;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var array
     */
    private $lastTimeStamp = array();

    /**
     *  Stores the current collection of models
     *
     * @var array
     */
    private $models = array();

    public function __construct(Command $command)
    {
        $this->configSettings = $this->getConfigSettings();
        $this->command = $command;
        $this->fileCreator = new FileCreator($command);
    }

    /**
     * Load user's config settings
     *
     * @return array
     */
    private function getConfigSettings()
    {
        $configSettings = [];

        $configSettings['pathTo'] = config("scaffold.paths");

        foreach($configSettings['pathTo'] as $pathName => $path)
        {
            if($path[strlen($path)-1] != "/")
            {
                if($pathName != "layout")
                    $path .= "/";

                $configSettings['pathTo'][$pathName] = $path;
            }
        }

        $configSettings['names'] = config("scaffold.names");

        $configSettings['views'] = config("scaffold.views");

        return $configSettings;
    }

    /**
     *  Prompt for and save models from the command line
     */
    public function createModels()
    {
        $this->fromFile = false;
        $this->fileCreator->fromFile = false;

        $this->setupLayoutFiles();

        $modelAndProperties = $this->askForModelAndFields();

        $moreTables = trim($modelAndProperties) == "q" ? false : true;

        while( $moreTables )
        {
            $this->saveModelAndProperties($modelAndProperties, array());

            $this->createFiles();

            $this->command->info("Model ".$this->model->upper(). " and all associated files created successfully!");

            $modelAndProperties = $this->command->ask('Add model with fields or "q" to quit: ');

            $moreTables = trim($modelAndProperties) == "q" ? false : true;
        }
    }

    /**
     *
     */
    public function setupLayoutFiles()
    {
        $this->laravelClasses = $this->getLaravelClassNames();
    }

    /**
     *  Get the laravel class names to check for collisions
     *
     * @return array
     */
    private function getLaravelClassNames()
    {
        $classNames = array();

        $aliases = \Config::get('app.aliases');

        foreach ($aliases as $alias => $facade)
        {
            array_push($classNames, strtolower($alias));
        }

        return $classNames;
    }

    /**
     *  Save the model and its properties
     *
     * @param $modelAndProperties
     * @param $oldModelFile
     * @param bool $storeInArray
     */
    private function saveModelAndProperties($modelAndProperties, $oldModelFile, $storeInArray = true)
    {
        do {
            if(!$this->namespaceGlobal)
                $this->namespace = "";

            $this->model = new Model($this->command, $oldModelFile, $this->namespace);

            $this->model->generateModel($modelAndProperties);

            if($storeInArray)
                $this->models[$this->model->getTableName()] = $this->model;

            if(!$this->namespaceGlobal)
            {
                $this->fileCreator->namespace = $this->model->getNamespace();
                $this->namespace = $this->model->getNamespace();
            }

            $modelNameCollision = in_array($this->model->lower(), $this->laravelClasses);

        } while($modelNameCollision);

        $propertiesGenerated = $this->model->generateProperties();

        if(!$propertiesGenerated)
        {
            if($this->fromFile)
                exit;
            else
                $this->createModels();
        }
    }

    /**
     *  Creates all of the files
     */
    private function createFiles()
    {
        $this->createModel();

        $this->migration = new Migration($this->configSettings['pathTo']['migrations'], $this->model, $this->fileCreator);

        $tableCreated = $this->migration->createMigrations($this->lastTimeStamp);

        $this->runMigrations();

        if($tableCreated)
        {
            $this->templatePathWithControllerType = $this->configSettings['pathTo']['templates'] ."/";

            if(!$this->model->exists)
            {
                $this->createRepository();
                $this->createDataTable();
                $this->createController();
                $this->createViews();
                $this->updateRoutes();
                $this->createRequests();
            }
        }
    }

    /**
     * Creates the model file
     */
    private function createModel()
    {
        $fileName = $this->configSettings['pathTo']['models'] . $this->nameOf("modelName") . ".php";

        if(\File::exists($fileName))
        {
            $this->updateModel($fileName);
            $this->model->exists = true;
            return;
        }

        $fileContents = "";

        if($this->model->hasSoftdeletes()) {
            $fileContents = "\tuse Illuminate\Database\Eloquent\SoftDeletes;\n";
        }

        if(!$this->model->hasTimestamps())
            $fileContents .= "\tpublic \$timestamps = false;\n";

        $properties = "";
        foreach ($this->model->getProperties() as $property => $type) {
            $properties .= "'$property',\n\t";
        }

        $properties = rtrim($properties, ",");

        $fileContents .= "\tprotected \$fillable = [\n\t\t".$properties."\n\t];\n";

        $fileContents = $this->addRelationships($fileContents);

        $template = "model.stub";

        $this->makeFileFromTemplate($fileName, $this->configSettings['pathTo']['templates'].$template, $fileContents);
    }

    /**
     *  Updates an existing model file
     *
     * @param $fileName
     */
    private function updateModel($fileName)
    {
        $fileContents = \File::get($fileName);

        $fileContents = trim($this->addRelationships($fileContents, false));

        $fileContents = trim($this->removeRelationships($fileContents)) . "\n}\n";

        \File::put($fileName, $fileContents);
    }

    /**
     *  Add relationships to the model
     *
     * @param $fileContents
     * @param $newModel
     * @return string
     */
    private function addRelationships($fileContents, $newModel = true)
    {
        if(!$newModel)
            $fileContents = substr($fileContents, 0, strrpos($fileContents, "}"));

        foreach ($this->model->getRelationships() as $relation)
        {
            $relatedModel = $relation->model;

            if(strpos($fileContents, $relation->getName()) !== false && !$newModel)
                continue;

            $functionContent = "\t\treturn \$this->" . $relation->getType() . "('" . $relatedModel->nameWithNamespace() . "');\n";
            $fileContents .= $this->fileCreator->createFunction($relation->getName(), $functionContent);

            $relatedModelFile = $this->configSettings['pathTo']['models'] . $relatedModel->upper() . '.php';

            if (!\File::exists($relatedModelFile))
            {
                if ($this->fromFile)
                    continue;
                else
                {
                    $editRelatedModel = $this->command->confirm("Model " . $relatedModel->upper() . " doesn't exist yet. Would you like to create it now [y/n]? ", true);

                    if ($editRelatedModel)
                        $this->fileCreator->createClass($relatedModelFile, "", array('name' => "\\Eloquent"));
                    else
                        continue;
                }
            }

            $content = \File::get($relatedModelFile);
            if (preg_match("/function " . $this->model->lower() . "/", $content) !== 1 && preg_match("/function " . $this->model->plural() . "/", $content) !== 1)
            {
                $index = 0;
                $reverseRelations = $relation->reverseRelations();

                if (count($reverseRelations) > 1)
                    $index = $this->command->ask($relatedModel->upper() . " (0=" . $reverseRelations[0] . " OR 1=" . $reverseRelations[1] . ") " . $this->model->upper() . "? ");

                $reverseRelationType = $reverseRelations[$index];
                $reverseRelationName = $relation->getReverseName($this->model, $reverseRelationType);

                $content = substr($content, 0, strrpos($content, "}"));
                $functionContent = "\t\treturn \$this->" . $reverseRelationType . "('" . $this->model->nameWithNamespace() . "');\n";
                $content .= $this->fileCreator->createFunction($reverseRelationName, $functionContent) . "}\n";

                \File::put($relatedModelFile, $content);
            }
        }
        return $fileContents;
    }

    /**
     *  Remove relationships from the model
     *
     * @param $fileContents
     * @return string
     */
    private function removeRelationships($fileContents)
    {
        foreach ($this->model->getRelationshipsToRemove() as $relation)
        {
            $name = $relation->getName();

            if(strpos($fileContents, $name) !== false)
            {
                $fileContents = preg_replace("/public\s+function\s+$name\s*\(.*?\).*?\{.*?\}/s", "", $fileContents);
            }
        }
        return $fileContents;
    }

    /**
     *  Gets the name from the configuration file
     *
     * @param string $type
     * @return string
     */
    private function nameOf($type)
    {
        return $this->replaceModels($this->configSettings['names'][$type]);
    }

    /**
     *  Prompt user for model and properties and return result
     *
     * @return string
     */
    private function askForModelAndFields()
    {
        $modelAndFields = $this->command->ask('Add model with its relations and fields or type "q" to quit (type info for examples) ');

        if($modelAndFields == "info")
        {
            $this->showInformation();

            $modelAndFields = $this->command->ask('Now your turn: ');
        }

        return $modelAndFields;
    }

    /**
     *  Copy template files from package folder to specified user folder
     */
    private function copyTemplateFiles()
    {
        if(!\File::isDirectory($this->configSettings['pathTo']['templates']))
            $this->fileCreator->copyDirectory("vendor/jrenton/laravel-scaffold/src/Jrenton/LaravelScaffold/templates/", $this->configSettings['pathTo']['templates']);
    }

    /**
     *  Show the examples of the syntax to be used to add models
     */
    private function showInformation()
    {
        $this->command->info('MyNamespace\Book title:string year:integer');
        $this->command->info('With relation: Book belongsTo Author title:string published:integer');
        $this->command->info('Multiple relations: University hasMany Course, Department name:string city:string state:string homepage:string )');
        $this->command->info('Or group like properties: University hasMany Department string( name city state homepage )');
    }

    /**
     *  Prompt user to run the migrations
     */
    private function runMigrations()
    {
        if(!$this->fromFile)
        {
            $editMigrations = $this->command->confirm('Would you like to edit your migrations file before running it [y/n]? ', true);

            if ($editMigrations)
            {
                $this->command->info('Remember to run "php artisan migrate" after editing your migration file');
                $this->command->info('And "php artisan db:seed" after editing your seed file');
            }
            else
            {
                while (true)
                {
                    try
                    {
                        $this->command->call('migrate');
                        break;
                    }
                    catch (\Exception $e)
                    {
                        $this->command->info('Error: ' . $e->getMessage());
                        $this->command->error('This table already exists and/or you have duplicate migration files.');
                        $this->command->confirm('Fix the error and enter "yes" ', true);
                    }
                }
            }
        }
    }

    /**
     *  Create the repository
     *
     * @return array
     */
    private function createRepository()
    {
        $this->fileCreator->createDirectory($this->configSettings['pathTo']['repositories']);

        $fileName = $this->configSettings['pathTo']['repositories'] . $this->nameOf("repository") . '.php';

        $this->makeFileFromTemplate($fileName, $this->configSettings['pathTo']['templates']."/repository.stub");
    }

    /**
     *  Create controller
     *
     * @return array
     */
    private function createDataTable()
    {
        $fileName = $this->configSettings['pathTo']['datatables'] . $this->nameOf("datatables"). ".php";

        $this->makeFileFromTemplate($fileName, $this->configSettings['pathTo']['templates']."/datatables.stub");
    }

    /**
     *  Create controller
     *
     * @return array
     */
    private function createController()
    {
        $fileName = $this->configSettings['pathTo']['controllers'] . $this->nameOf("controller"). ".php";

        $this->makeFileFromTemplate($fileName, $this->configSettings['pathTo']['templates']."/controller.stub");
    }

    /**
     *  Create requests
     *
     * @return array
     */
    private function createRequests()
    {
        $dir = $this->configSettings['pathTo']['requests'] ."/" . $this->model->upperPlural() . "/";
        $this->fileCreator->createDirectory($dir);

        $createFileName = $dir . "CreateRequest.php";
        $this->makeFileFromTemplate($createFileName, $this->configSettings['pathTo']['templates']."/request.stub");

        $updateFileName = $dir . "UpdateRequest.php";
        $this->makeFileFromTemplate($updateFileName, $this->configSettings['pathTo']['templates']."/request.stub");
    }

    /**
     *  Update routes file with new controller
     *
     * @return string
     */
    private function updateRoutes()
    {
        $routeFile = $this->configSettings['pathTo']['routes']."web.php";

        $namespace = $this->namespace ? $this->namespace . "\\" : "";

        $fileContents = "Route::resource('" . $this->nameOf("viewFolder") . "', '" . $namespace. $this->nameOf("controller") ."');\n";

        $content = \File::get($routeFile);
        if (preg_match("/" . $this->model->lower() . "/", $content) !== 1)
            \File::append($routeFile, $fileContents);
    }

    /**
     *  Create views as specified in the configuration file
     */
    private function createViews()
    {
        $dir = $this->configSettings['pathTo']['views'] . $this->nameOf('viewFolder') . "/";
        if (!\File::isDirectory($dir))
            \File::makeDirectory($dir);

        $pathToViews = $this->configSettings['pathTo']['templates']."/views/";

        if (empty($this->model->getProperties())) {
            return;
        }

        foreach($this->configSettings['views'] as $view)
        {
            $fileName = $dir . "$view.blade.php";

            try
            {
                $this->makeFileFromTemplate($fileName, $pathToViews."$view.stub");
            }
            catch(Exception $e)
            {
                if ($e instanceof FileNotFoundException)
                    $this->command->error("Template file ".$pathToViews . $view.".stub does not exist! You need to create it to generate that file!");
            }
        }
    }

    /**
     *  Generate a file based off of a template
     *
     * @param $fileName
     * @param $template
     * @param string $content
     */
    public function makeFileFromTemplate($fileName, $template, $content = "")
    {
        $fileContents = \File::get($template);

        $fileContents = $this->replaceNames($fileContents);
        $fileContents = $this->replaceModels($fileContents);
        $fileContents = $this->replaceProperties($fileContents);

        if($content)
            $fileContents = str_replace("[content]", $content, $fileContents);

        $namespace = $this->namespace ? "namespace ".$this->namespace. ";" : "";
        $fileContents = str_replace("[namespace]", $namespace, $fileContents);

        $this->fileCreator->createFile($fileName, $fileContents);
    }

    /**
     *  Replace [model] tags in template with the model name
     *
     * @param $fileContents
     * @return mixed
     */
    private function replaceModels($fileContents)
    {
        $modelReplaces = array('[model]'=>$this->model->lower(), '[Model]'=>$this->model->upper(), '[models]'=>$this->model->plural(), '[Models]'=>$this->model->upperPlural());

        foreach($modelReplaces as $model => $name)
            $fileContents = str_replace($model, $name, $fileContents);

        return $fileContents;
    }

    /**
     *  Replace 'names' from the config file with their names
     *
     * @param $fileContents
     * @return mixed
     */
    public function replaceNames($fileContents)
    {
        foreach($this->configSettings['names'] as $name => $text)
            $fileContents = str_replace("[$name]", $text, $fileContents);

        return $fileContents;
    }

    /**
     *  Replace [property] with model's properties
     *
     * @param $fileContents
     * @return mixed
     */
    private function replaceProperties($fileContents)
    {
        $lastPos = 0;
        $needle = "[repeat]";
        $endRepeat = "[/repeat]";

        while (($lastPos = strpos($fileContents, $needle, $lastPos))!== false)
        {
            $beginning = $lastPos;
            $lastPos = $lastPos + strlen($needle);
            $endProp = strpos($fileContents, $endRepeat, $lastPos);
            $end = $endProp + strlen($endRepeat);
            $replaceThis = substr($fileContents, $beginning, $end-$beginning);
            $propertyTemplate = substr($fileContents, $lastPos, $endProp - $lastPos);
            $properties = "";

            foreach($this->model->getProperties() as $property => $type)
            {
                $temp = str_replace("[property]", $property, $propertyTemplate);
                $temp = str_replace("[Property]", ucfirst($property), $temp);
                $properties .= $temp;
            }

            $properties = trim($properties, ",");
            $fileContents = str_replace($replaceThis, $properties, $fileContents);
        }

        return $fileContents;
    }
}
