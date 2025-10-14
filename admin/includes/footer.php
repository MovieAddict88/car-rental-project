</div>
    </div>
    <!-- /#page-content-wrapper -->
</div>
<!-- /#wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Menu toggle with overlay + animated hamburger
(function(){
  const toggleBtn = document.getElementById('menu-toggle');
  const wrapper = document.getElementById('wrapper');
  const overlay = document.getElementById('adminOverlay');
  const burger = toggleBtn ? toggleBtn.querySelector('.admin-hamburger') : null;
  if (!toggleBtn || !wrapper || !overlay) return;

  function openSidebar(){
    wrapper.classList.add('toggled');
    overlay.classList.add('active');
    if (burger) burger.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar(){
    wrapper.classList.remove('toggled');
    overlay.classList.remove('active');
    if (burger) burger.classList.remove('active');
    document.body.style.overflow = '';
  }

  toggleBtn.addEventListener('click', function(e){
    e.preventDefault();
    if (wrapper.classList.contains('toggled')) closeSidebar(); else openSidebar();
  });
  overlay.addEventListener('click', closeSidebar);
  document.addEventListener('keydown', (e)=>{ if (e.key === 'Escape') closeSidebar(); });
})();
</script>
</body>
</html>