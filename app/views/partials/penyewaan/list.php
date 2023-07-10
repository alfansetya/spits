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
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="list"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container-fluid">
            <div class="row ">
                <div class="col ">
                    <?php if($can_add){ ?>
                    <a  class="btn btn btn-primary my-1" href="<?php print_link("penyewaan/add") ?>">
                        <i class="fa fa-plus"></i>                              
                        Add New Peminjaman 
                    </a>
                    <?php } ?>
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
                        <div class="card ">
                            <div class="card-header p-0 pt-2 px-2">
                                <ul class="nav  nav-tabs   ">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#TabPage-1-Page1" role="tab" aria-selected="true">
                                            <i class="fa fa-align-justify "></i> Semua
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " data-toggle="tab" href="#TabPage-1-Page2" role="tab" aria-selected="true">
                                            <i class="fa fa-hand-stop-o "></i> Pending
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " data-toggle="tab" href="#TabPage-1-Page3" role="tab" aria-selected="true">
                                            <i class="fa fa-close "></i> Ditolak
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " data-toggle="tab" href="#TabPage-1-Page4" role="tab" aria-selected="true">
                                            <i class="fa fa-check-square-o "></i> Diterima
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane show active fade" id="TabPage-1-Page1" role="tabpanel">
                                        <?php $this :: display_page_errors(); ?>
                                        <div  class=" animated fadeIn page-content">
                                            <div id="penyewaan-list-records">
                                                <div id="page-report-body" class="table-responsive">
                                                    <table class="table  table-striped table-sm text-center">
                                                        <thead class="table-header bg-light">
                                                            <tr>
                                                                <?php if($can_delete){ ?>
                                                                <th class="td-checkbox">
                                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                                        <input class="toggle-check-all custom-control-input" type="checkbox" />
                                                                        <span class="custom-control-label"></span>
                                                                    </label>
                                                                </th>
                                                                <?php } ?>
                                                                <th class="td-sno">#</th>
                                                                <th  class="td-id_sewa"> Kode Booking</th>
                                                                <th  class="td-tgl_booking"> Tanggal Booking</th>
                                                                <th  class="td-nim"> Nama Peminjam</th>
                                                                <th  class="td-id_sepeda"> Merk Sepeda</th>
                                                                <th  class="td-tgl_mulai"> Tanggal Pinjam</th>
                                                                <th  class="td-tgl_akhir"> Tanggal Pengembalian</th>
                                                                <th  class="td-status"> Progres</th>
                                                                <th  class="td-terlambat"> Terlambat</th>
                                                                <th  class="td-pinalti"> Denda Berlaku</th>
                                                                <th  class="td-total_denda"> Total Denda</th>
                                                                <th class="td-btn"></th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        if(!empty($records)){
                                                        ?>
                                                        <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                                            <!--record-->
                                                            <?php
                                                            $counter = 0;
                                                            foreach($records as $data){
                                                            $rec_id = (!empty($data['id_sewa']) ? urlencode($data['id_sewa']) : null);
                                                            $counter++;
                                                            ?>
                                                            <tr>
                                                                <?php if($can_delete){ ?>
                                                                <th class=" td-checkbox">
                                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                                        <input class="optioncheck custom-control-input" name="optioncheck[]" value="<?php echo $data['id_sewa'] ?>" type="checkbox" />
                                                                            <span class="custom-control-label"></span>
                                                                        </label>
                                                                    </th>
                                                                    <?php } ?>
                                                                    <th class="td-sno"><?php echo $counter; ?></th>
                                                                    <td class="td-id_sewa"> <span class="text-success font-weight-bold"><?php echo $data['id_sewa']; ?></span></td>
                                                                    <td class="td-tgl_booking"> <?php echo $data['tgl_booking']; ?></td>
                                                                    <td class="td-nim">
                                                                        <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("masterdetail/index/penyewaan/users/nim/" . urlencode($data['nim'])) ?>">
                                                                            <i class="fa fa-eye"></i> <?php echo $data['users_nama_lengap'] ?>
                                                                        </a>
                                                                    </td>
                                                                    <td class="td-id_sepeda">
                                                                        <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("data_sepeda/view/" . urlencode($data['id_sepeda'])) ?>">
                                                                            <i class="fa fa-eye"></i> <?php echo $data['data_sepeda_merk_sepeda'] ?>
                                                                        </a>
                                                                    </td>
                                                                    <td class="td-tgl_mulai"> <?php echo $data['tgl_mulai']; ?></td>
                                                                    <td class="td-tgl_akhir"> <?php echo $data['tgl_akhir']; ?></td>
                                                                    <td class="td-status"><?php 
                                                                        if($data['status'] == -1 ){?>
                                                                        <span class="badge badge-primary"><?php echo "Diterima"; ?></span>
                                                                        <?php } ?>
                                                                        <?php 
                                                                        if($data['status'] == 1){?>
                                                                        <span class="badge badge-danger"><?php echo "Ditolak"; ?></span>
                                                                        <?php } ?>
                                                                        <?php 
                                                                        if($data['status'] == "Pending"){?>
                                                                        <span class="badge badge-warning"><?php echo "Pending"; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="td-terlambat"><?php
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
                                                                    ?></td>
                                                                    <td class="td-pinalti"><span><?php echo "Rp. ". number_format( $data['pinalti'], 0, ',', '.');?></span></td>
                                                                    <td class="td-total_denda"> <span><?php $hasil=$diffInDays*$data['pinalti'];
                                                                        if ($hasil <= 0 ){
                                                                        echo 0;
                                                                        }else{
                                                                        echo "Rp. ". number_format( $hasil, 0, ',', '.');
                                                                        }
                                                                    ?></span></td>
                                                                    <td class="page-list-action td-btn">
                                                                        <div class="dropdown" >
                                                                            <button data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">
                                                                                <i class="fa fa-bars"></i> 
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <?php if($can_view){ ?>
                                                                                <a class="dropdown-item page-modal" href="<?php print_link("penyewaan/view/$rec_id"); ?>">
                                                                                    <i class="fa fa-eye"></i> View 
                                                                                </a>
                                                                                <?php } ?>
                                                                                <?php if($can_edit){ ?>
                                                                                <a class="dropdown-item page-modal" href="<?php print_link("penyewaan/edit/$rec_id"); ?>">
                                                                                    <i class="fa fa-edit"></i> Edit
                                                                                </a>
                                                                                <?php } ?>
                                                                                <?php if($can_delete){ ?>
                                                                                <a  class="dropdown-item record-delete-btn" href="<?php print_link("penyewaan/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Are you sure you want to delete this record?" data-display-style="modal">
                                                                                    <i class="fa fa-times"></i> Delete 
                                                                                </a>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php 
                                                                }
                                                                ?>
                                                                <!--endrecord-->
                                                            </tbody>
                                                            <tbody class="search-data" id="search-data-<?php echo $page_element_id; ?>"></tbody>
                                                            <?php
                                                            }
                                                            ?>
                                                        </table>
                                                        <?php 
                                                        if(empty($records)){
                                                        ?>
                                                        <h4 class="bg-light text-center border-top text-muted animated bounce  p-3">
                                                            <i class="fa fa-ban"></i> No record found
                                                        </h4>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                    if( $show_footer && !empty($records)){
                                                    ?>
                                                    <div class=" border-top mt-2">
                                                        <div class="row justify-content-center">    
                                                            <div class="col-md-auto justify-content-center">    
                                                                <div class="p-3 d-flex justify-content-between">    
                                                                    <?php if($can_delete){ ?>
                                                                    <button data-prompt-msg="Are you sure you want to delete these records?" data-display-style="modal" data-url="<?php print_link("penyewaan/delete/{sel_ids}/?csrf_token=$csrf_token&redirect=$current_page"); ?>" class="btn btn-sm btn-danger btn-delete-selected d-none">
                                                                        <i class="fa fa-times"></i> Delete Selected
                                                                    </button>
                                                                    <?php } ?>
                                                                    <div class="dropup export-btn-holder mx-1">
                                                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="fa fa-save"></i> Export
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <?php $export_print_link = $this->set_current_page_link(array('format' => 'print')); ?>
                                                                            <a class="dropdown-item export-link-btn" data-format="print" href="<?php print_link($export_print_link); ?>" target="_blank">
                                                                                <img src="<?php print_link('assets/images/print.png') ?>" class="mr-2" /> PRINT
                                                                                </a>
                                                                                <?php $export_pdf_link = $this->set_current_page_link(array('format' => 'pdf')); ?>
                                                                                <a class="dropdown-item export-link-btn" data-format="pdf" href="<?php print_link($export_pdf_link); ?>" target="_blank">
                                                                                    <img src="<?php print_link('assets/images/pdf.png') ?>" class="mr-2" /> PDF
                                                                                    </a>
                                                                                    <?php $export_word_link = $this->set_current_page_link(array('format' => 'word')); ?>
                                                                                    <a class="dropdown-item export-link-btn" data-format="word" href="<?php print_link($export_word_link); ?>" target="_blank">
                                                                                        <img src="<?php print_link('assets/images/doc.png') ?>" class="mr-2" /> WORD
                                                                                        </a>
                                                                                        <?php $export_csv_link = $this->set_current_page_link(array('format' => 'csv')); ?>
                                                                                        <a class="dropdown-item export-link-btn" data-format="csv" href="<?php print_link($export_csv_link); ?>" target="_blank">
                                                                                            <img src="<?php print_link('assets/images/csv.png') ?>" class="mr-2" /> CSV
                                                                                            </a>
                                                                                            <?php $export_excel_link = $this->set_current_page_link(array('format' => 'excel')); ?>
                                                                                            <a class="dropdown-item export-link-btn" data-format="excel" href="<?php print_link($export_excel_link); ?>" target="_blank">
                                                                                                <img src="<?php print_link('assets/images/xsl.png') ?>" class="mr-2" /> EXCEL
                                                                                                </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
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
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane  fade" id="TabPage-1-Page2" role="tabpanel">
                                                                <div class=" ">
                                                                    <?php  
                                                                    $this->render_page("penyewaan/page_pending/penyewaan.status/Pending?limit_count=25" , array( 'show_header' => false,'show_footer' => false )); 
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane  fade" id="TabPage-1-Page3" role="tabpanel">
                                                                <div class=" ">
                                                                    <?php  
                                                                    $this->render_page("penyewaan/page_ditolak/penyewaan.status/1?limit_count=25" , array( 'show_header' => false,'show_footer' => false )); 
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane  fade" id="TabPage-1-Page4" role="tabpanel">
                                                                <div class=" ">
                                                                    <?php  
                                                                    $this->render_page("penyewaan/page_diterima/penyewaan.status/-1?limit_count=25" , array( 'show_header' => false,'show_footer' => false )); 
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
