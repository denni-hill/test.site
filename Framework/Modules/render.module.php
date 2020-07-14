<?php
	class render
	{
		private
			$systemVars;
		
		function __construct()
		{
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

			$this->systemVars = array(
				'templates_dir' => $protocol . $_SERVER['HTTP_HOST'] . '/Templates',
				'logged_user' => $_SESSION['logged_user'],
			);

			$this->systemVars['url'] = array(
				'home' => '/',
			);

			$this->systemVars['current_url'] = $protocol . $_SERVER["HTTP_HOST"] . explode("/?", $_SERVER["REQUEST_URI"])[0];
			if(file_exists(TEMPLATES_DIR . '/Content/Css/' . ACTIVE_APP . '/' . ACTIVE_VIEW . '.css'))
			{
				$css_path = $protocol. $_SERVER["HTTP_HOST"] . "/Templates/Content/Css/" . ACTIVE_APP . "/" . ACTIVE_VIEW . ".css";
			}
			else
			{
				$css_path = $protocol . $_SERVER["HTTP_HOST"] . "/Templates/Content/Css/" . ACTIVE_APP . "/main.css";
			}
			$this->systemVars['css_path'] = $css_path;
		}

		public function getVars($vars)
		{
			foreach( $this->systemVars as $var => $value )
			{
				$vars[$var] = $value;
			}

			return $vars;
		}
	}

    class twig_extensions extends  \Twig_Extension
    {
        //Adding custom functions into twig
        public function getFunctions()
        {
            return array(
                new \Twig_SimpleFunction('is_admin', array($this, 'is_admin')),
                new \Twig_SimpleFunction('is_logged', array($this, 'is_logged'))
            );
        }

        public function is_admin()
        {
            if(isset($_SESSION['logged_user']))
                return $_SESSION['logged_user']['is_admin'] == "1";
            else return false;
        }

        public function is_logged()
        {
            return isset($_SESSION['logged_user']);
        }
    }

function render($view, $vars = array())
{
	$loader = new Twig_Loader_Filesystem(TEMPLATES_DIR);

	$twig = new Twig_Environment($loader, array(
		//'cache' => TWIG_CACHE_DIR,
	));

    $extension = new twig_extensions();

    $twig->addExtension($extension);

    $functions = $extension->getFunctions();
    foreach ($functions as $function) {
        $twig->addFunction($function);
    }

	$template = $twig->loadTemplate('Views/' . $view . '.html.twig');

	$r = new render();
	$vars = $r->getVars($vars);

	$template->display($vars);
}

function View($vars = array())
{
    render(ACTIVE_APP . '/' . ACTIVE_VIEW, $vars);
}