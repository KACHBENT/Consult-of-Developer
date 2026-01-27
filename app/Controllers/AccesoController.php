<?php

namespace App\Controllers;

use App\Models\ContactoModel;
use App\Models\PersonaModel;
use App\Models\RolesDetalleModel;
use App\Models\UsuarioModel;
use App\Models\TipoContactoModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

class AccesoController extends Controller
{


    public function loginShowForm(): string
    {
        // Ajusta al path real de tu vista
        return view('AccesosAdministrativo/Login');
    }
/*
    public function registerShowForm(): string
    {
        // Ajusta al path real de tu vista
        return view('AccesosAdministrativo/Register');
    }


    public function login(): RedirectResponse
    {
        if (!$this->request->is('post')) {
            return redirect()->to(site_url('acceso/login'));
        }

        // Valida por grupo de reglas
        if (!$this->validate('userLogin')) {
            $errors = array_values($this->validator->getErrors());
            return redirect()->back()->withInput()->with('toast_error', $errors);
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $db = \Config\Database::connect();

        // 🚀 Join correcto para obtener el correo del contacto
        $builder = $db->table('tbl_rel_usuario AS u')
            ->select('u.usuarioId, u.personaId, u.usuario_Nombre, u.usuario_Contrasena, u.usuario_Activo, c.contacto_Valor AS persona_Correo')
            ->join('tbl_ope_persona AS p', 'p.personaId = u.personaId')
            ->join('tbl_rel_contacto AS c', 'c.contactoId = p.contactoId')
            ->where('u.usuario_Nombre', $username)
            ->limit(1);

        $usuario = $builder->get()->getRowArray();

        if (!$usuario) {
            return redirect()->back()->withInput()->with('toast_error', 'Usuario no encontrado.');
        }

        if ((int) $usuario['usuario_Activo'] !== 1) {
            return redirect()->back()->withInput()->with('toast_error', 'Usuario inactivo.');
        }

        if (!password_verify($password, $usuario['usuario_Contrasena'])) {
            return redirect()->back()->withInput()->with('toast_error', 'Usuario o contraseña incorrectos.');
        }

        // ✅ Guarda correo y nombre de usuario en la sesión
        session()->regenerate();
        session()->set([
            'isLoggedIn' => true,
            'usuario' => [
                'usuarioId' => $usuario['usuarioId'],
                'personaId' => $usuario['personaId'],
                'usuario_Nombre' => $usuario['usuario_Nombre'],
                'persona_Correo' => $usuario['persona_Correo'],
            ],
        ]);

        return redirect()->to(site_url('/'))
            ->with('toast_success', '¡Bienvenido, ' . $usuario['usuario_Nombre'] . '!');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to(site_url('acceso/login'));
    }


    public function registerPerson(): RedirectResponse
    {
        $data = $this->request->getPost();

        // Validación por grupo (de Config\Validation)
        if (!$this->validate('userRegister')) {
            $errors = array_values($this->validator->getErrors());
            return redirect()->back()->withInput()->with('toast_error', $errors);
        }

        // Resolver tipos de contacto EMAIL / TELEFONO (una sola vez)
        $tipoModel = new TipoContactoModel();
        $tipos = $tipoModel
            ->whereIn('tipocontacto_Valor', ['EMAIL', 'TELEFONO'])
            ->findAll();

        $map = [];
        foreach ($tipos as $t) {
            $map[strtoupper($t['tipocontacto_Valor'])] = (int) $t['tipocontactoId'];
        }
        $TIPO_CONTACTO_EMAIL = $map['EMAIL'] ?? 1;
        $TIPO_CONTACTO_TEL = $map['TELEFONO'] ?? 2;

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1) CONTACTO (email) — necesitamos su ID para Persona
            $contactoModel = new ContactoModel();
            $contactoId = (int) $contactoModel->insert([
                'tipocontactoId' => $TIPO_CONTACTO_EMAIL,
                'contacto_Valor' => trim((string) $data['persona_Correo']),
                'contacto_Activo' => 1,
            ], true);

            if ($contactoId <= 0) {
                $dbError = $db->error();
                throw new \RuntimeException('No fue posible crear el contacto (email). DB: ' . ($dbError['message'] ?? ''));
            }

            // 1b) CONTACTO (teléfono) — no usamos su ID
            $contactoModel->insert([
                'tipocontactoId' => $TIPO_CONTACTO_TEL,
                'contacto_Valor' => trim((string) $data['persona_telefono']),
                'contacto_Activo' => 1,
            ]);

            // ==========================
            // 2) PERSONA (parche + diagnóstico)
            // ==========================
            $personaPayload = [
                'persona_Nombre' => trim((string) $data['persona_Nombre']),
                'persona_ApellidoPaterno' => trim((string) $data['persona_ApellidoPaterno']),
                'persona_ApellidoMaterno' => isset($data['persona_ApellidoMaterno']) && $data['persona_ApellidoMaterno'] !== ''
                    ? trim((string) $data['persona_ApellidoMaterno']) : null,
                'contactoId' => (int) $contactoId,
                'persona_Curp' => strtoupper((string) $data['persona_Curp']),
                'persona_Rfc' => strtoupper((string) $data['persona_Rfc']),
                'persona_FechaNacimiento' => (string) $data['persona_FechaNacimiento'],
                'persona_Activo' => 1,
            ];

            // BYPASS del filtro de allowedFields usando Query Builder:
            $db = \Config\Database::connect();
            $ok = $db->table('tbl_ope_persona')->insert($personaPayload);
            if (!$ok) {
                $err = $db->error();
                $sql = method_exists($db, 'getLastQuery') ? (string) $db->getLastQuery() : '(no last query)';
                throw new \RuntimeException('Fallo insert persona. SQL=' . $sql . ' | DB=' . ($err['message'] ?? ''));
            }
            $personaId = (int) $db->insertID();

            // 3) USUARIO
            $userModel = new UsuarioModel();

            // (extra; ya valida is_unique en reglas)
            $usuarioExiste = $userModel->select('usuarioId')
                ->where('usuario_Nombre', trim((string) $data['usuario_Nombre']))
                ->first();

            if ($usuarioExiste) {
                throw new \RuntimeException('El nombre de usuario ya existe.');
            }

            $clavePaseLista = date('Ymd') . random_int(10000, 99999); // tu DB lo soporta como VARCHAR(20)
            $passwordHash = password_hash((string) $data['password'], PASSWORD_DEFAULT);

            $usuarioId = $userModel->insert([
                'personaId' => $personaId,
                'usuario_Nombre' => trim((string) $data['usuario_Nombre']),
                'usuario_Contrasena' => $passwordHash,
                'usuario_ClavePaseLista' => (string) $clavePaseLista,
                'usuario_Activo' => 1,
            ], true);

            if (!$usuarioId) {
                $dbError = $db->error();
                throw new \RuntimeException('No fue posible crear el usuario. DB: ' . ($dbError['message'] ?? ''));
            }

            // 4) ROL por defecto (EMPLEADO = 2) si existe el modelo
            if (class_exists(RolesDetalleModel::class)) {
                $rolesDetalle = new RolesDetalleModel();
                $rolesDetalle->insert([
                    'usuarioId' => $usuarioId,
                    'rolesId' => 2,
                    'rolesDetalle_Activo' => 1,
                ]);
            }

            $db->transCommit();

            return redirect()
                ->to(site_url('acceso/login'))
                ->with('toast_success', 'Usuario registrado correctamente.')
                ->with('clave_pase_lista', $clavePaseLista);


        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('toast_error', $e->getMessage());
        }
    }
    
    public function pinShowForm(): string
    {
        return view('AccesosPaseLista/AccesoPaseLista');
    }

    public function pinVerify()
    {
        // Aceptar solo POST
        if (!$this->request->is('post')) {
            return redirect()
                ->to(site_url('acceso/access-assistance'))
                ->with('toast_error', 'Método no permitido.');
        }

        // Leer y sanear PIN
        $pin = trim((string) $this->request->getPost('pin'));

        // Validación básica del PIN
        if ($pin === '' || !ctype_digit($pin) || strlen($pin) < 4 || strlen($pin) > 20) {
            return redirect()
                ->back()
                ->withInput()
                ->with('toast_error', 'PIN inválido. Debe contener solo dígitos y mínimo 4 caracteres.');
        }

        $usuarioModel = new UsuarioModel();

        // Buscar usuario activo con ese PIN de pase de lista
        $user = $usuarioModel
            ->select('usuarioId, personaId, usuario_Nombre, usuario_ClavePaseLista')
            ->where('usuario_Activo', 1)
            ->where('usuario_ClavePaseLista', $pin)
            ->first();

        // Si no existe, aumentar contador de intentos
        if (!$user) {
            $tries = (int) (session()->get('pin_tries') ?? 0);
            session()->set('pin_tries', $tries + 1);

            return redirect()
                ->back()
                ->withInput()
                ->with('toast_error', 'PIN incorrecto.');
        }

        // Éxito: limpiar intentos y preparar sesión de pase de lista
        session()->remove('pin_tries');

        // Opcional pero recomendable: regenerar ID de sesión
        session()->regenerate();

        session()->set([
            'paseListaOK' => true,
            'paseListaTS' => time(),
            'paseListaUser' => [
                'usuarioId' => (int) $user['usuarioId'],
                'personaId' => (int) $user['personaId'],
                'usuario_Nombre' => (string) $user['usuario_Nombre'],
            ],
        ]);

        // Redirigir a la pantalla protegida por el filtro "pin"
        return redirect()
            ->to(site_url('acceso/register-assistance'))
            ->with('toast_success', 'PIN verificado. Puedes continuar.');
    }*/
}
