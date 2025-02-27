<?php

class OpencartRadTools
{

    protected $basePath;
    protected $type = 'extension';
    protected $language;


    protected $catalogDirStruct = [];

    public function __construct(string $basePath = 'admin',  string $type = 'extension', string $lang = 'en-gb')
    {

        $this->type = $type;
        $this->basePath = $basePath;
        $this->language = $lang;

    

        $this->oc_create_dir_files($this->oc_folder_struct(), 'myproject');
        // echo "\n";
        // print_r($this->oc_folder_struct());
        //echo "\n";
    }


    public function oc_folder_struct(): array
    {

        // get languages
        $languages = [];
        if (is_array($this->language)) {
            foreach ($this->language as $langcode) {

                $languages[$langcode]   = $this->basePath . '/language/' . $langcode . '/' . $this->type;
            }
        } else {
            $languages[$this->language]   = $this->basePath . '/language/' . $this->language . '/' . $this->type;
        }




        $catalogDirStruct = [];
        if ($this->type === 'extension') {
            $catalogDirStruct = [
                $this->basePath . '/controller/' . $this->type,
                $this->basePath . '/model/' . $this->type

            ];

            foreach ($languages as $languagePath) {
                $catalogDirStruct[] =  $languagePath;
            }

            $catalogDirStruct = array_merge($catalogDirStruct, [
                $this->basePath . '/view/image',
                $this->basePath . '/view/javascript',
                $this->basePath . '/view/stylesheet',
                $this->basePath . '/view/template/' . $this->type,
            ]);
        }

        return $catalogDirStruct;
    }






    public function oc_create_dir_files(array $dirs, string $project_name): void
    {
        $new = false;
     
        foreach ($dirs as $dir) {

            $dirPath = $dir;
            if (!file_exists($dirPath)) {
                $env = true;
                mkdir($dirPath, 0777, true);
                //echo "Created dir " . $dirPath . "\n";
            }

            if (strpos($dirPath, 'template') !== false) {
                $twigPath = $dirPath . '/';
                $twigFile = $twigPath.  $project_name . '.twig';

                if (!file_exists($twigFile)) {
                    $twig_content = $this->oc_create_basic_twig($project_name);
                    file_put_contents($twigFile, $twig_content );
                    chmod( $twigFile, 0777);
                    echo "Created example Twig file: $twigFile\n";


                   // $vars = $this->extractVarsFromTwig($twig_file);
                   $langDir = $this->basePath . '/language/' . $this->language . '/' . 
                   ($this->type === 'module' 
                       ? 'extension/' . $this->type . '/' . $project_name 
                       : $this->type . '/'
                   );
                    
                    $languageFile =  $langDir .$project_name.".php";


                    if(!is_file($languageFile))
                    {
                        file_put_contents($languageFile, '');
                        chmod($languageFile, 0777);
                    } 
                   
                
                    $vars = $this->extractVarsFromTwig($twigFile);
                    $this->generateLanguageFile($vars, $languageFile);
                }
            }

            if (strpos($dirPath, 'controller') !== false) {
                $file = $dirPath . '/' . $project_name . '.php';
                if (!file_exists($file)) {
                    $phpController  = $this->oc_create_basic_controller($project_name);
                    file_put_contents($file, $phpController);
                    echo "Created example to Controller file: $file\n";
                }
            }

           /* if (strpos($dirPath, 'language') !== false) {
                $file = $dirPath . '/' . $project_name . '.php';

                if (!file_exists($file)) {
                    file_put_contents($file, '');

                //obter das variáveis do ficheiro twig
                echo "Created compatible language file: $file\n";
                }

              
            }*/
        }
    }



    public function oc_create_basic_controller(string $project): string
    {
        $controllerCode = "<?php\n";
        $controllerCode .= "namespace Opencart\\Admin\\Controller\\" . ucfirst($this->type) . ";\n\n";
        $controllerCode .= "use \\Opencart\\System\\Engine\\Controller;\n\n";
        $controllerCode .= $this->oc_intelephense_compatible()."\n\n";
        $controllerCode .= "class " . ucfirst($project) . " extends Controller {\n\n";


        $controllerCode .= "// index method\n";
        $controllerCode .= "public function index(): void\n";
        $controllerCode .= "{\n";
        $controllerCode .= "        \$this->load->language(";
        $controllerCode .= $this->type === 'extension'
            ? "'extension/" . strtolower($project) . "'"
            : "'extension/" . $this->type . "/" . strtolower($project) . "'";
        $controllerCode .= ");\n";
        $controllerCode .= "}\n\n";

        $controllerCode .= "// install method\n";
        $controllerCode .= "    public function install(): void {\n";
        $controllerCode .= "        // Add installation logic here\n";
        $controllerCode .= "        \$this->load->model(";
        $controllerCode .= ($this->type === 'extension'
            ? "'setting/" .$this->type . "'"
            : "'setting/extension/" . $this->type ."'");
        $controllerCode .= ");\n";
        $controllerCode .= "        \$this->model_"
            . ($this->type === 'extension'
                ? "setting_".$this->type 
                : 'extension_' . $this->type . "_" . strtolower($project))
            . "->install();\n";
        $controllerCode .= "    }\n\n";

        $controllerCode .= "// uninstall method\n";
        $controllerCode .= "    public function uninstall(): void {\n";
        $controllerCode .= "        // Add uninstallation logic here\n";
        $controllerCode .= "        \$this->load->model(";
        $controllerCode .= ($this->type === 'extension'
            ? "'setting/" .$this->type . "'"
            : "'setting/extension/" . $this->type ."'");
        $controllerCode .= ");\n";
        $controllerCode .= "        \$this->model_"
            . ($this->type === 'extension'
                ? "setting_".$this->type 
                : 'extension_' . $this->type . "_" . strtolower($project))
            . "->uninstall();\n";
        $controllerCode .= "    }\n\n";
        $controllerCode .= "}";
        return $controllerCode;
    }



    public function oc_create_basic_twig($project_name)
    {
        return '{{ header }}
                {{ column_left }}

                <div id="content">
                    {{ breadcrumbs }}
                <div class="page-header">
                    <div class="container-fluid">
                    <div class="float-end">
                        <button type="submit" form="form-'.$project_name.'" class="btn btn-primary">{{ submit }}</button>
                        <a href="{{ cancel }}" class="btn btn-light">{{ button_cancel }}</a>
                    </div>
                    <h1>{{ heading_title }}</h1>
                    </div>
                </div>

                <div class="container-fluid">
                    {% if error_warning %}
                    <div class="alert alert-danger alert-dismissible">
                        <i class="fa fa-exclamation-circle"></i> {{ error_warning }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    {% endif %}

                    <div class="card">
                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ text_edit }}
                    </div>
                    <div class="card-body">
                        <form id="form-'.$project_name.'" action="{{ post_action }}" method="post">
                        <div class="mb-3">
                            <label for="input-status" class="form-label">{{ entry_status }}</label>
                            <select name="module_'.$project_name.'_status" id="input-status" class="form-select">
                            <option value="1" {{ module_'.$project_name.'_status == \'1\' ? \'selected\' : \'\' }}>{{ text_enabled }}</option>
                            <option value="0" {{ module_'.$project_name.'_status == \'0\' ? \'selected\' : \'\' }}>{{ text_disabled }}</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">{{ submit }}</button>
                            <a href="{{ cancel_action }}" class="btn btn-light">{{ cancel }}</a>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
                </div>

                {{ footer }}';



    }


    public function oc_intelephense_compatible(){
      
            return <<<EOD
        /**
         * @property \Opencart\System\Engine\Model \$model_user_user_group
         * @property \Opencart\System\Engine\Model \$model_setting_extension
         * @property \Opencart\System\Engine\Model \$model_setting_setting
         * @property \Opencart\System\Engine\Model \$model_setting_event
         * @property \Opencart\System\Library\DB \$db
         * @property \Opencart\System\Library\Loader \$load
         * @property \Opencart\System\Library\Response \$response
         * @property \Opencart\System\Library\Request \$request
         * @property \Opencart\System\Library\Language \$language
         * @property \Opencart\System\Library\Document \$document
         * @property \Opencart\System\Library\Url \$url
         * @property \Opencart\System\Library\Session \$session
         * @property \Opencart\System\Library\Cache \$cache
         * @property \Opencart\System\Library\Config \$config
         * @property \Opencart\System\Library\Customer \$customer
         * @property \Opencart\System\Library\Currency \$currency
         * @property \Opencart\System\Library\Weight \$weight
         * @property \Opencart\System\Library\Length \$length
         * @property \Opencart\System\Library\Cart\Cart \$cart
         * @property \Opencart\System\Library\Cart\Customer \$cart_customer
         * @property \Opencart\System\Library\Cart\Currency \$cart_currency
         * @property \Opencart\System\Library\Cart\Tax \$tax
         * @property \Opencart\System\Library\Cart\Shipping \$shipping
         * @property \Opencart\System\Library\Cart\Total \$total
         * @property \Opencart\System\Library\Mail\Mail \$mail
         * @property \Opencart\System\Library\User \$user
         * @property \Opencart\System\Library\Log \$log
         */
        EOD;
        
    }


    public function generateLanguageFile(array $vars, string $file): void {
        
        $languageContent = "<?php\n\n";
    

        foreach ($vars as $var) {
            //  creating a language entry like 'text_example' => 'Example Text'
            $langKey = '' . strtolower(str_replace(' ', '_', $var));
            $pattern = "/==|=|\?/";
            $exclude  = ['header', 'column_left', 'footer', 'breadcrumbs'];
            if(!preg_match($pattern, $var)  && !in_array(trim(strtolower($var)), $exclude)):
            $languageContent .= "\$_['" . $langKey . "']      = '" . ucfirst($var) . "';\n";
            endif;
        }
   
        file_put_contents($file, $languageContent);
        chmod($file, 0777);
    }
    


    public function extractVarsFromTwig($twigFilePath): array {
    
       // echo $twigFilePath;

       $twigContent = file_get_contents($twigFilePath);

     
        // Regex to match all variables within {{ }}
        preg_match_all('/\{\{(.*?)\}\}/', $twigContent, $matches);
    
        // The matches will contain the variables, clean up the strings
        $vars = array_map('trim', $matches[1]);
    
        return $vars;
    }
}


$ocRadTools = new OpencartRadTools('admin', 'extension', 'en-gb');
