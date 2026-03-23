# Sistema de Constancia de Residencia 🏠

Este proyecto es un sistema de gestión desarrollado con **Laravel** y **Livewire**, diseñado para ejecutarse localmente utilizando **Laragon**.

---

## 🛠 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:
* **Laragon** (Recomendado)
* **PHP >= 8.1**
* **Composer**
* **Node.js & NPM**

---

## 🚀 Guía de Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

### 1. Ubicación del Proyecto
Mueve la carpeta del proyecto a tu directorio de trabajo de Laragon:
`C:\laragon\www\constancia-residencia`

### 2. Iniciar Servicios
Abre el panel de **Laragon** y presiona **"Start All"** para activar Apache y MySQL.

### 3. Configuración del Entorno (.env)
Crea una copia del archivo `.env.example` y renómbralo a `.env`. Configura las siguientes variables:

```env
APP_URL=[http://constancia-residencia.test](http://constancia-residencia.test)
DB_DATABASE=constanciaresidencia
DB_USERNAME=root
DB_PASSWORD=
