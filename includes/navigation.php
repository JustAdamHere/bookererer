<?php
  //if(login_restricted(1))
  if (false)
  {
?>
  <div class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
      <div class="navbar navbar-light">
        <div class="container-xl">
          <ul class="navbar-nav">
            <?php
              $menu_items = array
              (
                array
                (
                  "name" => "Admin overview",
                  "page" => $config["base_url"]."/admin.php",
                  "icon" => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>'
                ),
                array
                (
                  "name" => "Notifications",
                  "page" => $config["base_url"]."/notifications.php",
                  "icon" => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>'
                ),
                array
                (
                  "name" => "Ensembles",
                  "page" => $config["base_url"]."/ensembles.php",
                  "icon" => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="6" cy="17" r="3" /><circle cx="16" cy="17" r="3" /><polyline points="9 17 9 4 19 4 19 17" /><line x1="9" y1="8" x2="19" y2="8" /></svg>'
                )
              );

              foreach($menu_items as $menu_item)
              {
                $active_page = ($menu_item["page"] == "./".basename($_SERVER['PHP_SELF']))?"active":"";
                ?>
                  <li class="nav-item <?=$active_page;?>">
                    <a class="nav-link" href="<?=$menu_item["page"];?>" >
                      <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <?=$menu_item["icon"];?>
                      </span>
                      <span class="nav-link-title">
                        <?=$menu_item["name"];?>
                      </span>
                    </a>
                  </li>
                <?php
              }
            ?>            
          </ul>
        </div>
      </div>
    </div>
  </div>
<?php
  }
?>