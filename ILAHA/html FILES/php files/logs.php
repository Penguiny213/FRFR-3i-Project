<?php
if (isset($_GET['get-table'])) {
    try {
        $stmt = $pdo->query('SELECT * FROM attendance_logs');

        $stmt = $pdo->query('SELECT attendance_logs.attendance_event,attendance_logs.time_in,attendance_logs.time_out, attendance_logs.log_time, students_data.student_name,students_data.student_id
        FROM attendance_logs
        JOIN students_data ON attendance_logs.student_id = students_data.id
        ORDER BY attendance_logs.log_time DESC;
        -- JOIN student_sections ON students_data.students_section = student_sections.id;s
        ');


        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    die;
}

if (isset($_GET['get-table2'])) {
    try {
        $stmt = $pdo->query('SELECT * FROM logsss ORDER BY time_created DESC');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    die;
}
?>
<!DOCTYPE html>
<html>
<?php include "public/partials/html_headers.php"; ?>
<?php include "public/partials/html_navbar_top.php"; ?>

<body>
    <?php include "public/partials/html_hero_header.php"; ?>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-7-desktop ">
                    <div class="card ">
                        <div class="tabs is-centered">
                            <ul>
                                <!-- <li class="is-active">
                                    <a>
                                        <span class="icon is-small"><i class="fa-solid fa-address-book"></i></span>
                                        <span>Attendance Logs</span>
                                    </a>
                                </li> -->
                                <!-- <li class="is-active">
                                    <a>
                                        <span class="icon is-small"><i class="fa-solid fa-list-ul"></i></span>
                                        <span>Device Logs</span>
                                    </a>
                                </li> -->
                            </ul>
                        </div>
                        <div class="card-content">
                            <p class="title">
                                Device Logs
                                <!-- <button class="button is-outlined">Export</button> -->
                            </p>
                            <div class="table-container">
                                <div class="table-container">
                                    <table class="table is-fullwidth is-hoverable is-horizontal" id="logsTable">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Stident ID</th>
                                                <!-- <th>Device</th> -->
                                                <!-- <th>Room</th> -->
                                                <th>Time In</th>
                                                <th>Time Out</th>
                                                <th>Event</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-4-desktop ">
                    <div class="card ">
                        <div class="tabs is-centered">
                            <ul>
                                <!-- <li class="is-active">
                                    <a>
                                        <span class="icon is-small"><i class="fa-solid fa-address-book"></i></span>
                                        <span>Attendance Logs</span>
                                    </a>
                                </li> -->
                                <!-- <li class="is-active">
                                    <a>
                                        <span class="icon is-small"><i class="fa-solid fa-list-ul"></i></span>
                                        <span>Device Logs</span>
                                    </a>
                                </li> -->
                            </ul>
                        </div>
                        <div class="card-content">
                            <p class="title">
                                RFID Card logs
                                <!-- <button class="button is-outlined">Export</button> -->
                            </p>
                            <div class="table-container">
                                <div class="table-container">
                                    <table class="table is-fullwidth is-hoverable is-horizontal" id="devicelogsTable">
                                        <thead>
                                            <tr>
                                                <th>RFID Card</th>
                                                <th>Time </th>
                                                <!-- <th>Device</th> -->
                                                <!-- <th>Room</th> -->
                                                <!-- <th>Time In</th>
                                                <th>Time Out</th>
                                                <th>Event</th> -->
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</body>
<?php include "public/partials/html_footer.php"; ?>

<script>
    $(document).ready(function () {
        loadTable();
        loadTable2();
    });

    setInterval(function () {
        loadTable();
        loadTable2();
    }, 5000);

    function loadTable() {
        $.ajax({
            type: 'GET',
            data: 'get-table',
            success: function (response) {
                // console.log(response)
                var result = JSON.parse(response);
                var tbody = $('#logsTable tbody');
                tbody.empty();
                for (var i = 0; i < result.length; i++) {
                    var device = result[i];
                    var tr = $('<tr>');
                    tr.append($('<td>').text(device.student_name));
                    tr.append($('<td>').text(device.student_id));
                    tr.append($('<td>').text(convertTime(device.time_in)));
                    tr.append($('<td>').text((device.time_out) ? convertTime(device.time_out) : ""));
                    tr.append($('<td>').text(device.attendance_event));

                    // tr.append($('<td>').text(device.device_floor + "F - " + device.device_building));
                    // tr.append($('<td>').text((device.device_mode == 0) ? "Enrollment" : "Attendance"));
                    // tr.append($('<td>').text(device.device_api_key));

                    // tr.append($('<td>').html('<button class="button is-success is-small" onclick="editDevice(' + device.id + ')"><div class="icon"><i class="fa-solid fa-pen"></i></div></button ><button class="button is-danger is-small" onclick="removeDevice(' + device.id + ')"><div class="icon"><i class="fa-solid fa-trash"></i></div></button >'));
                    tbody.append(tr);
                }
            }
        });
    }
    function loadTable2() {
        $.ajax({
            type: 'GET',
            data: 'get-table2',
            success: function (response) {
                // console.log(response)
                var result = JSON.parse(response);
                var tbody = $('#devicelogsTable tbody');
                tbody.empty();
                for (var i = 0; i < result.length; i++) {
                    var device = result[i];
                    var tr = $('<tr>');
                    tr.append($('<td>').text(device.card));
                    // tr.append($('<td>').text(device.time_created));
                    tr.append($('<td>').text(convertTime(device.time_created)));
                    // tr.append($('<td>').text((device.time_out) ? convertTime(device.time_out) : ""));
                    // tr.append($('<td>').text(device.attendance_event));

                    // tr.append($('<td>').text(device.device_floor + "F - " + device.device_building));
                    // tr.append($('<td>').text((device.device_mode == 0) ? "Enrollment" : "Attendance"));
                    // tr.append($('<td>').text(device.device_api_key));

                    // tr.append($('<td>').html('<button class="button is-success is-small" onclick="editDevice(' + device.id + ')"><div class="icon"><i class="fa-solid fa-pen"></i></div></button ><button class="button is-danger is-small" onclick="removeDevice(' + device.id + ')"><div class="icon"><i class="fa-solid fa-trash"></i></div></button >'));
                    tbody.append(tr);
                }
            }
        });
    }
    function convertTime(dateTime) {
        const date = new Date(dateTime);

        const month = date.getMonth() + 1;
        const day = date.getDate();
        const year = date.getFullYear().toString().substr(-2);

        const hours = date.getHours();
        const minutes = date.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const formattedHours = hours % 12 || 12;

        return `${month}/${day}/${year} - ${formattedHours}:${minutes < 10 ? '0' + minutes : minutes} ${ampm}`;
    }
</script>

</html>
