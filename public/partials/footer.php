<footer class="footer mt-5 py-4 text-muted-2 h-">
  <div class="container d-flex justify-content-center align-items-center">
    <div class="d-flex align-items-center gap-2" style="min-height:48px">
      <span>Campus Hustle</span>
      <span>© <span id="year"></span></span>
    </div>
  </div>
</footer>

<script>
  document.getElementById('year').textContent = new Date().getFullYear();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/app.js"></script>
</body>
</html>