<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique SposaBella</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Boutique Sposa Bella" class="nav-logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/catalogo') }}">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#nosotros">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-auth" href="#">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Boutique Sposa Bella</h1>
            <p class="hero-subtitle">Descubre nuestra exclusiva colección de vestidos para todas tus ocasiones especiales</p>
            <a href="{{ url('/catalogo') }}" class="btn btn-main">Ver Catálogo</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-stars"></i>
                        </div>
                        <h3 class="feature-title">Calidad Premium</h3>
                        <p>Vestidos confeccionados con los mejores materiales y atención al detalle.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-heart"></i>
                        </div>
                        <h3 class="feature-title">Diseños Únicos</h3>
                        <p>Cada vestido está diseñado pensando en realzar tu belleza natural.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-gem"></i>
                        </div>
                        <h3 class="feature-title">Experiencia Exclusiva</h3>
                        <p>Asesoramiento personalizado para encontrar el vestido perfecto.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="nosotros" class="about-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('images/about-img.jpg') }}" alt="Nosotros" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <h2 class="section-title">Sobre Nosotros</h2>
                    <p class="section-text">
                        En Boutique SposaBella, nos dedicamos a hacer realidad
                    </p>
                    <p class="section-text">
                        Nuestro compromiso es brindar una experiencia única y personalizada, donde cada clienta encuentre el vestido perfecto que refleje su estilo y personalidad.
                    </p>
                    <div class="mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span>Asesoramiento personalizado</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span>Ajustes y modificaciones</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span>Diseños exclusivos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contacto" class="contact-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center section-title mb-5">Contáctanos</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="contact-info p-4 bg-white rounded shadow-sm">
                        <h3 class="h5 mb-4">Información de Contacto</h3>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-geo-alt-fill me-3 text-sposabella"></i>
                            <span>Zona 16 de julio, Av. Alfonso Ugarte, Edificio "Dallas"</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-telephone-fill me-3 text-sposabella"></i>
                            <span>+591 12345678</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope-fill me-3 text-sposabella"></i>
                            <span>info@sposabella.com</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock-fill me-3 text-sposabella"></i>
                            <span>Lun - Sáb: 9:00 AM - 7:00 PM</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="contact-form p-4 bg-white rounded shadow-sm">
                        <h3 class="h5 mb-4">Envíanos un mensaje</h3>
                        <form>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Nombre completo" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Correo electrónico" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" rows="4" placeholder="Mensaje" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-main">Enviar Mensaje</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container-fluid p-0">
            <div class="map-container" style="height: 400px;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.4053590147447!2d-68.2019893!3d-16.4952673!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915edf63438b7fa7%3A0x55e57a2e7c587b3c!2sAv.%20Alfonso%20Ugarte%2C%20La%20Paz!5e0!3m2!1ses!2sbo!4v1635789012345!5m2!1ses!2sbo"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Boutique Sposa Bella</h5>
                    <p>Tu destino para vestidos exclusivos y elegantes.</p>
                </div>
                <div class="col-md-4">
                    <h5>Contacto</h5>
                    <p>
                        <i class="bi bi-geo-alt text-sposabella"></i> Av. Principal #123<br>
                        <i class="bi bi-telephone text-sposabella"></i> +591 12345678<br>
                        <i class="bi bi-envelope text-sposabella"></i> info@sposabella.com
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>Síguenos</h5>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
