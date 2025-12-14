# BR Simple Image Gallery Plugin (Joomla 6)

![Joomla 6](https://img.shields.io/badge/Joomla-6.x-green?style=flat-square&logo=joomla) ![License](https://img.shields.io/badge/license-GPLv2-blue?style=flat-square)

**[English]** A lightweight, template-proof image gallery plugin for Joomla 6.  
**[Português]** Um plugin de galeria de imagens leve e à prova de templates para Joomla 6.

---

## 🇺🇸 English Description

**BR Simple Image Gallery** is a no-nonsense image gallery plugin built specifically for **Joomla 6**.

It focuses on simplicity and stability. Using a **"Stand-alone CSS"** architecture, it ignores your site's template styles to deliver a clean, responsive grid layout that works perfectly inside any Article. It automatically scans the folder, generates thumbnails, and includes a built-in Lightbox for viewing full-size images.

### 🚀 Key Features

* **Joomla 6 Native:** Optimized for the modern Joomla core.
* **Template-Proof:** Isolated CSS grid system that won't break regardless of your theme.
* **Auto-Thumbnails:** Automatically creates a responsive grid from a folder of images.
* **Built-in Lightbox:** Smooth, touch-friendly image zooming (no extra libraries needed).
* **Simple Syntax:** Use the classic `{gallery}` syntax.
* **Fast & Lightweight:** Minimal CSS/JS footprint.

### 📦 Installation

1.  Download the **`plg_br_simple_image_gallery`** ZIP package.
2.  Log in to your Joomla 6 Administrator Panel.
3.  Go to **System** -> **Install** -> **Extensions**.
4.  Upload and Install the package.
5.  **Crucial Step:** Go to **System** -> **Plugins**, search for "BR Simple Image Gallery", and **Enable** it.

### 🛠️ How to Use

1.  Create a folder inside your **Media Manager** (inside the `images` directory).
    * *Example:* `images/my-trip`
2.  Upload your photos there.
3.  Open any Article and use the shortcode wrapping the folder name (relative to `images/`):
    ```text
    {gallery}my-trip{/gallery}
    ```
4.  Save and view. Your responsive gallery is ready!

---

## 🇧🇷 Descrição em Português

**BR Simple Image Gallery** é um plugin de galeria de imagens direto ao ponto, construído especificamente para **Joomla 6**.

O foco é simplicidade e estabilidade. Utilizando uma arquitetura de **"CSS Stand-alone" (Isolado)**, ele ignora os estilos do template do seu site para entregar um layout de grade limpo e responsivo, que funciona perfeitamente dentro de qualquer Artigo. Ele escaneia a pasta automaticamente, gera miniaturas e inclui um Lightbox nativo para visualização das imagens.

### 🚀 Principais Recursos

* **Nativo do Joomla 6:** Otimizado para o núcleo moderno do Joomla.
* **À Prova de Templates:** Sistema de grid CSS isolado que não quebra, independente do seu tema.
* **Miniaturas Automáticas:** Cria automaticamente uma grade responsiva a partir de uma pasta de imagens.
* **Lightbox Integrado:** Visualização de imagem suave e compatível com toque (sem bibliotecas pesadas).
* **Sintaxe Simples:** Usa a sintaxe clássica `{gallery}`.
* **Rápido e Leve:** Uso mínimo de CSS/JS.

### 📦 Instalação

1.  Baixe o pacote ZIP **`plg_br_simple_image_gallery`**.
2.  Acesse o Painel Administrativo do seu Joomla 6.
3.  Vá em **System** (Sistema) -> **Install** (Instalar) -> **Extensions** (Extensões).
4.  Faça o upload e instale o pacote.
5.  **Passo Importante:** Vá em **System** (Sistema) -> **Plugins**, procure por "BR Simple Image Gallery" e **Habilite-o**.

### 🛠️ Como Usar

1.  Crie uma pasta no seu **Gerenciador de Mídia** (dentro do diretório `images`).
    * *Exemplo:* `images/minha-viagem`
2.  Faça o upload das suas fotos para lá.
3.  Abra qualquer Artigo e use o shortcode envolvendo o nome da pasta (relativo a `images/`):
    ```text
    {gallery}minha-viagem{/gallery}
    ```
4.  Salve e visualize. Sua galeria responsiva está pronta!

---

### 📝 License
Copyright (c) 2025 Janderson Moreira.  
Licensed under the GNU General Public License version 2 or later.
