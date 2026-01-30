# CONSULTORIA DE DESARROLLO

Aplicación web desarrollada en PHP para la gestión y consulta de información.

## 🏗️ Arquitectura, Framework y Base de Datos

Arquitectura: MVC (Model - View - Controller)

Framework: CodeIgniter 4

Base de Datos: MySQL / MariaDB (configurable en .env)

## 📖 Descripción del Proyecto

Sistema web construido con CodeIgniter 4 que permite administrar datos de forma organizada, incluyendo generación de reportes en PDF mediante Dompdf.

## 🎯 Objetivo

Desarrollar un sistema web funcional aplicando buenas prácticas con CodeIgniter 4 y arquitectura MVC.

## 💻 Tecnologías Utilizadas

PHP 8.1+

CodeIgniter 4

MySQL / MariaDB

HTML, CSS, JavaScript

Composer

Dompdf (PDF)

## 📂 Estructura del Proyecto

```plaintext
📁 asistencia-system/
├─ 📁 app/                # Controladores, modelos y vistas (MVC)
│  ├─ 📁 Controllers/
│  ├─ 📁 Models/
│  └─ 📁 Views/
├─ 📁 public/             # Archivos accesibles desde el navegador
├─ 📁 writable/           # Archivos de logs y caché
├─ 📁 database/           # Scripts y backups de la base de datos
├─ .env                  # Configuración del entorno
└─ README.md            # Documentación del proyecto
