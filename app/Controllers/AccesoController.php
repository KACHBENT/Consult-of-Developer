<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\HTTP\RedirectResponse;

class AccesoController extends BaseController
{
    public function loginShowForm(): string
    {
        return view('AccesosAdministrativo/Login');
    }

    public function registerShowForm(): string
    {
        return view('AccesosAdministrativo/Registro_Persona');
    }

    public function login(): RedirectResponse
    {
        if (!$this->request->is('post')) {
            return redirect()->to(site_url('acceso/login'));
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[100]',
            'password' => 'required|min_length[6]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('toast_error', array_values($this->validator->getErrors()));
        }

        $username = trim((string) $this->request->getPost('username'));
        $pass     = (string) $this->request->getPost('password');

        $usuarioModel = new UsuarioModel();


        if (str_contains($username, '@')) {
            $user = $usuarioModel->where('email', $username)->first();
        } else {
            $user = $usuarioModel->where('nombre', $username)->first();
        }

        if (!$user) {
            return redirect()->back()->withInput()->with('toast_error', 'Usuario no encontrado.');
        }

        if ((int) $user['estado'] !== 1) {
            return redirect()->back()->withInput()->with('toast_error', 'Usuario inactivo.');
        }

        if (!password_verify($pass, (string) $user['password'])) {
            return redirect()->back()->withInput()->with('toast_error', 'Usuario o contraseña incorrectos.');
        }

        session()->regenerate();
        session()->set([
            'isLoggedIn' => true,
            'usuario' => [
                'id_usuario'   => (int) $user['id_usuario'],
                'id_categoria' => $user['id_categoria'] !== null ? (int) $user['id_categoria'] : null,
                'nombre'       => (string) $user['nombre'],
                'email'        => (string) $user['email'],
                'rol'          => (string) $user['rol'], // ADMIN | CLIENTE
            ],
        ]);

        return redirect()->to(site_url('/'))
            ->with('toast_success', '¡Bienvenido, ' . $user['nombre'] . '!');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to(site_url('acceso/login'));
    }

  public function register(): RedirectResponse
{
    if (!$this->request->is('post')) {
        return redirect()->to(site_url('acceso/register'));
    }

    $rules = [
        'nombre'        => 'required|min_length[3]|max_length[100]',
        'email'         => 'required|valid_email|max_length[100]|is_unique[usuarios.email]',
        'password'      => 'required|min_length[6]|max_length[255]',
        'password_conf' => 'required|matches[password]',
        'id_categoria'  => 'permit_empty|is_natural_no_zero',
        'rol'           => 'permit_empty|in_list[ADMIN,CLIENTE]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
            ->withInput()
            ->with('toast_error', array_values($this->validator->getErrors()));
    }

    $usuarioModel = new UsuarioModel();

    $rol = strtoupper(trim((string) $this->request->getPost('rol')));
    if ($rol === '') {
        $rol = 'CLIENTE';
    }

    $idCategoriaRaw = trim((string) $this->request->getPost('id_categoria'));

    $payload = [
        'id_categoria' => $idCategoriaRaw !== '' ? (int) $idCategoriaRaw : null,
        'nombre'       => trim((string) $this->request->getPost('nombre')),
        'email'        => trim((string) $this->request->getPost('email')),
        'password'     => (string) $this->request->getPost('password'), // el Model lo hashea
        'rol'          => $rol,
        'estado'       => 1,
    ];

    $id = (int) $usuarioModel->insert($payload, true);

    if ($id <= 0) {
        return redirect()->back()
            ->withInput()
            ->with('toast_error', 'No se pudo registrar el usuario.');
    }

    return redirect()->to(site_url('acceso/login'))
        ->with('toast_success', 'Usuario registrado correctamente. Ahora puedes iniciar sesión.');
}

}
