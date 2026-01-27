<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PinFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Si no hay sesión de pase lista, redirige
        if (! session()->get('paseListaOK')) {
            return redirect()->to(site_url('acceso/access-assistance'));
        }
        // ⚠️ Importante: no bloquear POST globalmente
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
