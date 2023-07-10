<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("users/add");
$can_edit = ACL::is_allowed("users/edit");
$can_view = ACL::is_allowed("users/view");
$can_delete = ACL::is_allowed("users/delete");
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
                    <h4 class="record-title">Details Users</h4>
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
                        $rec_id = (!empty($data['nim']) ? urlencode($data['nim']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-nama_lengap">
                                        <th class="title"> Nama Lengkap: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nama_lengap']; ?>" 
                                                data-pk="<?php echo $data['nim'] ?>" 
                                                data-url="<?php print_link("users/editfield/" . urlencode($data['id_users'])); ?>" 
                                                data-name="nama_lengap" 
                                                data-title="Enter Nama Lengkap" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nama_lengap']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-nim">
                                        <th class="title"> No. Induk Mahasiswa: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nim']; ?>" 
                                                data-pk="<?php echo $data['nim'] ?>" 
                                                data-url="<?php print_link("users/editfield/" . urlencode($data['id_users'])); ?>" 
                                                data-name="nim" 
                                                data-title="Enter NIM" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="number" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nim']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-prodi">
                                        <th class="title"> Prodi: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/users_prodi_option_list'); ?>' 
                                                data-value="<?php echo $data['prodi']; ?>" 
                                                data-pk="<?php echo $data['nim'] ?>" 
                                                data-url="<?php print_link("users/editfield/" . urlencode($data['id_users'])); ?>" 
                                                data-name="prodi" 
                                                data-title="Select a value ..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['prodi']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-kontak">
                                        <th class="title"> Kontak: </th>
                                        <td class="value"><?php
                                            function formatWhatsAppLink($phoneNumber) {
                                            // Menghapus semua karakter selain angka
                                            $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
                                            // Memeriksa apakah nomor telepon memiliki kode negara
                                            if (strpos($phoneNumber, '0') === 0) {
                                            // Mengganti angka awal dengan kode negara
                                            $phoneNumber = '62' . substr($phoneNumber, 1);
                                            }
                                            // Membuat URL tautan WhatsApp
                                            $whatsappLink = 'https://api.whatsapp.com/send?phone=' . $phoneNumber;
                                            return $whatsappLink;
                                            }
                                            // Contoh penggunaan
                                            $nomorTelepon = $data['kontak'];
                                            $linkWhatsApp = formatWhatsAppLink($nomorTelepon);
                                            echo '<a href="' . $linkWhatsApp . '">' . $nomorTelepon . '</a>';
                                        ?> <i class=" fa fa-whatsapp " style='font-size:24px;color:green'></i></td>
                                    </tr>
                                    <tr  class="td-email">
                                        <th class="title"> Email: </th>
                                        <td class="value"> <?php echo $data['email']; ?></td>
                                    </tr>
                                    <tr  class="td-users_name">
                                        <th class="title"> Usersname: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['users_name']; ?>" 
                                                data-pk="<?php echo $data['nim'] ?>" 
                                                data-url="<?php print_link("users/editfield/" . urlencode($data['id_users'])); ?>" 
                                                data-name="users_name" 
                                                data-title="Include NIM" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['users_name']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-ktm">
                                        <th class="title"> Kartu Tanda Mahasiswa: </th>
                                        <td class="value"><?php Html :: page_img($data['ktm'],400,400,1); ?></td>
                                    </tr>
                                    <tr  class="td-level">
                                        <th class="title"> Level Pengguna: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php echo json_encode_quote(Menu :: $level); ?>' 
                                                data-value="<?php echo $data['level']; ?>" 
                                                data-pk="<?php echo $data['nim'] ?>" 
                                                data-url="<?php print_link("users/editfield/" . urlencode($data['id_users'])); ?>" 
                                                data-name="level" 
                                                data-title="Select a value ..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['level']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-account_status">
                                        <th class="title"> Account Status: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php echo json_encode_quote(Menu :: $account_status); ?>' 
                                                data-value="<?php echo $data['account_status']; ?>" 
                                                data-pk="<?php echo $data['nim'] ?>" 
                                                data-url="<?php print_link("users/editfield/" . urlencode($data['id_users'])); ?>" 
                                                data-name="account_status" 
                                                data-title="Select a value ..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['account_status']; ?> 
                                            </span>
                                        </td>
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
                                                <a class="btn btn-sm btn-info"  href="<?php print_link("users/edit/$rec_id"); ?>">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <?php } ?>
                                                <?php if($can_delete){ ?>
                                                <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("users/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Are you sure you want to delete this record?" data-display-style="modal">
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
