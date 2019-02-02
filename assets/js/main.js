$(document).ready(function(){
    $('.update-activity-item').click(function () {
        var div = $('#activityModal');
        var id = $(this).attr('id');
        div.find('input[name=name]').val($(this).attr('data-name'));
        div.find('input[name=activity_start]').val($(this).attr('data-activity_start'));
        div.find('input[name=activity_end]').val($(this).attr('data-activity_end'));
        div.find('input[name=activity_date]').val($(this).attr('data-activity_date'));
        div.find('textarea[name=description]').val($(this).attr('data-description'));
        div.find('#activityModalLabel').text('Update Activity');
        div.find('.delete').removeClass('hidden').attr('href', '/activities/delete/' + id);
        div.find('form').attr('action','/activities/update/'+id);
        div.modal('show');
    })

    $('.delete-item').on('click', function (e) {
        e.preventDefault();

        var loc = $(this).attr('href');
        swal({
            title: 'Deleting',
            text: 'Are you sure?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Do it!',
            closeOnConfirm: false,
            backdrop: false,
            allowOutsideClick: false
        }, function () {
            swal('processing...');
            if (loc !== undefined)
                window.location.href = loc;
        });

	})
	
    $('.clear-plan').on('click', function (e) {
        e.preventDefault();

        var loc = $(this).attr('href');
        swal({
            title: 'Deleting',
            text: 'Are you sure?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Do it!',
            closeOnConfirm: false,
            backdrop: false,
            allowOutsideClick: false
        }, function () {
            swal('processing...');
            if (loc !== undefined)
                window.location.href = loc;
        });

	})
	
    $('.copy-plan').on('click', function (e) {
        e.preventDefault();

        var loc = $(this).attr('href');
        swal({
            title: 'Confirm data overwrite',
            text: 'This will overwrite meal plan for next week',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Confirm',
            closeOnConfirm: false,
            backdrop: false,
            allowOutsideClick: false
        }, function () {
            swal('processing...');
            if (loc !== undefined)
                window.location.href = loc;
        });

    })
})