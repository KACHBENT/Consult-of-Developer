<?php

namespace App\Controllers;

use App\Models\MiembroModel;
use App\Models\ServicioModel;
use App\Models\MovimientoModel;
use CodeIgniter\HTTP\RedirectResponse;

class MiembrosController extends BaseController
{
    private function requireLogin(): ?RedirectResponse
    {
        if (!session('isLoggedIn')) {
            return redirect()->to(site_url('acceso/login'))->with('toast_error', 'Debes iniciar sesión.');
        }
        return null;
    }

    private function assertServicioPropio(int $idServicio): array|RedirectResponse
    {
        $servModel = new ServicioModel();
        $serv = $servModel->find($idServicio);

        if (!$serv) {
            return redirect()->to(site_url('servicios'))->with('toast_error', 'Servicio no encontrado.');
        }

        $user = session('usuario');
        if ((string) $user['rol'] !== 'ADMIN' && (int) $serv['id_usuario'] !== (int) $user['id_usuario']) {
            return redirect()->to(site_url('servicios'))->with('toast_error', 'No autorizado.');
        }

        return $serv;
    }

    public function index(int $idServicio): string|RedirectResponse
    {
        if ($r = $this->requireLogin()) return $r;
        $serv = $this->assertServicioPropio($idServicio);
        if ($serv instanceof RedirectResponse) return $serv;

        $model = new MiembroModel();
        $rows  = $model->where('id_servicio', $idServicio)->orderBy('id_miembro', 'DESC')->findAll();

        return view('Miembros/Index', [
            'servicio' => $serv,
            'miembros' => $rows,
        ]);
    }

    public function create(int $idServicio): string|RedirectResponse
    {
        if ($r = $this->requireLogin()) return $r;
        $serv = $this->assertServicioPropio($idServicio);
        if ($serv instanceof RedirectResponse) return $serv;

        return view('Miembros/Create', ['servicio' => $serv]);
    }

    public function store(int $idServicio): RedirectResponse
    {
        if ($r = $this->requireLogin()) return $r;
        $serv = $this->assertServicioPropio($idServicio);
        if ($serv instanceof RedirectResponse) return $serv;

        if (!$this->request->is('post')) {
            return redirect()->to(site_url("miembros/{$idServicio}"));
        }

        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]',
            'email'  => 'permit_empty|valid_email|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('toast_error', array_values($this->validator->getErrors()));
        }

        $model = new MiembroModel();
        $mov   = new MovimientoModel();
        $user  = session('usuario');

        $id = (int) $model->insert([
            'id_servicio'  => $idServicio,
            'nombre'       => trim((string) $this->request->getPost('nombre')),
            'puesto'       => (string) $this->request->getPost('puesto'),
            'especialidad' => (string) $this->request->getPost('especialidad'),
            'experiencia'  => (string) $this->request->getPost('experiencia'),
            'email'        => (string) $this->request->getPost('email'),
            'foto'         => (string) $this->request->getPost('foto'),
            'activo'       => 1,
        ], true);

        if ($id <= 0) {
            return redirect()->back()->withInput()->with('toast_error', 'No se pudo crear el miembro.');
        }

        $mov->log((int) $user['id_usuario'], 'MIEMBRO_CREATE', "Miembro creado ID={$id} servicio={$idServicio}");

        return redirect()->to(site_url("miembros/{$idServicio}"))->with('toast_success', 'Miembro creado.');
    }

    public function edit(int $idMiembro): string|RedirectResponse
    {
        if ($r = $this->requireLogin()) return $r;

        $model = new MiembroModel();
        $row   = $model->find($idMiembro);

        if (!$row) {
            return redirect()->to(site_url('servicios'))->with('toast_error', 'Miembro no encontrado.');
        }

        $serv = $this->assertServicioPropio((int) $row['id_servicio']);
        if ($serv instanceof RedirectResponse) return $serv;

        return view('Miembros/Edit', ['miembro' => $row, 'servicio' => $serv]);
    }

    public function update(int $idMiembro): RedirectResponse
    {
        if ($r = $this->requireLogin()) return $r;

        $model = new MiembroModel();
        $row   = $model->find($idMiembro);

        if (!$row) {
            return redirect()->to(site_url('servicios'))->with('toast_error', 'Miembro no encontrado.');
        }

        $serv = $this->assertServicioPropio((int) $row['id_servicio']);
        if ($serv instanceof RedirectResponse) return $serv;

        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]',
            'email'  => 'permit_empty|valid_email|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('toast_error', array_values($this->validator->getErrors()));
        }

        $ok = (bool) $model->update($idMiembro, [
            'nombre'       => trim((string) $this->request->getPost('nombre')),
            'puesto'       => (string) $this->request->getPost('puesto'),
            'especialidad' => (string) $this->request->getPost('especialidad'),
            'experiencia'  => (string) $this->request->getPost('experiencia'),
            'email'        => (string) $this->request->getPost('email'),
            'foto'         => (string) $this->request->getPost('foto'),
        ]);

        if (!$ok) {
            return redirect()->back()->withInput()->with('toast_error', 'No se pudo actualizar.');
        }

        $mov = new MovimientoModel();
        $mov->log((int) session('usuario.id_usuario'), 'MIEMBRO_UPDATE', "Miembro actualizado ID={$idMiembro}");

        return redirect()->to(site_url("miembros/" . (int) $row['id_servicio']))->with('toast_success', 'Miembro actualizado.');
    }

    public function toggle(int $idMiembro): RedirectResponse
    {
        if ($r = $this->requireLogin()) return $r;

        $model = new MiembroModel();
        $row   = $model->find($idMiembro);

        if (!$row) {
            return redirect()->to(site_url('servicios'))->with('toast_error', 'Miembro no encontrado.');
        }

        $serv = $this->assertServicioPropio((int) $row['id_servicio']);
        if ($serv instanceof RedirectResponse) return $serv;

        $nuevo = ((int) $row['activo'] === 1) ? 0 : 1;
        $model->update($idMiembro, ['activo' => $nuevo]);

        $mov = new MovimientoModel();
        $mov->log((int) session('usuario.id_usuario'), 'MIEMBRO_TOGGLE', "Miembro ID={$idMiembro} activo={$nuevo}");

        return redirect()->to(site_url("miembros/" . (int) $row['id_servicio']))
            ->with('toast_success', $nuevo ? 'Miembro activado.' : 'Miembro desactivado.');
    }
}
