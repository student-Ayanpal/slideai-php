# 🪄 SlideAI: AI-Powered Presentation Generator

![SlideAI Banner](https://img.shields.io/badge/SlideAI-Intelligent_Presentations-7C3AED?style=for-the-badge&logo=powerpoint&logoColor=white)

SlideAI is a powerful, modern web application that generates fully-formatted PowerPoint (`.pptx`) presentations from a simple text prompt. Powered by the **Gemini API** and built with **PHP**, it automatically structures your ideas into engaging slides, selects relevant imagery, and dynamically formats the layout for a flawless, ready-to-download presentation.

## ✨ Features

- **🧠 AI Content Generation:** Just type a topic or prompt, and Gemini will generate comprehensive slide content, complete with titles, bullet points, and data charts.
- **🎨 Beautiful Layouts:** Includes dynamically generated slide templates such as Title, Bullet Lists, Bar/Pie/Line Charts, and Image-split layouts.
- **📐 Smart Auto-Formatting:** Features intelligent AutoFit capabilities and dynamic font scaling. No matter how much text the AI generates, it will seamlessly shrink and fit the slide boundaries without overlapping!
- **🖼️ Automated Imagery:** Integrates relevant, context-aware imagery to bring your slides to life.
- **⚡ Instant Download:** Renders a native `.pptx` file directly to the user's browser in seconds.

## 🚀 Getting Started

### Prerequisites
- PHP 8.1+
- Composer
- A Google Gemini API Key

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/YOUR_USERNAME/slideai-php.git
   cd slideai-php
   ```

2. **Install dependencies:**
   SlideAI uses [PHPOffice/PhpPresentation](https://github.com/PHPOffice/PHPPresentation) to generate native PowerPoint files.
   ```bash
   composer install
   ```

3. **Configure the Environment:**
   Copy the example environment file and add your Gemini API Key.
   ```bash
   cp .env.example .env
   ```
   *Open `.env` and set your `GEMINI_API_KEY`.*

4. **Run the Application:**
   Host the directory using a local server (like XAMPP, WAMP, or Laravel Valet), or run the PHP built-in server:
   ```bash
   php -S localhost:8000
   ```
   Visit `http://localhost:8000` in your browser!

## 🛠️ Built With
- **PHP 8** - Backend logic and API integrations
- **PHPOffice/PhpPresentation** - For native OpenXML `.pptx` generation
- **Gemini API** - Intelligent content creation
- **Vanilla JS & CSS** - Fast, responsive, glassmorphism UI

## 📝 License
This project is open-source and available under the [MIT License](LICENSE).
