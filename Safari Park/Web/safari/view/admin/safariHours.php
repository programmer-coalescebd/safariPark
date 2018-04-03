<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/13/17
 * Time: 3:35 PM
 */

//$shifts = $DB->sql("SELECT ticket_id, unique_id, ticket_name, ticket_position, ticket_price, status FROM `ticket` WHERE ticket_category = 'entryTicket'", 0, 0, 0);
$shifts = $DB->sql("SELECT * FROM shift WHERE ticket_category = 'safariTicket'", 0, 0, 0);
?>
<div class="row">
    <div class="col-sm-12 parentTab">
        <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_safari_hours_list[$lang] . '</span>' : $lang_safari_hours_list[$lang]); ?></h4>

        <button class="btn btn-icon btn-success addNew" onclick="location.href='/admin/addSafariHour';">
            <i class="fa fa-file-text-o"></i>
            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_add_new[$lang] . '</span>' : $lang_add_new[$lang]); ?>
        </button>

        <div class="table-responsive m-b-20">

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_schedule_name[$lang] . '</span>' : $lang_schedule_name[$lang]); ?></th>
                    <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_schedule_service[$lang] . '</span>' : $lang_schedule_service[$lang]); ?></th>
                    <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_schedule_start[$lang] . '</span>' : $lang_schedule_start[$lang]); ?></th>
                    <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_schedule_end[$lang] . '</span>' : $lang_schedule_end[$lang]); ?></th>
                    <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_allo_tickets[$lang] . '</span>' : $lang_allo_tickets[$lang]); ?></th>
                    <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_actions[$lang] . '</span>' : $lang_actions[$lang]); ?></th>
                </tr>
                </thead>


                <tbody>

                <?php
                if (count($shifts) > 0) {

                    foreach ($shifts as $shift) {
                        echo '<tr>
                                        <td class="bangla">' . $shift["shift_name"] . '</td>
                                        <td>' . (1 === preg_match('~[0-9]~', $shift["shift_date"]) ? $shift["shift_date"] : '<span class="bangla">'.$lang_days[$lang][$shift["shift_date"]].'</span>') . '</td>
                                        <td>' . $shift["shift_time"] . '</td>
                                        <td>' . (date("h:i A", strtotime($shift["shift_time"] . " +" . $shift["duration"] . " minutes"))) . '</td>
                                        <td>' . $shift["tickets"] . '</td>
                                        <td>
                                            <button class="btn btn-icon btn-danger" style="padding:3px 6px" onclick="location.href=\'/admin/deleteSafariHours/' . $shift["unique_id"] . '\';"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>';
                    }

                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>