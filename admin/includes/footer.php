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

  const isSmallScreen = () => window.innerWidth < 992; // match CSS breakpoint

  function setOverlay(active){
    if (active) {
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    } else {
      overlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  function openSidebar(){
    if (isSmallScreen()) {
      // On small screens, .toggled means OPEN
      wrapper.classList.add('toggled');
      setOverlay(true);
    } else {
      // On large screens, .toggled means COLLAPSED, so remove it to open
      wrapper.classList.remove('toggled');
      setOverlay(false);
    }
    if (burger) burger.classList.add('active');
  }

  function closeSidebar(){
    if (isSmallScreen()) {
      // On small screens, remove to close
      wrapper.classList.remove('toggled');
      setOverlay(false);
    } else {
      // On large screens, add to collapse
      wrapper.classList.add('toggled');
      setOverlay(false);
    }
    if (burger) burger.classList.remove('active');
  }

  function isOpen(){
    return isSmallScreen() ? wrapper.classList.contains('toggled')
                           : !wrapper.classList.contains('toggled');
  }

  toggleBtn.addEventListener('click', function(e){
    e.preventDefault();
    if (isOpen()) closeSidebar(); else openSidebar();
  });
  overlay.addEventListener('click', closeSidebar);
  document.addEventListener('keydown', (e)=>{ if (e.key === 'Escape') closeSidebar(); });

  // Ensure consistent state on resize (hide overlay on large screens)
  window.addEventListener('resize', () => {
    if (!isSmallScreen()) setOverlay(false);
  });
})();
</script>
</body>
</html>