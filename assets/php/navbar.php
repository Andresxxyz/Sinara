<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="index.php" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="assets/img/logo.webp" alt="">
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="index.php" class="active">Início</a></li>
                <li class="dropdown"><a href="sobre_repositorio.php"><span>Sobre</span> <i
                            class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="sobre_repositorio.php">Sobre o Repositório</a></li>
                        <li><a href="sobre_campus.php">Sobre o Câmpus</a></li>
                    </ul>
                </li>   
                <li><a href="projetos.php">Projetos</a></li>
                <li><a href="orientadores.php">Orientadores</a></li>

        
                </li>        
                <li><a href="#footer">Contato</a></li>
                <?php if (isset($_SESSION['user_matricula'])): ?>
                    <li><a href="meu_perfil.php">Meu perfil</a></li>
                    <li><a href="javascript:void(0);" onclick="abrirConfirmacaoLogout()">Sair</a></li>
                    <div class="confirmarLogout" id="confirmarLogout">
                        <p style="color:#000;">Tem certeza que deseja sair?</p>
                        <div class="confirmarLogoutBtn">
                            <a href="assets/php/logout.php" class="btn-custom confLogout">Sim</a>
                            <a href="javascript:void(0);" onclick="fecharConfirmacaoLogout()" class="btn-custom">Cancelar</a>
                        </div>

                        
                        
                    </div>
                <?php endif; ?> 
                 <?php if (!isset($_SESSION['user_matricula'])): ?>
                    <li><a href="cadastro.php">Cadastro</a></li>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>   
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>