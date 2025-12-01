<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área do Paciente - Gov.br Saúde</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="../View/CSS/global.css">
    <link rel="stylesheet" href="../View/CSS/dashboard.css">
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-white border-b border-gray-200 py-3 px-4 lg:px-8 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="font-bold text-xl text-gray-700 select-none">
                <span class="text-blue-700">gov.br</span> <span class="font-normal text-gray-500">| Saúde</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="hidden md:block text-sm text-gray-600">Olá, <strong><?php echo htmlspecialchars($nomeUsuario); ?></strong></span>
                <div class="h-10 w-10 rounded-full bg-blue-100 text-gov-blue flex items-center justify-center font-bold border border-blue-200 text-sm">
                    <?php echo $iniciais; ?>
                </div>
                <a href="login.php" class="text-sm text-red-600 font-semibold hover:underline ml-2">Sair</a>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <!-- Carrossel -->
        <section class="w-full bg-gray-100 mb-8 py-6">
            <div class="max-w-5xl mx-auto relative px-4">
                <!-- Slide 1 -->
                <div class="carousel-item active w-full h-64 md:h-80 relative overflow-hidden rounded-lg shadow-md bg-[#dbe830]">
                    <img src="imagens/campanha_vascinacao.png" alt="Vacinação" class="w-full h-full object-contain">
                </div>
                <!-- Slide 2 -->
                <div class="carousel-item w-full h-64 md:h-80 relative overflow-hidden rounded-lg shadow-md">
                    <div class="absolute inset-0 bg-blue-800 flex items-center justify-center">
                        <div class="text-center text-white px-4">
                            <h2 class="text-4xl font-bold mb-2">COMBATE À DENGUE</h2>
                            <button class="border-2 border-white px-6 py-2 rounded-full font-bold hover:bg-white hover:text-blue-800 transition">Ver orientações</button>
                        </div>
                    </div>
                </div>
                <!-- Slide 3 -->
                <div class="carousel-item w-full h-64 md:h-80 relative overflow-hidden rounded-lg shadow-md">
                    <div class="absolute inset-0 bg-green-600 flex items-center justify-center">
                        <div class="text-center text-white px-4">
                            <h2 class="text-4xl font-bold mb-2">AGENDAMENTO ONLINE</h2>
                            <a href="agendamento.php" class="bg-white text-green-700 px-6 py-2 rounded-full font-bold hover:bg-gray-100 transition">Agendar agora</a>
                        </div>
                    </div>
                </div>
                <!-- Navegação -->
                <div class="absolute bottom-4 w-full text-center z-20">
                    <span class="dot active" onclick="currentSlide(1)"></span>
                    <span class="dot" onclick="currentSlide(2)"></span>
                    <span class="dot" onclick="currentSlide(3)"></span>
                </div>
            </div>
        </section>

        <!-- Grid de Serviços -->
        <section class="max-w-7xl mx-auto px-4 pb-16">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gov-blue mb-2">Navegue por categoria</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php if (count($listaServicos) > 0): ?>
                    <?php foreach ($listaServicos as $servico): ?>
                        <a href="<?php echo htmlspecialchars($servico['link_destino']); ?>" class="service-card bg-white p-8 rounded shadow-sm flex flex-col items-center text-center h-64 justify-center cursor-pointer group border border-gray-100">
                            <div class="icon-container text-gov-blue text-5xl mb-6 transition-colors">
                                <i class="<?php echo htmlspecialchars($servico['icone_class']); ?>"></i>
                            </div>
                            <h3 class="card-title text-gov-blue font-bold text-sm uppercase tracking-wide group-hover:text-blue-800">
                                <?php echo htmlspecialchars($servico['titulo']); ?>
                            </h3>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-4 text-center py-10 bg-yellow-50 border border-yellow-200 rounded text-yellow-800">
                        Nenhum serviço encontrado.
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        let slideIndex = 0;
        showSlides();
        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("carousel-item");
            let dots = document.getElementsByClassName("dot");
            if (slides.length === 0) return;
            for (i = 0; i < slides.length; i++) { slides[i].style.display = "none"; slides[i].classList.remove("active"); }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1}    
            for (i = 0; i < dots.length; i++) { dots[i].className = dots[i].className.replace(" active", ""); }
            slides[slideIndex-1].style.display = "block";  
            slides[slideIndex-1].classList.add("active");
            dots[slideIndex-1].className += " active";
            setTimeout(showSlides, 5000); 
        }
        function currentSlide(n) { slideIndex = n - 1; }
    </script>
</body>
</html>