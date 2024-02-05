<?php
if (isset($_GET['get-table'])) {
    try {
        $stmt = $pdo->query('SELECT * FROM device_data');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    die;
}

if (isset($_GET['edit_device'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM device_data WHERE id = ?");
        $stmt->execute([$_GET['edit_device']]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    die;
}

if (isset($_GET['remove_device'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM device_data WHERE id = :id");
        $stmt->bindParam(':id', $_GET['remove_device']);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    die;
}

if (isset($_POST['device_name'])) {
    $device_name = $_POST['device_name'];
    $device_room = $_POST['device_room'];
    $device_floor = $_POST['device_floor'];
    $device_building = $_POST['device_building'];
    $device_mode = $_POST['device_mode'];
    $device_api_key = $_POST['device_api_key'];

    if (isset($_POST['device_id'])) {
        try {
            $device_id = $_POST['device_id'];
            $stmt = $pdo->prepare("UPDATE device_data SET device_name = :device_name, device_room = :device_room,device_floor = :device_floor,device_building = :device_building,device_mode = :device_mode, device_api_key= :device_api_key  WHERE id = :id");
            $stmt->bindParam(':device_name', $device_name);
            $stmt->bindParam(':device_room', $device_room);
            $stmt->bindParam(':device_mode', $device_mode);
            $stmt->bindParam(':device_floor', $device_floor);
            $stmt->bindParam(':device_building', $device_building);
            $stmt->bindParam(':device_api_key', $device_api_key);
            $stmt->bindParam(':id', $device_id);
            $stmt->execute();
            echo json_encode(array('status' => 'success'));
        } catch (PDOException $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO device_data (device_name, device_room,device_floor,device_building, device_mode, device_api_key) VALUES (?, ?, ?,?, ?, ?)');
            $stmt->execute([$device_name, $device_room, $device_floor, $device_building, $device_mode, $device_api_key]);
            echo json_encode(array('status' => 'success'));
        } catch (PDOException $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
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
                <div class="column">
                    <div class="card">
                        <form id="form" class="card-content" method="post" onsubmit="return false">

                            <p class="title">
                                Add Devices
                            </p>
                            <div>
                                <div class="field">
                                    <p class="control has-icons-left has-icons-right">
                                        <input class="input" type="text" placeholder="Device" name="device_name"
                                            id="device_name">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-server"></i>
                                        </span>
                                    </p>
                                </div>

                                <div class="field">
                                    <p class="control has-icons-left has-icons-right">
                                        <input class="input" type="text" placeholder="Room" value="" name="device_room"
                                            id="device_room">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                        </span>
                                    </p>
                                </div>

                                <div class="field has-addons">
                                    <p class="control is-expanded has-icons-left">
                                        <input class="input" type="number" placeholder="Floor" value="" max="500"
                                            min="0" name="device_floor" id="device_floor">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-building"></i>
                                        </span>
                                    </p>
                                    <p class="control is-expanded">
                                        <input class="input" type="text" placeholder="Building" value=""
                                            name="device_building" id="device_building">
                                    </p>
                                </div>

                                <div class="field">
                                    <p class="control has-icons-left">
                                        <span class="select is-fullwidth " id="device_mode">
                                            <select name="device_mode" id="device_mode_select">
                                                <option value="" selected disabled>Mode</option>
                                                <option value="0">Enrollment</option>
                                                <option value="1">Attendance</option>
                                            </select>
                                        </span>
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-gears"></i>
                                        </span>
                                    </p>
                                </div>

                                <div class="field is-grouped">
                                    <p class="control is-expanded">
                                        <input id="apikey" class="input" type="text" placeholder="API Key"
                                            name="device_api_key" id="device_api_key">
                                    </p>
                                    <p class="control">
                                        <button id="keygen" class="button is-warning">
                                            <div class="icon">
                                                <i class="fa-solid fa-dice"></i>
                                            </div>
                                            <span>Generate API</span>
                                        </button>
                                    </p>
                                </div>


                                <input type="text" id="device_id" name="device_id" hidden>

                                <div class="field is-grouped ">

                                    <p class="control">
                                        <button class="button is-success is-fullwidth is-responsive"
                                            onclick="saveDevice()">
                                            <div class="icon">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                            <span>Save Device</span>
                                        </button>
                                    </p>

                                    <p class="control">
                                        <button class="button is-danger is-fullwidth"
                                            onclick="$('#form input').val(''); $('#device_mode_select').val('');">
                                            <div class="icon">
                                                <i class="fa-solid fa-trash"></i>
                                            </div>
                                            <span>Clear</span>
                                        </button>
                                    </p>

                                    <progress class="progress is-danger" max="100" style="display: none;">30%</progress>


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="column is-8-desktop">
                    <div class="card">
                        <div class="card-content">
                            <p class="title">
                                Devices
                            </p>
                            <div class="table-container">
                                <div class="table-container">
                                    <div class="table-container">
                                        <div class="table-container">
                                            <table class="table is-fullwidth is-hoverable is-horizontal"
                                                id="device-table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Room</th>
                                                        <th>Floor & Building</th>
                                                        <th>Mode</th>
                                                        <th>Api</th>
                                                        <th>Created</th>
                                                        <th>Edit</th>

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
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function () {
            loadTable();
        });

        // setInterval(function () {
        //     loadTable();
        // }, 5000);

        function loadTable() {
            $.ajax({
                type: 'GET',
                data: 'get-table',
                success: function (response) {
                    // console.log(response)
                    var result = JSON.parse(response);
                    var tbody = $('#device-table tbody');
                    tbody.empty();
                    for (var i = 0; i < result.length; i++) {
                        var device = result[i];
                        var tr = $('<tr>');
                        tr.append($('<td>').text(device.device_name));
                        tr.append($('<td>').text(device.device_room));
                        tr.append($('<td>').text(device.device_floor + "F - " + device.device_building));
                        tr.append($('<td>').text((device.device_mode == 0) ? "Enrollment" : "Attendance"));
                        tr.append($('<td>').text(device.device_api_key));
                        tr.append($('<td>').text(convertTime(device.device_created)));
                        tr.append($('<td>').html('<button class="button is-success is-small" onclick="editDevice(' + device.id + ')"><div class="icon"><i class="fa-solid fa-pen"></i></div></button ><button class="button is-danger is-small" onclick="removeDevice(' + device.id + ')"><div class="icon"><i class="fa-solid fa-trash"></i></div></button >'));
                        tbody.append(tr);
                    }
                }
            });
        }

        function editDevice(id) {
            $.ajax({
                type: 'GET',
                data: { 'edit_device': id },
                success: function (response) {
                    var result = JSON.parse(response);
                    $('#device_name').val(result.device_name)
                    $('#device_id').val(result.id)
                    $('#device_room').val(result.device_room)
                    $('#device_floor').val(result.device_floor)
                    $('#device_building').val(result.device_building)
                    $('#device_mode_select').val(result.device_mode)
                    $('#apikey').val(result.device_api_key)
                }
            });
        }

        function removeDevice(id) {
            $.ajax({
                type: 'GET',
                data: { 'remove_device': id },
                success: function () {
                    loadTable();
                }
            });
        }

        function saveDevice() {
            var obj = {};
            $.each($('#form').serializeArray(), function (_, val) {
                // console.log(val.name);
                if (val.value.trim() === "" && !$('#' + val.name).hasClass('no-validation')) {
                    $('#' + val.name).addClass('is-danger');
                    return false;
                }

                if (val.value.trim() != '') {
                    obj[val.name] = val.value;
                }
                $('#' + val.name).removeClass('is-danger');
            });


            // console.log(obj)
            $.ajax({
                type: 'POST',
                data: obj,
                success: function () {
                    loadTable();
                    $('#form input').val('');
                    $('#device_mode_select').val('');
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

    <script>
        function generateUUID() {
            var d = new Date().getTime();

            if (window.performance && typeof window.performance.now === "function") {
                d += performance.now();
            }

            var uuid = 'xx-4xxx-yxxx-xxxx'.replace(/[xy]/g, function (c) {
                var r = (d + Math.random() * 16) % 16 | 0;
                d = Math.floor(d / 16);
                return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(18);
            });
            return uuid;
        }
        $('#keygen').on('click', function () {
            $('#apikey').val(generateUUID());
        });
    </script>
</body>
<?php include "public/partials/html_footer.php"; ?>

</html>
