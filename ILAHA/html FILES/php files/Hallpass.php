php include()
<?php

if (isset($_GET['get-table'])) {
    try {
        $stmt = $pdo->query('SELECT * FROM hallpass_data');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    die;
}

if (isset($_GET['edit_hallpass'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM hallpass_data WHERE id = ?");
        $stmt->execute([$_GET['edit_hallpass']]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
    }
    die;
}

if (isset($_GET['remove_hallpass'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM hallpass_data WHERE id = :id");
        $stmt->bindParam(':id', $_GET['remove_hallpass']);
        $stmt->execute();
    } catch (PDOException $e) {
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
    }
    die;
}
if (isset($_POST['hallpass_name'])) {
    $hallpass_name = $_POST['hallpass_name'];
    $hallpass_destination = $_POST['hallpass_destination'];
    $hallpass_room = $_POST['hallpass_room'];
    $keypad_key = $_POST['keypad_key'];

    $hours = isset($_POST['hours']) ? $_POST['hours'] : "00";
    $minutes = isset($_POST['minutes']) ? $_POST['minutes'] : "00";
    $seconds = isset($_POST['seconds']) ? $_POST['seconds'] : "00";

    $hallpass_limit = $hours . ":" . $minutes . ":" . $seconds;

    if (isset($_POST['hallpass_id'])) {
        try {
            $hallpass_id = $_POST['hallpass_id'];
            $stmt = $pdo->prepare("UPDATE hallpass_data SET hallpass_name = :hallpass_name, hallpass_destination = :hallpass_destination, hallpass_room  = :hallpass_room , keypad_key  = :keypad_key, hallpass_limit = :hallpass_limit  WHERE id = :id");
            $stmt->bindParam(':hallpass_name', $hallpass_name);
            $stmt->bindParam(':hallpass_destination', $hallpass_destination);
            $stmt->bindParam(':hallpass_room', $hallpass_room);
            $stmt->bindParam(':keypad_key', $keypad_key);
            $stmt->bindParam(':id', $hallpass_id);
            $stmt->bindParam(':hallpass_limit', $hallpass_limit);
            $stmt->execute();
            echo json_encode(array('status' => 'success'));
        } catch (PDOException $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO hallpass_data (hallpass_name, hallpass_destination,keypad_key, hallpass_room,hallpass_limit) VALUES (?,?, ?, ?,?)');
            $stmt->execute([$hallpass_name, $hallpass_destination, $keypad_key, $hallpass_room, $hallpass_limit]);
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
                        <form id="form" class="card-content" onsubmit="return false">

                            <p class="title">
                                Add hallpass
                            </p>
                            <div>
                                <input type="text" id="hallpass_id" name="hallpass_id" class=" no-validation" hidden>

                                <div class="field">
                                    <p class="control has-icons-left has-icons-right">
                                        <input class="input" type="text" placeholder="Display Name" name="hallpass_name"
                                            id="hallpass_name">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-server"></i>
                                        </span>
                                    </p>
                                </div>


                                <div class="field">
                                    <p class="control has-icons-left has-icons-right">
                                        <input class="input" type="text" placeholder="Room" value=""
                                            name="hallpass_room" id="hallpass_room">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                        </span>
                                    </p>
                                </div>

                                <div class="field">
                                    <p class="control has-icons-left has-icons-right">
                                        <input class="input" type="text" placeholder="Destination" value=""
                                            name="hallpass_destination" id="hallpass_destination">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-map-location-dot"></i>
                                        </span>
                                    </p>
                                </div>

                                <div class="field">
                                    <p class="control has-icons-left">
                                        <span class="select is-fullwidth " id="keypad_key">
                                            <select name="keypad_key" id="keypad_key_select">
                                                <option value="" selected disabled>Assigned Keypad Char</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                            </select>
                                        </span>
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-keyboard"></i>
                                        </span>
                                    </p>
                                </div>

                                <div class="field has-addons">
                                    <p class="control is-expanded has-icons-left">
                                        <input class="input no-validation" type="number" max="24" min="0"
                                            placeholder="Hours" name="hours" id="hours">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-bell"></i>
                                        </span>
                                    </p>
                                    <p class="control is-expanded">
                                        <input class="input no-validation" type="number" max="60" min="0"
                                            placeholder="Minutes" name="minutes" id="minutes">
                                    </p>
                                    <p class="control is-expanded">
                                        <input class="input no-validation" type="number" max="60" min="0"
                                            placeholder="Seconds" name="seconds" id="seconds">
                                    </p>
                                </div>



                                <div class="field is-grouped">

                                    <p class="control">
                                        <button class="button is-success" onclick="saveHallpass()">
                                            <div class="icon">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                            <span>Save Device</span>
                                        </button>
                                    </p>

                                    <p class="control">
                                        <button class="button is-danger"
                                            onclick="$('#form input').val('');$('#keypad_key_select').val('') ">
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
                                Hallpass (Max 4 Destinations)
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
                                                        <th>Destination</th>
                                                        <th>Room</th>
                                                        <th>Limit</th>
                                                        <th>Keypad Char</th>
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
        $(document).ready(function () {saveHallpass
            loadTable();
        });

        function saveHallpass() {
            var obj = {};
            $.each($('#form').serializeArray(), function (_, val) {
                if (val.value.trim() === "" && !$('#' + val.name).hasClass('no-validation')) {
                    $('#' + val.name).addClass('is-danger');
                    return false; // exit the loop
                }

                if (val.value.trim() != '') {
                    obj[val.name] = val.value;
                }
                $('#' + val.name).removeClass('is-danger');
            });

            $.ajax({
                type: 'POST',
                data: obj,
                success: function () {
                    loadTable();
                    $('#form input').val('');
                    $('#keypad_key_select').val('');
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    console.log(status);
                    console.log(error);
                    // handle error response
                }
            });
        }

        function loadTable() {
            $.ajax({
                type: 'GET',
                data: 'get-table',
                success: function (response) {
                    var result = JSON.parse(response);
                    var tbody = $('#device-table tbody');
                    tbody.empty();
                    for (var i = 0; i < result.length; i++) {
                        var hallpass = result[i];
                        var tr = $('<tr>');
                        tr.append($('<td>').text(hallpass.hallpass_name));
                        tr.append($('<td>').text(hallpass.hallpass_destination));
                        tr.append($('<td>').text(hallpass.hallpass_room));
                        tr.append($('<td>').text(formatTime(hallpass.hallpass_limit)));
                        tr.append($('<td>').text(hallpass.keypad_key));
                        // tr.append($('<td>').text(hallpass.hallpass_created));
                        tr.append($('<td>').html('<button class="button is-success is-small" onclick="editDevice(' + hallpass.id + ')"><div class="icon"><i class="fa-solid fa-pen"></i></div></button ><button class="button is-danger is-small" onclick="removeDevice(' + hallpass.id + ')"><div class="icon"><i class="fa-solid fa-trash"></i></div></button >'));
                        tbody.append(tr);
                    }
                }
            });
        }

        function editDevice(id) {
            $.ajax({
                type: 'GET',
                data: {
                    'edit_hallpass': id
                },
                success: function (response) {
                    var result = JSON.parse(response);
                    $('#hallpass_name').val(result.hallpass_name)
                    $('#hallpass_id').val(result.id)
                    $('#hallpass_destination').val(result.hallpass_destination)
                    $('#hallpass_room').val(result.hallpass_room)
                    $('#keypad_key_select').val(result.keypad_key)

                    var timeArray = result.hallpass_limit.split(':');
                    $('#hours').val((parseInt(timeArray[0], 10) === 0) ? '' : parseInt(timeArray[0], 10));
                    $('#minutes').val((parseInt(timeArray[1], 10) === 0) ? '' : parseInt(timeArray[1], 10))
                    $('#seconds').val((parseInt(timeArray[2], 10) === 0) ? '' : parseInt(timeArray[2], 10))
                }
            });
        }

        function removeDevice(id) {
            $.ajax({
                type: 'GET',
                data: {
                    'remove_hallpass': id
                },
                success: function () {
                    loadTable();
                }
            });
        }

        function formatTime(time) {
            var timeParts = time.split(":");
            var hours = parseInt(timeParts[0]);
            var minutes = parseInt(timeParts[1]);
            var seconds = parseInt(timeParts[2]);
            var output = "";
            if (hours > 0) {
                output += hours + " hrs ";
            }
            if (minutes > 0) {
                output += minutes + " mins ";
            }
            if (seconds > 0) {
                output += seconds + " secs";
            }
            return output.trim();

        }

    </script>
</body>
<?php include "public/partials/html_footer.php"; ?>

</html>