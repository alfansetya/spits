<?php
$comp_model = new SharedController;
$page_element_id = "edit-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="edit"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">Edit  Penyewaan</h4>
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
                        <form novalidate  id="" role="form" enctype="multipart/form-data"  class="form page-form form-horizontal needs-validation" action="<?php print_link("penyewaan/edit/$page_id/?csrf_token=$csrf_token"); ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="id_sewa">Kode Booking <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-id_sewa"  value="<?php  echo $data['id_sewa']; ?>" type="number" placeholder="Enter Kode Booking" step="1"  readonly required="" name="id_sewa"  data-url="api/json/penyewaan_id_sewa_value_exist/" data-loading-msg="Checking availability ..." data-available-msg="Available" data-unavailable-msg="Not available" class="form-control  ctrl-check-duplicate" />
                                                    <div class="check-status"></div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="nim">Nama Lengkap <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <select required=""  id="ctrl-nim" name="nim"  placeholder="Select a value ..."    class="selectize" >
                                                        <option value="">Select a value ...</option>
                                                        <?php
                                                        $rec = $data['nim'];
                                                        $nim_options = $comp_model -> penyewaan_nim_option_list();
                                                        if(!empty($nim_options)){
                                                        foreach($nim_options as $option){
                                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                                        $selected = ( $value == $rec ? 'selected' : null );
                                                        ?>
                                                        <option 
                                                            <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $label; ?>
                                                        </option>
                                                        <?php
                                                        }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="id_sepeda">Merk Sepeda <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <?php 
                                                    $id_sepeda_options = $comp_model -> penyewaan_id_sepeda_option_list();
                                                    $rec = $data['id_sepeda'];
                                                    if(!empty($id_sepeda_options)){
                                                    foreach($id_sepeda_options as $option){
                                                    $value = (!empty($option['value']) ? $option['value'] : null);
                                                    $label = (!empty($option['label']) ? $option['label'] : $value);
                                                    $checked = ( $value == $rec ? 'checked' : null );
                                                    ?>
                                                    <label class="btn btn-sm btn-primary option-btn">
                                                        <input id="ctrl-id_sepeda" class="" <?php echo $checked ?> value="<?php echo $value; ?>" type="radio"  data-load-select-options="ketersediaan" name="id_sepeda"   required="" />
                                                            <span class=""><?php echo $label; ?></span>
                                                        </label>
                                                        <?php
                                                        }
                                                        }
                                                        ?> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="ketersediaan">Ketersediaan <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <select required=""  id="ctrl-ketersediaan" data-load-path="<?php print_link('api/json/penyewaan_ketersediaan_option_list') ?>" name="ketersediaan"  placeholder="Select a value ..."    class="custom-select" >
                                                            <?php
                                                            $rec = $data['ketersediaan'];
                                                            $ketersediaan_options = $comp_model -> penyewaan_ketersediaan_option_list($data['id_sepeda']);
                                                            if(!empty($ketersediaan_options)){
                                                            foreach($ketersediaan_options as $option){
                                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                                            $selected = ( $value == $rec ? 'selected' : null );
                                                            ?>
                                                            <option 
                                                                <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $label; ?>
                                                            </option>
                                                            <?php
                                                            }
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-mixcloud "></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="tgl_mulai">Tanggal Peminjaman <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input id="ctrl-tgl_mulai" class="form-control datepicker  datepicker"  required="" value="<?php  echo $data['tgl_mulai']; ?>" type="datetime" name="tgl_mulai" placeholder="Enter Tanggal Peminjaman" data-enable-time="false" data-min-date="" data-max-date="" data-date-format="Y-m-d" data-alt-format="F j, Y" data-inline="false" data-no-calendar="false" data-mode="single" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="tgl_akhir">Tanggal Pengembalian <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input id="ctrl-tgl_akhir" class="form-control datepicker  datepicker"  required="" value="<?php  echo $data['tgl_akhir']; ?>" type="datetime" name="tgl_akhir" placeholder="Enter Tanggal Pengembalian" data-enable-time="false" data-min-date="" data-max-date="" data-date-format="Y-m-d" data-alt-format="F j, Y" data-inline="false" data-no-calendar="false" data-mode="single" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="status">Progres Peminjaman <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="input-group">
                                                                <select required=""  id="ctrl-status" name="status"  placeholder="Select a value ..."    class="custom-select" >
                                                                    <option value="">Select a value ...</option>
                                                                    <?php
                                                                    $status_options = Menu :: $status;
                                                                    $field_value = $data['status'];
                                                                    if(!empty($status_options)){
                                                                    foreach($status_options as $option){
                                                                    $value = $option['value'];
                                                                    $label = $option['label'];
                                                                    $selected = ( $value == $field_value ? 'selected' : null );
                                                                    ?>
                                                                    <option <?php echo $selected ?> value="<?php echo $value ?>">
                                                                        <?php echo $label ?>
                                                                    </option>                                   
                                                                    <?php
                                                                    }
                                                                    }
                                                                    ?>
                                                                </select>
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
                                                            <label class="control-label" for="pinalti">Pinalti / Denda Berlaku </label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="input-group">
                                                                <select  id="ctrl-pinalti" name="pinalti"  placeholder="Lewati jika tanpa denda"    class="custom-select" >
                                                                    <option value="">Lewati jika tanpa denda</option>
                                                                    <?php
                                                                    $rec = $data['pinalti'];
                                                                    $pinalti_options = $comp_model -> penyewaan_pinalti_option_list();
                                                                    if(!empty($pinalti_options)){
                                                                    foreach($pinalti_options as $option){
                                                                    $value = (!empty($option['value']) ? $option['value'] : null);
                                                                    $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                    $selected = ( $value == $rec ? 'selected' : null );
                                                                    ?>
                                                                    <option 
                                                                        <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $label; ?>
                                                                    </option>
                                                                    <?php
                                                                    }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"><i class="fa fa-warning "></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-ajax-status"></div>
                                            <div class="form-group text-center">
                                                <button class="btn btn-primary" type="submit">
                                                    Update
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
