<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("penyewaan/add");
$can_edit = ACL::is_allowed("penyewaan/edit");
$can_view = ACL::is_allowed("penyewaan/view");
$can_delete = ACL::is_allowed("penyewaan/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "view-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data Information from Controller
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id; //Page id from url
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="view"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title"></h4>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div  class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="card animated fadeIn page-content">
                        <?php
                        $counter = 0;
                        if(!empty($data)){
                        $rec_id = (!empty($data['id_sewa']) ? urlencode($data['id_sewa']) : null);
                        $counter++;
                        ?>
                        <div class="mt-2">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-primary text-white">
                                    <svg id="barcode"></svg>
                                    <script>
                                        JsBarcode("#barcode", "<?php echo $data['id_sewa']; ?>", {
                                        format: "CODE39",
                                        lineColor: "#0D1117",
                                        width: 4,
                                        height: 40,
                                        displayValue: true
                                        });
                                    </script>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Tanggal Booking
                                    <span>
                                        <?php echo $data['tgl_booking']; ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Nama Peminjam
                                    <span><a size="sm"
                                        class="btn btn-sm btn-primary page-modal"
                                        href="<?php print_link("users/view/" . urlencode($data['nim'])) ?>">
                                        <i class="fa fa-eye"></i>
                                        <?php echo $data['users_nama_lengap'] ?>
                                    </a></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Merk Sepeda
                                    <span><a size="sm"
                                        class="btn btn-sm btn-primary page-modal"
                                        href="<?php print_link("data_sepeda/view/" . urlencode($data['id_sepeda'])) ?>">
                                        <i class="fa fa-eye"></i>
                                        <?php echo $data['data_sepeda_merk_sepeda'] ?>
                                    </a></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Tanggal Peminjaman
                                    <span>
                                        <?php echo $data['tgl_mulai']; ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Tanggal Pengembalian
                                    <span>
                                        <?php echo $data['tgl_akhir']; ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Keterlambatan
                                    <span class="badge badge-danger badge-pill">
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
                                        } else {
                                        echo $data['terlambat'] = $diffInDays . " Hari";
                                        }
                                        ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Denda Berlaku
                                    <span class="text-success font-weight-bold">
                                        <?php echo "Rp. ". number_format( $data['pinalti'], 0, ',', '.');?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Bayar denda
                                    <span class="text-success font-weight-bold">
                                        <?php $hasil=$diffInDays*$data['pinalti'];
                                        if ($hasil <= 0 ){
                                        echo 0;
                                        }else{
                                        echo "Rp. ". number_format( $hasil, 0, ',', '.');
                                        }
                                        ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center ">
                                    Sataus Peminjaman
                                    <span>
                                        <?php 
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
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <?php if($can_edit){ ?>
                        <a class="btn btn-sm btn-info"  href="<?php print_link("penyewaan/edit/$rec_id"); ?>">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <?php } ?>
                        <?php if($can_delete){ ?>
                        <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("penyewaan/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Are you sure you want to delete this record?" data-display-style="modal">
                            <i class="fa fa-times"></i> Delete
                        </a>
                        <?php } ?>
                    </div>
                    <?php
                    }
                    else{
                    ?>
                    <!-- Empty Record Message -->
                    <div class="text-muted p-3">
                        <i class="fa fa-ban"></i> No Record Found
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
