<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/theme.light.css" id="theme-link">
<script>
    const toggleTheme = () => {
        const link = document.getElementById('theme-link');
        const isLight = link.getAttribute('href').includes('light');
        link.setAttribute('href', isLight ? '<?= BASE_URL ?>/assets/css/theme.dark.css' : '<?= BASE_URL ?>/assets/css/theme.light.css');
    };
</script>
