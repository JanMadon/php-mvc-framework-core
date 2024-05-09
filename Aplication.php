<?php

namespace janm\phpmvc;

use janm\phpmvc\db\DataBase;
use janm\phpmvc\db\DbModel;

class Aplication
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';

    protected array $eventListeners = [];
    public static Aplication $app;
    public static string $ROOT_DIR;
    public DataBase $db;
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Controller $controller;
    public ?UserModel $user;
    public View $view;


    public function __construct(string $rootPatch, array $config)
    {
        $this->userClass = $config['user'];
        self::$ROOT_DIR = $rootPatch;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new DataBase($config['db']);

        $primaryValue = $this->session->get('user');
        if ($primaryValue){
            //todo jeśli user zostanie skasowany a cooke zostanie w przeglądarce to wali błędem
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    public function run()
    {
        $this->triggerEvents(self::EVENT_BEFORE_REQUEST);

        try {
            echo $this->router->resolve();
        } catch (\Exception $e){
            echo $e->getCode();
            //$this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', ['exception' => $e]);

        }
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    public function on($eventName, $callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }

    private function triggerEvents($eventName)
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }

    }

}