     
          <hr>
          <footer class="text-center">
            <div class="mb-2">
              <small>
                © 2020 softwarejk by - <a target="_blank" rel="noopener noreferrer" href="https://github.com/user-juli?tab=repositories">
                  julii
                </a>
              </small>
            </div>
          </footer>
        
        </div>
      </main>
      <!-- page-content" -->
    </div>
    <!-- page-wrapper -->
    
  
    <script type="text/javascript" src="libs/js/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="libs/js/functions.js"></script>
  
    <script type="text/javascript">
      $(".sidebar-dropdown > a").click(function() {
        $(".sidebar-submenu").slideUp(200);
        if (
          $(this)
            .parent()
            .hasClass("active")
        ) {
          $(".sidebar-dropdown").removeClass("active");
          $(this)
            .parent()
            .removeClass("active");
        } else {
          $(".sidebar-dropdown").removeClass("active");
          $(this)
            .next(".sidebar-submenu")
            .slideDown(200);
          $(this)
            .parent()
            .addClass("active");
        }
      });

      $("#close-sidebar").click(function() {
        $(".page-wrapper").removeClass("toggled");
      });
      $("#show-sidebar").click(function() {
        $(".page-wrapper").addClass("toggled");
      });

    </script>
  
  </body>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>
 