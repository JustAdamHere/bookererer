<?php require_once($_SERVER['DOCUMENT_ROOT']."/config.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/includes/functions.php");?>

<header class="navbar navbar-expand-md navbar-light d-print-none">
  <div class="container-xl">
    <?php
      if (login_restricted(1))
      {
      ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
          <span class="navbar-toggler-icon"></span>
        </button>
      <?php
      }
    ?>
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href="<?=$config['base_url'];?>">
        <img src="<?=$config['logo_url'];?>" width="110" height="32" alt="<?=$config['software_name'];?>" class="navbar-brand-image">
      </a>
    </h1>
    <div class="navbar-nav flex-row order-md-last">
      <?php
        if (login_valid())
        {
          ?>

          <?php
            $icon_style = "style=\"background-image: url('')\"";
            $name       = "Adam Blakey";
            $role       = "Administrator";

            // To temporarily hide settings and notifications, which are not yet implemented.
            $hide_settings = true;
          ?>

          <?php
            if (!$hide_settings)
            {
              ?>
              <div class="nav-item dropdown d-none d-md-flex me-3">
                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                  <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                  <span class="badge bg-red"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-card">
                  <div class="list-group list-group-flush list-group-hoverable">
                    <div class="list-group-item">
                      <div class="row align-items-center">
                        <div class="col-auto"><span class="badge bg-red"></span></div>
                        <div class="col-auto">
                          <a href="#">
                            <span class="avatar">AB</span>
                          </a>
                        </div>
                        <div class="col-auto">
                          <a href="#" class="text-body d-block">Adam Blakey (NSWO, Clarinet)</a>
                          <small class="text-wrap text-muted">Edited their attendance for 1st May to 'not attending'.</small>
                          <small class="d-block text-muted mt-n1">8 minutes ago</small>
                        </div>
                      </div>
                    </div>
                    <div class="list-group-item">
                      <div class="row align-items-center">
                        <div class="col-auto"><span class="badge bg-red"></span></div>
                        <div class="col-auto">
                          <a href="#">
                            <span class="avatar">BL</span>
                          </a>
                        </div>
                        <div class="col-auto">
                          <a href="#" class="text-body d-block">Bridget Langham (NSWO, Saxophone)</a>
                          <small class="text-wrap text-muted mt-n1">Edited their attendance for 1st May to 'attending'.</small>
                          <small class="d-block text-muted mt-n1">13 minutes ago</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
            }
          ?>

          <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
              <span class="avatar avatar-sm" <?=$icon_style;?>></span>
              <div class="d-none d-xl-block ps-2">
                <div><?=$name;?></div>
                <div class="mt-1 small text-muted"><?=$role;?></div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <?php
                if (!$hide_settings)
                {
                  ?>
                    <a href="#" class="dropdown-item">View notifications</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">Settings</a>
                  <?php
                }
              ?>
              <a href="<?=$config['base_url'];?>/logout.php?redirect_page=<?=urlencode($_SERVER["REQUEST_URI"]);?>" class="dropdown-item">Logout</a>
            </div>
          </div>
        <?php
        }
        else
        {
        ?>
            <a href="<?=$config['base_url'];?>/login.php?redirect_page=<?=urlencode($_SERVER["REQUEST_URI"]);?>" class="nav-link d-flex lh-1 text-reset p-0" aria-label="Login">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-login" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                <path d="M20 12h-13l3 -3m0 6l-3 -3" />
              </svg>
            </a>
        <?php
        }
        ?>
    </div>
  </div>
</header>