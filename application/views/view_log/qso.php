<?php if ($query->num_rows() > 0) {  foreach ($query->result() as $row) { ?>
<div class="container-fluid">

    <ul style="margin-bottom: 10px;" class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="table-tab" data-toggle="tab" href="#qsodetails" role="tab" aria-controls="table" aria-selected="true"><?php echo $this->lang->line('qso_details'); ?></a>
        </li>
        <li class="nav-item">
            <a id="station-tab" class="nav-link" data-toggle="tab" href="#stationdetails" role="tab" aria-controls="table" aria-selected="true"><?php echo $this->lang->line('cloudlog_station_profile'); ?></a>
        </li>
        <?php
        if ($row->COL_NOTES != null) {?>
        <li class="nav-item">
            <a id="notes-tab" class="nav-link" data-toggle="tab" href="#notesdetails" role="tab" aria-controls="table" aria-selected="true"><?php echo "Notes"; ?></a>
        </li>
        <?php }?>
        <?php
        if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) {

            echo '<li ';
            if (count($qslimages) == 0) {
                echo 'hidden ';
            }
                echo 'class="qslcardtab nav-item">
                <a class="nav-link" id="qsltab" data-toggle="tab" href="#qslcard" role="tab" aria-controls="home" aria-selected="false">'. $this->lang->line('general_word_qslcard') .'</a>
                </li>';

            echo '<li class="nav-item">
            <a class="nav-link" id="qslmanagementtab" data-toggle="tab" href="#qslupload" role="tab" aria-controls="home" aria-selected="false">'. $this->lang->line('general_word_qslcard_management') .'</a>
            </li>';
        }

        ?>

    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane active" id="qsodetails" role="tabpanel" aria-labelledby="home-tab">

        <div class="row">
            <div class="col-md">

                <table width="100%">
                    <tr>
                        <?php

                        // Get Date format
                        if($this->session->userdata('user_date_format')) {
                            // If Logged in and session exists
                            $custom_date_format = $this->session->userdata('user_date_format');
                        } else {
                            // Get Default date format from /config/cloudlog.php
                            $custom_date_format = $this->config->item('qso_date_format');
                        }

                        ?>

                        <td><?php echo $this->lang->line('general_word_datetime'); ?></td>
                        <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
                        <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); $timestamp = strtotime($row->COL_TIME_ON); echo " at ".date('H:i', $timestamp); ?></td>
                        <?php } else { ?>
                        <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
                        <?php } ?>
                    </tr>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_callsign'); ?></td>
                        <td><b><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></b> <a target="_blank" href="https://www.qrz.com/db/<?php echo strtoupper($row->COL_CALL); ?>"><img width="16" height="16" src="<?php echo base_url(); ?>images/icons/qrz.png" alt="Lookup <?php echo strtoupper($row->COL_CALL); ?> on QRZ.com"></a> <a target="_blank" href="https://www.hamqth.com/<?php echo strtoupper($row->COL_CALL); ?>"><img width="16" height="16" src="<?php echo base_url(); ?>images/icons/hamqth.png" alt="Lookup <?php echo strtoupper($row->COL_CALL); ?> on HamQTH"></a></td>
                    </tr>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_band'); ?></td>
                        <td><?php echo $row->COL_BAND; ?></td>
                    </tr>

                    <?php if($this->config->item('display_freq') == true) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_frequency'); ?></td>
                        <td><?php echo $this->frequency->hz_to_mhz($row->COL_FREQ); ?></td>
                    </tr>
                    <?php if($row->COL_FREQ_RX != 0) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_frequency_rx'); ?></td>
                        <td><?php echo $this->frequency->hz_to_mhz($row->COL_FREQ_RX); ?></td>
                    </tr>
                    <?php }} ?>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_mode'); ?></td>
                        <td><?php echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; ?></td>
                    </tr>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_rsts'); ?></td>
                        <td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX) { ?>(<?php printf("%03d", $row->COL_STX);?>)<?php } ?> <?php if ($row->COL_STX_STRING) { ?>(<?php echo $row->COL_STX_STRING;?>)<?php } ?></td>
                    </tr>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_rstr'); ?></td>
                        <td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX) { ?>(<?php printf("%03d", $row->COL_SRX);?>)<?php } ?> <?php if ($row->COL_SRX_STRING) { ?>(<?php echo $row->COL_SRX_STRING;?>)<?php } ?></td>
                    </tr>

                    <?php if($row->COL_GRIDSQUARE != null) { ?>
                    <tr>
                        <td>Gridsquare:</td>
                        <td><?php echo $row->COL_GRIDSQUARE; ?> <a href="javascript:spawnQrbCalculator('<?php echo $row->station_gridsquare . '\',\'' . $row->COL_GRIDSQUARE; ?>')"><i class="fas fa-globe"></i></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_GRIDSQUARE != null && strlen($row->COL_GRIDSQUARE) >= 4) { ?>
                    <!-- Total Distance Between the Station Profile Gridsquare and Logged Square -->
                    <tr>
                        <td><?php echo $this->lang->line('general_total_distance'); //Total distance ?></td>
                        <td>
                            <?php
                                // Load the QRA Library
                                $CI =& get_instance();
                                $CI->load->library('qra');

                                // Cacluate Distance
                                $distance = $CI->qra->distance($row->station_gridsquare, $row->COL_GRIDSQUARE, $measurement_base);

                                switch ($measurement_base) {
                                    case 'M':
                                        $distance .= "mi";
                                        break;
                                    case 'K':
                                        $distance .= "km";
                                        break;
                                    case 'N':
                                        $distance .= "nmi";
                                        break;
                                }
                                echo $distance;
                            ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_VUCC_GRIDS != null) { ?>
                    <tr>
                        <td>Gridsquare (Multi):</td>
                        <td><?php echo $row->COL_VUCC_GRIDS; ?> <a href="javascript:spawnQrbCalculator('<?php echo $row->station_gridsquare . '\',\'' . $row->COL_VUCC_GRIDS; ?>')"><i class="fas fa-globe"></i></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_STATE != null) { ?>
                    <tr>
                        <td>USA State:</td>
                        <td><?php echo $row->COL_STATE; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_CNTY != null && $row->COL_CNTY != ",") { ?>
                        <tr>
                            <td>USA County:</td>
                            <td><?php echo $row->COL_CNTY; ?></td>
                        </tr>
                    <?php } ?>

                    <?php if($row->COL_NAME != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('general_word_name'); ?></td>
                        <td><?php echo $row->COL_NAME; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
                    <?php if($row->COL_COMMENT != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('general_word_comment'); ?></td>
                        <td><?php echo $row->COL_COMMENT; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>

                    <?php if($row->COL_SAT_NAME != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_satellite_name'); ?></td>
                        <td><a href="https://db.satnogs.org/search/?q=<?php echo $row->COL_SAT_NAME; ?>" target="_blank"><?php echo $row->COL_SAT_NAME; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SAT_MODE != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_satellite_mode'); ?></td>
                        <td><?php echo $row->COL_SAT_MODE; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($row->COL_COUNTRY != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('general_word_country'); ?></td>
                        <td><?php echo ucwords(strtolower(($row->COL_COUNTRY)), "- (/"); ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_CONT != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_continent'); ?></td>
                        <td>
                        <?php
                           switch($row->COL_CONT) {
                             case "AF":
                               echo $this->lang->line('africa');
                               break;
                             case "AN":
                               echo $this->lang->line('antarctica');
                               break;
                             case "AS":
                               echo $this->lang->line('asia');
                               break;
                             case "EU":
                               echo $this->lang->line('europe');
                               break;
                             case "NA":
                               echo $this->lang->line('northamerica');
                               break;
                             case "OC":
                               echo $this->lang->line('oceania');
                               break;
                             case "SA":
                               echo $this->lang->line('southamerica');
                               break;
                           }
                        ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_CONTEST_ID != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('contesting_contest_name'); ?></td>
                        <td><?php echo $row->COL_CONTEST_ID; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_IOTA != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_iota_reference'); ?></td>
                        <td><a href="https://www.iota-world.org/iotamaps/?grpref=<?php echo $row->COL_IOTA; ?>" target="_blank"><?php echo $row->COL_IOTA; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SOTA_REF != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_sota_reference'); ?></td>
                        <td><a href="https://summits.sota.org.uk/summit/<?php echo $row->COL_SOTA_REF; ?>" target="_blank"><?php echo $row->COL_SOTA_REF; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_WWFF_REF != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_wwff_reference'); ?></td>
                        <td><a href="https://www.cqgma.org/zinfo.php?ref=<?php echo $row->COL_WWFF_REF; ?>" target="_blank"><?php echo $row->COL_WWFF_REF; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_POTA_REF != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_pota_reference'); ?></td>
                        <td><a href="https://pota.app/#/park/<?php echo $row->COL_POTA_REF; ?>" target="_blank"><?php echo $row->COL_POTA_REF; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SIG != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_sig'); ?></td>
                        <td><?php echo $row->COL_SIG; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SIG_INFO != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_sig_info'); ?></td>
                        <?php
                        switch ($row->COL_SIG) {
                        case "GMA":
                           echo "<td><a href=\"https://www.cqgma.org/zinfo.php?ref=".$row->COL_SIG_INFO."\" target=\"_blank\">".$row->COL_SIG_INFO."</a></td>";
                           break;
                        case "MQC":
                           echo "<td><a href=\"https://www.mountainqrp.it/awards/referenza.php?ref=".$row->COL_SIG_INFO."\" target=\"_blank\">".$row->COL_SIG_INFO."</a></td>";
                           break;
                        default:
                           echo "<td>".$row->COL_SIG_INFO."</td>";
                           break;
                        }
                        ?>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_DARC_DOK != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_dok'); ?></td>
                        <?php if (preg_match('/^[A-Y]\d{2}$/', $row->COL_DARC_DOK)) { ?>
                        <td><a href="https://www.darc.de/<?php echo $row->COL_DARC_DOK; ?>" target="_blank"><?php echo $row->COL_DARC_DOK; ?></a></td>
                        <?php } else if (preg_match('/^Z\d{2}$/', $row->COL_DARC_DOK)) { ?>
                        <td><a href="https://<?php echo $row->COL_DARC_DOK; ?>.vfdb.org" target="_blank"><?php echo $row->COL_DARC_DOK; ?></a></td>
                        <?php } else { ?>
                        <td><?php echo $row->COL_DARC_DOK; ?></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>

                </table>
                <?php if($row->COL_QSL_SENT == "Y" || $row->COL_QSL_RCVD == "Y") { ?>
                    <h3><?php echo $this->lang->line('qslcard_info'); ?></h3>

                    <?php if($row->COL_QSL_SENT == "Y") {?>
                        <?php if ($row->COL_QSL_SENT_VIA == "B") { ?>
                            <p><?php echo $this->lang->line('qslcard_sent_bureau'); ?>
                        <?php } else if($row->COL_QSL_SENT_VIA == "D") { ?>
                            <p><?php echo $this->lang->line('qslcard_sent_direct'); ?>
                        <?php } else if($row->COL_QSL_SENT_VIA == "E") { ?>
                            <p><?php echo $this->lang->line('qslcard_sent_electronic'); ?>
                        <?php } else if($row->COL_QSL_SENT_VIA == "M") { ?>
                            <p><?php echo $this->lang->line('qslcard_sent_manager'); ?>
                        <?php } else { ?>
                            <p><?php echo $this->lang->line('qslcard_sent'); ?>
                        <?php } ?>
                        <?php if ($row->COL_QSLSDATE != null) { ?>
                            <?php $timestamp = strtotime($row->COL_QSLSDATE); echo " (".date($custom_date_format, $timestamp).")"; ?></p>
                        <?php } ?>
                    <?php } ?>

                    <?php if($row->COL_QSL_RCVD == "Y") { ?>
                        <?php if ($row->COL_QSL_RCVD_VIA == "B") { ?>
                            <p><?php echo $this->lang->line('qslcard_rcvd_bureau'); ?>
                        <?php } else if($row->COL_QSL_RCVD_VIA == "D") { ?>
                            <p><?php echo $this->lang->line('qslcard_rcvd_direct'); ?>
                        <?php } else if($row->COL_QSL_RCVD_VIA == "E") { ?>
                            <p><?php echo $this->lang->line('qslcard_rcvd_electronic'); ?>
                        <?php } else if($row->COL_QSL_RCVD_VIA == "M") { ?>
                            <p><?php echo $this->lang->line('qslcard_rcvd_manager'); ?>
                        <?php } else { ?>
                            <p><?php echo $this->lang->line('qslcard_rcvd'); ?>
                        <?php } ?>
                        <?php if ($row->COL_QSLRDATE != null) { ?>
                            <?php $timestamp = strtotime($row->COL_QSLRDATE); echo " (".date($custom_date_format, $timestamp).")"; ?></p>
                        <?php } ?>
                    <?php } ?>

                <?php } ?>

                    <?php if($row->COL_LOTW_QSL_RCVD == "Y") { ?>
                    <h3><?php echo $this->lang->line('lotw_short'); ?></h3>
                    <p><?php echo $this->lang->line('gen_this_qso_was_confirmed_on'); ?> <?php $timestamp = strtotime($row->COL_LOTW_QSLRDATE); echo date($custom_date_format, $timestamp); ?>.</p>
                    <?php } ?>

                    <?php if($row->COL_EQSL_QSL_RCVD == "Y") { ?>
                    <h3>eQSL</h3>
                        <p><?php echo $this->lang->line('gen_this_qso_was_confirmed_on'); ?> <?php $timestamp = strtotime($row->COL_EQSL_QSLRDATE); echo date($custom_date_format, $timestamp); ?>.</p>
                    <?php } ?>
            </div>

                <div class="col-md">

                    <div id="mapqso" style="width: 100%; height: 250px"></div>

                    <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
                        <br>
                            <div style="display: inline-block;"><p class="editButton"><a class="btn btn-primary" href="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" href="javascript:;"><i class="fas fa-edit"></i><?php echo $this->lang->line('qso_btn_edit_qso'); ?></a></p></div>
                            <div style="display: inline-block;"><form method="POST" action="<?php echo site_url('search'); ?>"><input type="hidden" value="<?php echo strtoupper($row->COL_CALL); ?>" name="callsign"><button class="btn btn-primary" type="submit"><i class="fas fa-eye"></i> More QSOs</button></form></div>
                    <?php } ?>

                    <?php

                        if($row->COL_SAT_NAME != null) {
                            $twitter_band_sat = $row->COL_SAT_NAME;
                            $hashtags = "#hamr #cloudlog #amsat";
                        } else {
                            $twitter_band_sat = $row->COL_BAND;
                            $hashtags = "#hamr #cloudlog";
                        }
                        if (!isset($distance)) {
                            $twitter_string = urlencode("Just worked ".$row->COL_CALL." in ".ucwords(strtolower(($row->COL_COUNTRY)))." on ".$twitter_band_sat." using ".($row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE)." ".$hashtags);
                        } else {
                            $twitter_string = urlencode("Just worked ".$row->COL_CALL." in ".ucwords(strtolower(($row->COL_COUNTRY)))." (Gridsquare: ".$row->COL_GRIDSQUARE." / distance: ".$distance.") on ".$twitter_band_sat." using ".($row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE)." ".$hashtags);
                        }
                    ?>

                    <div style="display: inline-block;"><a class="btn btn-primary twitter-share-button" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $twitter_string; ?>"><i class="fab fa-twitter"></i> Tweet</a></div>

                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="stationdetails" role="tabpanel" aria-labelledby="table-tab">
            <h3>Station Details</h3>

            <table width="100%">
                    <tr>
                        <td>Station Callsign</td>
                        <td><?php echo $row->station_callsign; ?></td>
                    </tr>
                    <tr>
                        <td>Station Name</td>
                        <td><?php echo $row->station_profile_name; ?></td>
                    </tr>
                    <tr>
                        <td>Station Gridsquare</td>
                        <td><?php echo $row->station_gridsquare; ?></td>
                    </tr>

                    <?php if($row->station_city) { ?>
                    <tr>
                        <td>Station City</td>
                        <td><?php echo $row->station_city; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->station_country) { ?>
                    <tr>
                        <td>Station Country</td>
                        <td><?php echo ucwords(strtolower(($row->station_country)), "- (/"); ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_OPERATOR) { ?>
                    <tr>
                        <td>Station Operator</td>
                        <td><?php echo $row->COL_OPERATOR; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_TX_PWR) { ?>
                    <tr>
                        <td>Station Transmit Power</td>
                        <td><?php echo $row->COL_TX_PWR; ?>w</td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_MY_WWFF_REF) { ?>
                    <tr>
                        <td>Station WWFF Reference</td>
                        <td><?php echo $row->COL_MY_WWFF_REF; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_MY_POTA_REF) { ?>
                    <tr>
                        <td>Station POTA Reference</td>
                        <td><?php echo $row->COL_MY_POTA_REF; ?></td>
                    </tr>
                    <?php } ?>
            </table>
        </div>

        <div class="tab-pane fade" id="notesdetails" role="tabpanel" aria-labelledby="table-tab">
            <h3>Notes</h3>
            <?php echo nl2br($row->COL_NOTES); ?>
        </div>

        <?php
        if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) {
        ?>
        <div class="tab-pane fade" id="qslupload" role="tabpanel" aria-labelledby="table-tab">
            <?php
            if (count($qslimages) > 0) {
            echo '<table style="width:100%" class="qsltable table table-sm table-bordered table-hover table-striped table-condensed">
                <thead>
                <tr>
                    <th style=\'text-align: center\'>QSL image file</th>
                    <th style=\'text-align: center\'></th>
                    <th style=\'text-align: center\'></th>
                </tr>
                </thead><tbody>';

                foreach ($qslimages as $qsl) {
                echo '<tr>';
                    echo '<td style=\'text-align: center\'>' . $qsl->filename . '</td>';
                    echo '<td id="'.$qsl->id.'" style=\'text-align: center\'><button onclick="deleteQsl('.$qsl->id.')" class="btn btn-sm btn-danger">Delete</button></td>';
                    echo '<td style=\'text-align: center\'><button onclick="viewQsl(\''.$qsl->filename.'\')" class="btn btn-sm btn-success">View</button></td>';
                    echo '</tr>';
                }

                echo '</tbody></table>';
            }
            ?>

            <p><div class="alert alert-warning" role="alert"><span class="badge badge-warning">Warning</span> Maximum file upload size is <?php echo $max_upload; ?>B.</div></p>

            <form class="form" id="fileinfo" name="fileinfo" enctype="multipart/form-data">
                <fieldset>

                    <div class="form-group">
                        <label for="qslcardfront"><?php echo $this->lang->line('qslcard_upload_front'); ?></label>
                        <input class="form-control-file" type="file" id="qslcardfront" name="qslcardfront" accept="image/*" >
                    </div>

                    <div class="form-group">
                        <label for="qslcardback"><?php echo $this->lang->line('qslcard_upload_back'); ?></label>
                        <input class="form-control-file" type="file" id="qslcardback" name="qslcardback" accept="image/*">
                    </div>

                    <input type="hidden" class="form-control" id="qsoinputid" name="qsoid" value="<?php echo $row->COL_PRIMARY_KEY; ?>">

                    <button type="button" onclick="uploadQsl();" id="button1id"  name="button1id" class="btn btn-primary"><?php echo $this->lang->line('qslcard_upload_button'); ?></button>

                </fieldset>
            </form>
        </div>

        <div class="tab-pane fade" id="qslcard" role="tabpanel" aria-labelledby="table-tab">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php
                    $i = 0;
                    foreach ($qslimages as $image) {
                        echo '<li data-target="#carouselExampleIndicators" data-slide-to="' . $i . '"';
                        if ($i == 0) {
                            echo 'class="active"';
                        }
                        $i++;
                        echo '></li>';
                    }
                    ?>
                </ol>
                <div class="carousel-inner">

                    <?php
                    $i = 1;
                    foreach ($qslimages as $image) {
                        echo '<div class="carousel-item carouselimageid_' . $image->id;
                        if ($i == 1) {
                            echo ' active';
                        }
                        echo '">';
                        echo '<img class="d-block w-100" src="' . base_url() . '/assets/qslcard/' . $image->filename .'" alt="QSL picture #'. $i++.'">';
                        echo '</div>';
                    }
                    ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <?php
        }
        ?>
</div>
</div>

<?php
	if($row->COL_GRIDSQUARE != null && strlen($row->COL_GRIDSQUARE) >= 4) {
		$stn_loc = $this->qra->qra2latlong(trim($row->COL_GRIDSQUARE));	
        if($stn_loc[0] != 0) {
		    $lat = $stn_loc[0];
		    $lng = $stn_loc[1];
        }
    } elseif($row->COL_VUCC_GRIDS != null) {
        $grids = explode(",", $row->COL_VUCC_GRIDS);
        if (count($grids) == 2) {
            $grid1 = $this->qra->qra2latlong(trim($grids[0]));
            $grid2 = $this->qra->qra2latlong(trim($grids[1]));

            $coords[]=array('lat' => $grid1[0],'lng'=> $grid1[1]);
            $coords[]=array('lat' => $grid2[0],'lng'=> $grid2[1]);    

            $midpoint = $this->qra->get_midpoint($coords);
            $lat = $midpoint[0];
		    $lng = $midpoint[1];
        }
        if (count($grids) == 4) {
            $grid1 = $this->qra->qra2latlong(trim($grids[0]));
            $grid2 = $this->qra->qra2latlong(trim($grids[1]));
            $grid3 = $this->qra->qra2latlong(trim($grids[2]));
            $grid4 = $this->qra->qra2latlong(trim($grids[3]));

            $coords[]=array('lat' => $grid1[0],'lng'=> $grid1[1]);
            $coords[]=array('lat' => $grid2[0],'lng'=> $grid2[1]);    
            $coords[]=array('lat' => $grid3[0],'lng'=> $grid3[1]);    
            $coords[]=array('lat' => $grid4[0],'lng'=> $grid4[1]);    

            $midpoint = $this->qra->get_midpoint($coords);
            $lat = $midpoint[0];
		    $lng = $midpoint[1];
        }
	} else {
        if(isset($row->lat)) {
			$lat = $row->lat;
        } else {
            $lat = 0;
        }

        if(isset($row->long)) {
			$lng = $row->long;
        } else {
            $lng = 0;
        }
	}
?>

<script>
var lat = <?php echo $lat; ?>;
var long = <?php echo $lng; ?>;
var callsign = "<?php echo $row->COL_CALL; ?>";
</script>
    <div hidden id ='lat'><?php echo $lat; ?></div>
    <div hidden id ='long'><?php echo $lng; ?></div>
    <div hidden id ='callsign'><?php echo $row->COL_CALL; ?></div>
    <div hidden id ='qsoid'><?php echo $row->COL_PRIMARY_KEY; ?></div>

<?php } } ?>
