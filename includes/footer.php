<footer class="footer">
    <div class="container">
        <div class="footer-brand">SlideAI</div>
        <p class="footer-text">The premium AI-powered presentation generator.</p>
        <div class="footer-links">
            <a href="index.php#features">Features</a>
            <a href="index.php#how-it-works">How it Works</a>
            <a href="generator.php">Generate</a>
        </div>
        <div class="footer-copyright">
            &copy; <?php echo date('Y'); ?> SlideAI. All rights reserved.
        </div>
    </div>
</footer>

<!-- JS Dependencies -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Global JS -->
<script src="assets/js/main.js"></script>

<?php if(isset($extraScripts)) echo $extraScripts; ?>

</body>
</html>
