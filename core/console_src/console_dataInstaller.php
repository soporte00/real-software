<?php namespace core\console_src;


class console_dataInstaller
{

    use console_paths;

    private $filesInFolder = [];

    public function __construct()
    {
        $this->filesInFolder = glob($this->databaseFolder.'*');
    }

    private function listFiles($method, $reverse = false)
    {

        if ($reverse) $this->filesInFolder = array_reverse($this->filesInFolder);

        foreach ($this->filesInFolder as $k) {

            preg_match('/^\.\/([a-zA-Z.\/]+)(db[0-9]+_[a-zA-Z0-9_-]+).php$/', $k, $match);

            $f =  str_replace('/','\\',$match[1]).$match[2];

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
