<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("pemgembalian/add");
$can_edit = ACL::is_allowed("pemgembalian/edit");
$can_view = ACL::is_allowed("pemgembalian/view");
$can_delete = ACL::is_allowed("pemgembalian/delete");
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
                    <h4 class="record-title">View  Pemgembalian</h4>
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
                        $rec_id = (!empty($data['id_pengembalian']) ? urlencode($data['id_pengembalian']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-id_pengembalian">
                                        <th class="title"> Id Pengembalian: </th>
                                        <td class="value"> <?php echo $data['id_pengembalian']; ?></td>
                                    </tr>
                                    <tr  class="td-tgl_pengembalian">
                                        <th class="title"> Tgl Pengembalian: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['tgl_pengembalian']; ?>" 
                                                data-pk="<?php echo $data['id_pengembalian'] ?>" 
                                                data-url="<?php print_link("pemgembalian/editfield/" . urlencode($data['id_pengembalian'])); ?>" 
                                                data-name="tgl_pengembalian" 
                                                data-title="Enter Tanggal Pengembalian" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['tgl_pengembalian']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-keterangan">
                                        <th class="title"> Keterangan: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-pk="<?php echo $data['id_pengembalian'] ?>" 
                                                data-url="<?php print_link("pemgembalian/editfield/" . urlencode($data['id_pengembalian'])); ?>" 
                                                data-name="keterangan" 
                                                data-title="Enter Keterangan" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="textarea" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['keterangan']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-status_pengembalian">
                                        <th class="title"> Status Pengembalian: </th>
                                        <td class="value"><?php Html :: check_button($data['status_pengembalian'], "true") ?></td>
                                    </tr>
                                    <tr  class="td-id_sewa">
                                        <th class="title"> Id Sewa: </th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-info page-modal" href="<?php print_link("penyewaan/view/" . urlencode($data['id_sewa'])) ?>">
                                                <i class="fa fa-barcode "></i> <?php echo $data['penyewaan_id_sewa'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="td-id_sepeda">
                                        <th class="title"> Id Sepeda: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php 
                                                $dependent_field = (!empty($data['id_sewa']) ? urlencode($data['id_sewa']) : null);
                                                print_link('api/json/pemgembalian_id_sepeda_option_list/'.$dependent_field); 
                                                ?>' 
                                                data-value="<?php echo $data['id_sepeda']; ?>" 
                                                data-pk="<?php echo $data['id_pengembalian'] ?>" 
                                                data-url="<?php print_link("pemgembalian/editfield/" . urlencode($data['id_pengembalian'])); ?>" 
                                                data-name="id_sepeda" 
                                                data-title="Select a value ..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['id_sepeda']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-nim">
                                        <th class="title"> Nim: </th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-info page-modal" href="<?php print_link("users/view/" . urlencode($data['nim'])) ?>">
                                                <i class="fa fa-user "></i> <?php echo $data['users_nama_lengap'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="td-penyewaan_id_sewa">
                                        <th class="title"> Penyewaan Id Sewa: </th>
                                        <td class="value"> <?php echo $data['penyewaan_id_sewa']; ?></td>
                                    </tr>
                                    <tr  class="td-penyewaan_id_sepeda">
                                        <th class="title"> Penyewaan Id Sepeda: </th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("data_sepeda/view/" . urlencode($data['id_sepeda'])) ?>">
                                                <i class="fa fa-eye"></i> <?php echo $data['data_sepeda_merk_sepeda'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="td-penyewaan_ketersediaan">
                                        <th class="title"> Penyewaan Ketersediaan: </th>
                                        <td class="value"> <?php echo $data['penyewaan_ketersediaan']; ?></td>
                                    </tr>
                                    <tr  class="td-penyewaan_tgl_mulai">
                                        <th class="title"> Penyewaan Tgl Mulai: </th>
                                        <td class="value"> <?php echo $data['penyewaan_tgl_mulai']; ?></td>
                                    </tr>
                                    <tr  class="td-penyewaan_tgl_akhir">
                                        <th class="title"> Penyewaan Tgl Akhir: </th>
                                        <td class="value"> <?php echo $data['penyewaan_tgl_akhir']; ?></td>
                                    </tr>
                                    <tr  class="td-penyewaan_status">
                                        <th class="title"> Penyewaan Status: </th>
                                        <td class="value"> <?php echo $data['penyewaan_status']; ?></td>
                                    </tr>
                                    <tr  class="td-penyewaan_terlambat">
                                        <th class="title"> Penyewaan Terlambat: </th>
                                        <td class="value"> <?php echo $data['penyewaan_terlambat']; ?></td>
                                    </tr>
                                    <tr  class="td-penyewaan_pinalti">
                                        <th class="title"> Penyewaan Pinalti: </th>
                                        <td class="value"> <?php echo $data['penyewaan_pinalti']; ?></td>
                                    </tr>
                                    <tr  class="td-penyewaan_tgl_booking">
                                        <th class="title"> Penyewaan Tgl Booking: </th>
                                        <td class="value"> <?php echo $data['penyewaan_tgl_booking']; ?></td>
                                    </tr>
                                    <tr  class="td-penyewaan_nim">
                                        <th class="title"> Penyewaan Nim: </th>
                                        <td class="value"> <?php echo $data['penyewaan_nim']; ?></td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
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
                                                <?php if($can_edit){ ?>
                                                <a class="btn btn-sm btn-info"  href="<?php print_link("pemgembalian/edit/$rec_id"); ?>">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <?php } ?>
                                                <?php if($can_delete){ ?>
                                                <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("pemgembalian/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Are you sure you want to delete this record?" data-display-style="modal">
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
