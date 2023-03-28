<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="fmutama">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <li class="nav-divider">
                        Menu
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link active" href="fmutama.php"  aria-controls="submenu-1"><i class="fa fa-fw fa-user-circle">
                        </i>Dashboard <span class="badge badge-success">6</span></a>
                    </li>
                    
                    <li class='nav-item'>
                        <a class='nav-link' href='#' data-toggle='collapse' aria-expanded='false' data-target='#submenu-1' aria-controls='submenu-1'>
                        <i class='fa fa-fw fa-rocket'></i>Authorization</a>
                        
                        <div id='submenu-1' class='collapse submenu'>
                        <ul class='nav flex-column'>
                        <?php
                            $query = sqlsrv_query($conn,"select B.NmMenu,B.FormName from SysMsMenuGroupTrustee A 
                                                        inner join sysmenu B on A.KdMenu=B.KdMenu where B.Parent='2120000000' and A.KdGroup='".$_SESSION["groupuser"]."' ");
                            while($hasil = sqlsrv_fetch_array($query)){

                            echo "<li class='nav-item'><a class='nav-link' href='".$hasil['FormName']."'>".$hasil['NmMenu']."</a></li>";
                            }
                        ?>
                        </ul>
                    </li>

                    <li class='nav-item'>
                        <a class='nav-link' href='#' data-toggle='collapse' aria-expanded='false' data-target='#submenu-2' aria-controls='submenu-2'>
                        <i class='fa fa-fw fa-rocket'></i>Reports</a>
                        
                        <div id='submenu-2' class='collapse submenu'>
                        <ul class='nav flex-column'>
                        <?php
                            $query = sqlsrv_query($conn,"select B.NmMenu,B.FormName from SysMsMenuGroupTrustee A 
                                                         inner join sysmenu B on A.KdMenu=B.KdMenu where B.Parent='2130000000' 
                                                         and A.KdGroup='".$_SESSION["groupuser"]."' ");
                            while($hasil = sqlsrv_fetch_array($query)){

                            echo "<li class='nav-item'><a class='nav-link' href='".$hasil['FormName']."'>".$hasil['NmMenu']."</a></li>";
                            }
                        ?>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</div>