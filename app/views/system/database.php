<?php $this->view('partials/head'); ?>

<div class="container">
    <div class="row">
        <div id="mr-migrations" class="col-lg-12 loading">
            <h1><span id="database-update-count">(n/a)</span> <span data-i18n="database.migrations.pending">Database Update(s) Pending</span></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <button id="db-upgrade" class="btn btn-primary">
                <span id="db-upgrade-label" data-i18n="database.update">Update</span>
            </button>
        </div>
    </div>
    <div class="row">
        <div id="database-upgrade-log" class="col-lg-6">
            <table class="table table-console">
                <thead>
                    <tr>
                        <th colspan="1">
                            <a class="disclosure" href="#">
                                <span class="glyphicon glyphicon-chevron-right"></span> <span data-i18n="database.log">Upgrade Log</span>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-i18n="database.loghelp">Nothing to show</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>  <!-- /container -->

<script>
    $(document).on('appReady', function(e, lang) {

        // Show/Hide the upgrade log
        $('.disclosure').click(function() {
            $(this).toggleClass('disclosure-active');
            $(this).closest('table').toggleClass('disclosure-active');
        });

        $('#db-upgrade').click(function(e) {
            $(this).attr('disabled', true);
            $(this).find('#db-upgrade-label').html('Upgrading&hellip;');
            var $btn = $(this);

            function done() {
                $btn.attr('disabled', false);
                $btn.find('#db-upgrade-label').html('Update');
            }

            var tbody = $('.table-console tbody');

            $.getJSON(appUrl + '/system/migrate', function(data) {
                done();
                tbody.empty();

                if (data.error) {
                    tbody.append($('<tr><td class="log-error">' + data.error + '</td></tr>'));
                } else {
                    if (data.notes) {
                        tbody.empty();

                        for (var i = 0; i < data.notes.length; i++) {
                            tbody.append($('<tr><td>' + data.notes[i] + '</td></tr>')); // .text(data.notes[i])
                        }
                    }
                }


            }).fail(function(jqXHR, textStatus, error) {
                done();
            })
        });
        
        $.getJSON(appUrl + '/system/migrationsPending', function( data ) {
            var tbody = $('#mr-migrations tbody').empty();
            $('.loading').removeClass('loading');

            if (data.error) {
                  
            } else {

            }

            $('#database-update-count').text(data['files_pending'].length);

            if (data.files_pending) {
                for (var i = 0; i < data['files_pending'].length; i++) {
                  tbody.append($('<tr><td></td></tr>').text(data['files_pending'][i]));
                }
            }
        })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                $('#mr-db table tr td')
                    .empty()
                    .addClass('text-danger')
                    .text(i18n.t('errors.loading', {error:err}));
            });
    });
</script>
<?php
$this->view('partials/foot');