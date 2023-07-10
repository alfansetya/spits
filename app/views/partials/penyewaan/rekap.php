<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("penyewaan/add");
$can_edit = ACL::is_allowed("penyewaan/edit");
$can_view = ACL::is_allowed("penyewaan/view");
$can_delete = ACL::is_allowed("penyewaan/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "list-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data From Controller
$view_data = $this->view_data;
$records = $view_data->records;
$record_count = $view_data->record_count;
$total_records = $view_data->total_records;
$field_name = $this->route->field_name;
$field_value = $this->route->field_value;
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="list"  data-display-type="grid" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container-fluid">
            <div class="row ">
                <div class="col ">
                    <h4 >Laporan</h4>
                </div>
                <div class="col-sm-3 ">
                </div>
                <div class="col-sm-4 ">
                    <form  class="search" action="<?php print_link('penyewaan'); ?>" method="get">
                        <div class="input-group">
                            <input value="<?php echo get_value('search'); ?>" class="form-control" type="text" name="search"  placeholder="Search" />
                                <div class="input-group-append">
                                    <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 comp-grid">
                        <div class="">
                            <!-- Page bread crumbs components-->
                            <?php
                            if(!empty($field_name) || !empty($_GET['search'])){
                            ?>
                            <hr class="sm d-block d-sm-none" />
                            <nav class="page-header-breadcrumbs mt-2" aria-label="breadcrumb">
                                <ul class="breadcrumb m-0 p-1">
                                    <?php
                                    if(!empty($field_name)){
                                    ?>
                                    <li class="breadcrumb-item">
                                        <a class="text-decoration-none" href="<?php print_link('penyewaan'); ?>">
                                            <i class="fa fa-angle-left"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <?php echo (get_value("tag") ? get_value("tag")  :  make_readable($field_name)); ?>
                                    </li>
                                    <li  class="breadcrumb-item active text-capitalize font-weight-bold">
                                        <?php echo (get_value("label") ? get_value("label")  :  make_readable(urldecode($field_value))); ?>
                                    </li>
                                    <?php 
                                    }   
                                    ?>
                                    <?php
                                    if(get_value("search")){
                                    ?>
                                    <li class="breadcrumb-item">
                                        <a class="text-decoration-none" href="<?php print_link('penyewaan'); ?>">
                                            <i class="fa fa-angle-left"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item text-capitalize">
                                        Search
                                    </li>
                                    <li  class="breadcrumb-item active text-capitalize font-weight-bold"><?php echo get_value("search"); ?></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </nav>
                            <!--End of Page bread crumbs components-->
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <div  class="">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-md-12 comp-grid">
                        <?php $this :: display_page_errors(); ?>
                        <div  class=" animated fadeIn page-content">
                            <div id="penyewaan-rekap-records">
                                <?php
                                if(!empty($records)){
                                ?>
                                <div id="page-report-body">
                                    <div class="row sm-gutters page-data" id="page-data-<?php echo $page_element_id; ?>">
                                        <!--record-->
                                        <?php
                                        $counter = 0;
                                        foreach($records as $data){
                                        $rec_id = (!empty($data['id_sewa']) ? urlencode($data['id_sewa']) : null);
                                        $counter++;
                                        ?>
                                        <div class="col-sm-4">
                                            <div class="bg-light p-2 mb-3 animated bounceIn">
                                                <span class="text-success font-weight-bold"><?php echo $data['id_sewa']; ?></span>
                                                <div class="mb-2">  
                                                    <span class="font-weight-light text-muted ">
                                                        Tanggal Booking:  
                                                    </span>
                                                <?php echo $data['tgl_booking']; ?></div>
                                                <div class="mb-2">  
                                                    <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("users/view/" . urlencode($data['nim'])) ?>">
                                                        <i class="fa fa-eye"></i> <?php echo $data['users_nama_lengap'] ?>
                                                    </a>
                                                </div>
                                                <div class="mb-2">  
                                                    <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("data_sepeda/view/" . urlencode($data['id_sepeda'])) ?>">
                                                        <i class="fa fa-eye"></i> <?php echo $data['data_sepeda_merk_sepeda'] ?>
                                                    </a>
                                                </div>
                                                <div class="mb-2">  
                                                    <span class="font-weight-light text-muted ">
                                                        Jadwal Pengembalian:  
                                                    </span>
                                                <?php echo $data['tgl_akhir']; ?></div>
                                                <div class="mb-2">  
                                                    <span class="font-weight-light text-muted ">
                                                        Tanggal Pengembalian:  
                                                    </span>
                                                <?php echo $data['pemgembalian_tgl_pengembalian']; ?></div>
                                                <?php
                                                // Mengatur zona waktu
                                                date_default_timezone_set('Asia/Jakarta');
                                                // Tanggal awal dan tanggal akhir
                                                $startDate = $data['tgl_akhir'];
                                                $endDate = date_now();
                                                // Menghitung selisih hari
                                                $startTimestamp = strtotime($startDate);
                                                $endTimestamp = strtotime($endDate);
                                                $diff = $endTimestamp - $startTimestamp;
                                                $diffInDays = round($diff / (60 * 60 * 24));
                                                // Menampilkan selisih hari
                                                if ($diffInDays <= "0") {
                                                echo 0;
                                                }else{
                                                echo $data['terlambat']= $diffInDays. " Hari";
                                                }
                                                ?>
                                                <span><?php echo "Rp. ". number_format( $data['pinalti'], 0, ',', '.');?></span>
                                                <span><?php $hasil=$diffInDays*$data['pinalti'];
                                                    if ($hasil <= 0 ){
                                                    echo 0;
                                                    }else{
                                                    echo "Rp. ". number_format( $hasil, 0, ',', '.');
                                                    }
                                                ?></span>
                                                <div class="mb-2">  
                                                    <span class="font-weight-light text-muted ">
                                                        Keterangan:  
                                                    </span>
                                                <?php echo $data['pemgembalian_keterangan']; ?></div>
                                                <div class="mb-2">  <?php Html :: check_button($data['pemgembalian_status_pengembalian'], "1") ?></div>
                                            </div>
                                        </div>
                                        <?php 
                                        }
                                        ?>
                                        <!--endrecord-->
                                    </div>
                                    <div class="row sm-gutters search-data" id="search-data-<?php echo $page_element_id; ?>"></div>
                                    <div>
                                    </div>
                                </div>
                                <?php
                                if($show_footer == true){
                                ?>
                                <div class=" border-top mt-2">
                                    <div class="row justify-content-center">    
                                        <div class="col-md-auto">   
                                        </div>
                                        <div class="col">   
                                            <?php
                                            if($show_pagination == true){
                                            $pager = new Pagination($total_records, $record_count);
                                            $pager->route = $this->route;
                                            $pager->show_page_count = true;
                                            $pager->show_record_count = true;
                                            $pager->show_page_limit =true;
                                            $pager->limit_count = $this->limit_count;
                                            $pager->show_page_number_list = true;
                                            $pager->pager_link_range=5;
                                            $pager->render();
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                }
                                else{
                                ?>
                                <div class="text-muted  animated bounce p-3">
                                    <h4><i class="fa fa-ban"></i> No record found</h4>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
