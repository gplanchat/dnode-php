<?php

/* Include all dependencies */
require(__DIR__.'/../vendor/.composer/autoload.php');

class RemoteSessionServer
{
    private $crSession = null;
    private $repository = null;
    private $dnode = null;

    public function __construct ($repository, $credentials, $workspace = null)
    {
          $this->crSession = $repository->login($credentials, $workspace);
          $this->dnode = new DNode\DNode($this);
    }

    /* Get value of the property at defined path */
    public function getPropertyValue($path, $cb)
    {
        $value = null;
        try {
            $value = $this->crSession->getProperty($path)->getValue();
        } catch (\Exception $e) {
            $this->remote->setException(get_class($e), $e->getMessage());
        }
        $this->remote->setValue($value, function() use ($cb) { 
            $cb();
        });
    }

    public function listen($port)
    {
        $this->dnode->listen($port);
    }
}

/*
$credentials = new \PHPCR\SimpleCredentials("admin", "password");
$params = array (
    'midgard2.configuration.file' => getenv('MIDGARD_ENV_GLOBAL_SHAREDIR') . "/midgard2.conf"
);

$repository = Midgard\PHPCR\RepositoryFactory::getRepository($params);

$server = new RemoteSessionServer($repository, $credentials);
$server->listen(6060);

exit;
// Create a DNode server
$server = new DNode\DNode(new Converter());
$server->listen(6060);
 */
