<?php namespace Alice\Core;

/**
 * This is the BaseController class
 *
 * The other controllers will inherit this one
 * so that they can access Views
 *
 */
class BaseController
{
    public $model = null;

    public function __construct()
    {
        //echo "I'm BaseController.<br />";

        // Instantiate the View object
        $this->view = new BaseView();

        $this->bindModel();
    }

    /**
     * Function to load Model automatically
     */
    function bindModel()
    {
        $relatedModel = str_replace('Controller', 'Model', get_class($this));
        $modelPath = Application::getPath('path.models') . DIRECTORY_SEPARATOR . "{$relatedModel}.php";

        // If Model exists then load it
        if (file_exists($modelPath))
        {
            require $modelPath;
            $this->model = new $relatedModel;
        }
    }
}
