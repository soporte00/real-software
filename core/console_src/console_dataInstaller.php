<?php namespace core\console_src;


// ignore_user_abort(true);
// set_time_limit(0);

class console_dataInstaller
{

    private $databaseFolder = [];

    public function __construct()
    {
        $this->databaseFolder = glob('./database/*');
    }

    private function listFiles($method, $reverse = false)
    {

        if ($reverse) $this->databaseFolder = array_reverse($this->databaseFolder);

        foreach ($this->databaseFolder as $k) {

            $script = "/^[a-zA-Z.\/]+(db[0-9]+_[a-zA-Z0-9_-]+).php$/";

            preg_match($script, $k, $match);

            echo $method.'-';

            $f = '\database\\' . $match[1];
            $c = new $f();
            $c->$method();

            usleep(500);
        }
    }

    public function install()
    {
        echo "Installing DB\n";
        $this->listFiles('install');
        echo "Finished\n";
    }

    public function uninstall()
    {
        echo "Deleting DB\n";
        $this->listFiles('uninstall', true);
        echo "Finished\n";
    }
}
