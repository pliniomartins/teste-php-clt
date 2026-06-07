<?php

class Controller
{
    public $request;

    public function __construct()
    {
        $this->request = new Request;
    }

    public function view($arquivo, $array = null)
    {
        if (!is_null($array)) {
            foreach ($array as $var => $value) {
                ${$var} = $value;
            }
        }
        ob_start();
        include "{$arquivo}.php";
        ob_flush();
    }

    /**
     * Salva uma mensagem flash na sessão para exibir na próxima requisição
     */
    protected function flash(string $tipo, string $mensagem): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem' => $mensagem];
    }

    /**
     * Retorna e limpa a mensagem flash da sessão
     */
    public static function getFlash(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Redireciona para uma URL
     */
    protected function redirecionar(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
