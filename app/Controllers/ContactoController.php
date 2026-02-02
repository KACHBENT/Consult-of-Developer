<?php

namespace App\Controllers;

use App\Models\MovimientoModel;
use App\Models\ContactoModel; // Ajusta si tu tabla se llama distinto
use App\Libraries\Mailer;

class ContactoController extends BaseController
{
    /**
     * ✅ Define aquí tus 3 integrantes (miembros)
     * Cambia correos, nombres y roles.
     */
    private array $miembros = [
        [
            'id'    => 'dev1',
            'nombre'=> 'Integrante 1',
            'rol'   => 'Full Stack Developer',
            'email' => 'miembro1@gmail.com',
            'foto'  => 'img/team/dev1.png',
        ],
        [
            'id'    => 'dev2',
            'nombre'=> 'Integrante 2',
            'rol'   => 'Backend Developer',
            'email' => 'miembro2@gmail.com',
            'foto'  => 'img/team/dev2.png',
        ],
        [
            'id'    => 'dev3',
            'nombre'=> 'Integrante 3',
            'rol'   => 'Frontend / UI Developer',
            'email' => 'miembro3@gmail.com',
            'foto'  => 'img/team/dev3.png',
        ],
    ];

    public function index()
    {
        return view('contacto/index', [
            'miembros' => $this->miembros
        ]);
    }

    public function enviar()
    {
        $rules = [
            'nombre'    => 'required|min_length[3]|max_length[120]',
            'email'     => 'required|valid_email|max_length[190]',
            'asunto'    => 'required|min_length[3]|max_length[160]',
            'mensaje'   => 'required|min_length[10]|max_length[2000]',
            'miembroId' => 'required|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nombre    = trim($this->request->getPost('nombre'));
        $email     = strtolower(trim($this->request->getPost('email')));
        $asunto    = trim($this->request->getPost('asunto'));
        $mensaje   = trim($this->request->getPost('mensaje'));
        $miembroId = trim($this->request->getPost('miembroId'));

        // ✅ Validar que el miembro exista en tu lista
        $miembro = $this->findMiembro($miembroId);
        if (! $miembro) {
            return redirect()->back()->withInput()->with('errors', [
                'miembroId' => 'Selecciona un integrante válido.'
            ]);
        }

        // ✅ Chequeo MX del remitente (dominio válido)
        if (! $this->domainHasMx($email)) {
            return redirect()->back()->withInput()->with('errors', [
                'email' => 'El dominio del correo no parece válido (sin MX). Revisa el correo.'
            ]);
        }

        $mov = new MovimientoModel();
        $contactoModel = new ContactoModel();
        $mailer = new Mailer();

        // ✅ Token para verificación real
        $token = bin2hex(random_bytes(24));

        // ✅ Guardar registro (para poder verificar después con el link)
        $idContacto = (int) $contactoModel->insert([
            'nombre'             => $nombre,
            'email'              => $email,
            'asunto'             => $asunto,
            'mensaje'            => $mensaje,

            // destino
            'miembro_id'         => $miembro['id'],
            'miembro_nombre'     => $miembro['nombre'],
            'miembro_email'      => $miembro['email'],

            // verificación
            'token_verificacion' => $token,
            'verificado'         => 0,

            // trazabilidad
            'fecha_creacion'     => date('Y-m-d H:i:s'),
            'ip'                 => $this->request->getIPAddress(),
            'user_agent'         => substr((string)$this->request->getUserAgent(), 0, 250),
        ]);

        $mov->log(null, 'contacto_registrado', "ID: {$idContacto} | remitente: {$email} | a: {$miembro['email']}");

        // ✅ Enviar correo de verificación al REMITENTE
        $verifyUrl = base_url('contacto/verificar/' . $token);

        $html = view('contacto/email_verificacion', [
            'nombre'    => $nombre,
            'verifyUrl' => $verifyUrl,
            'miembro'   => $miembro,
        ]);

        $send = $mailer->send(
            $email,
            $nombre,
            'Confirma tu correo - Contacto',
            $html
        );

        if (! $send['ok']) {
            $mov->log(null, 'contacto_verificacion_error', "ID: {$idContacto} | error: {$send['error']}");
            return redirect()->back()->withInput()->with('errors', [
                'general' => 'No se pudo enviar el correo de verificación. ' . $send['error']
            ]);
        }

        $mov->log(null, 'contacto_verificacion_enviada', "ID: {$idContacto} | a: {$email}");

        return redirect()->to(base_url('contacto/gracias'));
    }

    public function gracias()
    {
        return view('contacto/gracias');
    }

    public function verificar(string $token)
    {
        $mov = new MovimientoModel();
        $contactoModel = new ContactoModel();
        $mailer = new Mailer();

        $contacto = $contactoModel->where('token_verificacion', $token)->first();

        if (! $contacto) {
            $mov->log(null, 'contacto_token_invalido', "token: {$token}");
            return view('contacto/verificado', ['ok' => false, 'msg' => 'Token inválido o expirado.']);
        }

        if ((int)$contacto['verificado'] === 1) {
            return view('contacto/verificado', ['ok' => true, 'msg' => 'Tu correo ya estaba verificado ✅']);
        }

        // ✅ marcar verificado
        $contactoModel->update($contacto['id_contacto'], [
            'verificado' => 1
        ]);

        $mov->log(null, 'contacto_verificado', "ID: {$contacto['id_contacto']} | remitente: {$contacto['email']}");

        // ✅ Ahora sí: mandar el mensaje al miembro seleccionado
        $html = view('contacto/email_mensaje_miembro', [
            'contacto' => $contacto
        ]);

        $send = $mailer->send(
            $contacto['miembro_email'],
            $contacto['miembro_nombre'],
            'Contacto: ' . $contacto['asunto'],
            $html,
            // Reply-To: para que el miembro responda directo al usuario
            $contacto['email'],
            $contacto['nombre']
        );

        if (! $send['ok']) {
            $mov->log(null, 'contacto_envio_miembro_error', "ID: {$contacto['id_contacto']} | error: {$send['error']}");
            return view('contacto/verificado', ['ok' => true, 'msg' => 'Correo verificado ✅ pero falló el envío al integrante.']);
        }

        $mov->log(null, 'contacto_envio_miembro_ok', "ID: {$contacto['id_contacto']} | a: {$contacto['miembro_email']}");

        return view('contacto/verificado', ['ok' => true, 'msg' => 'Correo verificado ✅ Tu mensaje fue enviado al integrante.']);
    }

    private function findMiembro(string $id): ?array
    {
        foreach ($this->miembros as $m) {
            if ($m['id'] === $id) return $m;
        }
        return null;
    }

    private function domainHasMx(string $email): bool
    {
        $domain = substr(strrchr($email, "@") ?: '', 1);
        if (! $domain) return false;

        if (function_exists('checkdnsrr') && @checkdnsrr($domain, 'MX')) return true;

        $mx = @dns_get_record($domain, DNS_MX);
        return is_array($mx) && count($mx) > 0;
    }
}
