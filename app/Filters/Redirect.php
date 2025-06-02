<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Redirect implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
        $session = session();
        if ($session->get('isLoggedIn')) {
            return redirect()->to('/produk');
        }
    }
}
