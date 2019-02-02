<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Weekly Planner</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>
	<div id="container">
	 
		<div id="body">
		<div class="card weekly">
    <div class="card-header">
        <h3 class="card-title text-center weekly-header">
            <?php
            if(isset($_GET['week']) && $_GET['week'] !== "") {

                $lastWeek = date('Y-m-d', strtotime($_GET['week'].' -7 days'));
                $nextWeek = date('Y-m-d', strtotime($_GET['week'].' +7 days'));

                echo anchor(uri_string().'?week='.$lastWeek, icon('chevron-left').'&nbsp;');
                echo format_date($_GET['week'], FALSE);
                echo ' - ';
                echo format_date(date('Y-m-d', strtotime($_GET['week'].' +6 days')), FALSE);
                echo anchor(uri_string().'?week='.$nextWeek, '&nbsp;'.icon('chevron-right'));
            } else {

                $lastWeek = date('Y-m-d', strtotime('monday last week'));
                $nextWeek = date('Y-m-d', strtotime('monday next week'));

                echo anchor(uri_string().'?week='.$lastWeek, icon('chevron-left').'&nbsp;');

                echo format_date(date('Y-m-d', strtotime('monday this week')), FALSE);
                echo ' - ';
                echo format_date(date('Y-m-d', strtotime('monday this week +6 days')), FALSE);
                echo anchor(uri_string().'?week='.$nextWeek, '&nbsp;'.icon('chevron-right'));
            }
            ?>
        </h3>

    </div>

    <?php
    $h_start = "08:00";
    $h_end = "17:00";
    $interval = "30";

    $starttimestamp = strtotime($h_start);
    $endtimestamp = strtotime($h_end);
    $hours = abs($endtimestamp - $starttimestamp) / 3600;
    ?>
    <div class="card-content">
        <div class="weekly-btns">
            <button
                    data-toggle="modal" 
					data-target="#activityModal"
                    class="btn btn-default btn-sm">
					<?php echo icon('plus').' New Activity'; ?>
				</button>
            <?php if(!isset($_GET['week']) || isset($_GET['week']) && $_GET['week'] == date('Y-m-d')): ?>
                <a href="<?php echo site_url('activities/copy'); ?>"
                   class="btn btn-primary btn-sm copy-plan"><i class="fa fa-copy"></i> Copy to next week</a>

                <a href="<?php echo site_url('activities/clear'); ?>"
                   class="btn btn-warning btn-sm clear-plan"><i class="fa fa-trash"></i> Clear activity plan</a>

            <?php else: ?>
                <?php echo anchor(uri_string(), '<i class="fa fa-home"></i> This week', 'class="btn btn-primary btn-sm"'); ?>
            <?php endif; ?>
        </div>

        <table>
            <thead>
            <tr>
                <th></th>
                <?php foreach ($days as $num => $day): ?>
                    <?php

                    if(isset($_GET['week']) && $_GET['week'] !== "") {
                        $date = date('Y-m-d', strtotime($_GET['week'].' +'.$num.'days'));
                    } else {
                        $date = date('Y-m-d', strtotime('monday this week '.$num.' days'));
                    }
                    ?>
                    <th class="text-center">
                        <span class="time"><?php echo $day; ?></span>
                        <span class="d-block text-sm text-warning"><?php echo $date; ?></span>
                    </th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i <= $hours; $i++): ?>
                <?php
                $hour = date('h', strtotime($h_start.' + '.$i.' hours'));
                ?>

                <tr class="<?php echo date('h') == $hour ? 'active' : ''; ?>">
                    <td class="day"><span class="text-sm"><?php echo $hour; ?></span></td>
                    <?php foreach ($days as $num => $day): ?>
                        <?php
                        if(isset($_GET['week']) && $_GET['week'] !== "") {
                            $date = date('Y-m-d', strtotime($_GET['week'].' +'.$num.'days'));
                        } else {
                            $date = date('Y-m-d', strtotime('monday this week '.$num.' days'));
                        }
                        ?>
                        <td class="hour">
                            <?php foreach ($this->activity->getActivity($date, $hour, $activities) as $item): ?>
                                <span
                                        id="<?php echo $item['id']; ?>"
                                        data-name="<?php echo $item['name']; ?>"
                                        data-description="<?php echo $item['description']; ?>"
                                        data-activity_date="<?php echo $item['activity_date']; ?>"
                                        data-activity_start="<?php echo $item['activity_start']; ?>"
                                        data-activity_end="<?php echo $item['activity_end']; ?>"
                                        class="update-activity-item activity-item">
                                    <?php echo $item['name']; ?>
                                </span>
                            <?php endforeach; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>
    </div>

</div>

<div class="modal fade" id="activityModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="activityModalLabel">New activity</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php echo form_open('activities/create'); ?> 

            <div class="modal-body">
                <label>Activity</label>
                <?php echo form_input('name', NULL, 'class="form-control" required="required" required="required"'); ?>

                <div class="row">
                    <div class="col-sm-4">
                        <label>Date</label>
                        <?php echo form_date('activity_date', date('Y-m-d'), 'class="form-control" required="required"'); ?>
                    </div>
                    <div class="col-sm-4">
					<label>Start</label>
                        <?php echo form_time('activity_start', date('H:i'), 'class="form-control" required="required"'); ?>
                    </div>
                    <div class="col-sm-4">
                        <label>End</label>
                        <?php echo form_time('activity_end', date('H:i'), 'class="form-control" required="required"'); ?>
                    </div>
                </div>

                <label>Description</label>
                <textarea class="form-control" name="description"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Submit</button>
                <a href="#" class="btn btn-danger delete hidden"><i class="fa fa-trash"></i> </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

		</div>
		
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="<?php echo base_url('/assets/js/main.js'); ?>"></script>

		</div>
</body>
</html>
