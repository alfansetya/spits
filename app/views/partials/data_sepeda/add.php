<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">Data Sepeda</h4>
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
                <div class="col-md-7 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="bg-light p-3 animated fadeIn page-content">
                        <form id="data_sepeda-add-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("data_sepeda/add?csrf_token=$csrf_token") ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="merk_sepeda">Merk Sepeda <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input id="ctrl-merk_sepeda"  value="<?php  echo $this->set_field_value('merk_sepeda',""); ?>" type="text" placeholder="Enter Merk Sepeda"  required="" name="merk_sepeda"  class="form-control " />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-bicycle "></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="jumlah_sepeda">Jumlah Sepeda <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input id="ctrl-jumlah_sepeda"  value="<?php  echo $this->set_field_value('jumlah_sepeda',""); ?>" type="number" placeholder="Enter Jumlah Sepeda" step="1"  required="" name="jumlah_sepeda"  class="form-control " />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-i-cursor "></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="kondisi_sepeda">Kondisi Sepeda <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <textarea placeholder="Enter Kondisi Sepeda" id="ctrl-kondisi_sepeda"  required="" rows="5" name="kondisi_sepeda" class=" form-control"><?php  echo $this->set_field_value('kondisi_sepeda',""); ?></textarea>
                                                        <!--<div class="invalid-feedback animated bounceIn text-center">Please enter text</div>-->
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-info-circle "></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="gambar_sepeda">Gambar Sepeda <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="">
                                                        <div class="dropzone required" input="#ctrl-gambar_sepeda" fieldname="gambar_sepeda"    data-multiple="false" dropmsg="Choose files or drag and drop files to upload"    btntext="Browse" filesize="3" maximum="1">
                                                            <input name="gambar_sepeda" id="ctrl-gambar_sepeda" required="" class="dropzone-input form-control" value="<?php  echo $this->set_field_value('gambar_sepeda',""); ?>" type="text"  />
                                                                <!--<div class="invalid-feedback animated bounceIn text-center">Please a choose file</div>-->
                                                                <div class="dz-file-limit animated bounceIn text-center text-danger"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-submit-btn-holder text-center mt-3">
                                            <div class="form-ajax-status"></div>
                                            <button class="btn btn-primary" type="submit">
                                                Submit
                                                <i class="fa fa-send"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
