<?php

if (isset($_GET['get-table'])) {
    try {
        $stmt = $pdo->query('SELECT * FROM students_data');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    die;
}



// if (isset($_GET['get-table'])) {
//     try {
//         $stmt = $pdo->query('SELECT students_data.*, device_data.device_name, student_sections.section_name, student_sections.section_adviser
//         FROM students_data
//         JOIN student_sections ON students_data.student_section = student_sections.id
//         JOIN device_data ON students_data.student_registered = device_data.id');
//         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         echo json_encode($result);
//     } catch (PDOException $e) {
//         echo "Error: " . $e->getMessage();
//     }
//     die;
// }



// if (isset($_GET['get-table'])) {
//     try {
//         $stmt = $pdo->query('SELECT students_data.*, device_data.device_name, student_sections.section_name, student_sections.section_adviser
//         FROM students_data
//         JOIN student_sections ON students_data.student_section = student_sections.id
//         JOIN device_data ON students_data.student_registered = device_data.id');
//         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         echo json_encode($result);
//     } catch (PDOException $e) {
//         echo "Error: " . $e->getMessage();
//     }
//     die;
// }



if (isset($_GET['edit_student'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM students_data WHERE id = ?");
        $stmt->execute([$_GET['edit_student']]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {

        echo "Error: " . $e->getMessage();
    }
    die;
}

if (isset($_GET['remove_student'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM students_data WHERE id = :id");
        $stmt->bindParam(':id', $_GET['remove_student']);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    die;
}

if (isset($_POST['student_name'])) {
    $student_name = $_POST['student_name'];
    $student_section = $_POST['student_section'];
    $student_gender = $_POST['student_gender'];
    $student_id = $_POST['student_id'];
    $student_card_id = $_POST['student_card_id'];

    if (isset($_POST['id'])) {
        try {
            $id = $_POST['id'];
            $stmt = $pdo->prepare("UPDATE students_data SET student_name = :student_name, student_section = :student_section, student_gender = :student_gender, student_id = :student_id, student_card_id = :student_card_id WHERE id = :id");
            $stmt->bindParam(':student_name', $student_name);
            $stmt->bindParam(':student_gender', $student_gender);
            $stmt->bindParam(':student_section', $student_section);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':student_card_id', $student_card_id);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo json_encode(array('status' => 'success'));
        } catch (PDOException $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO students_data (student_name, student_section, student_gender, student_id, student_card_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$student_name, $student_section, $student_gender, $student_id, $student_card_id]);
            echo json_encode(array('status' => 'success'));
        } catch (PDOException $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    }
    die;
}


// if (isset($_POST['device_name'])) {
//     $device_name = $_POST['device_name'];
//     $device_room = $_POST['device_room'];
//     $device_floor = $_POST['device_floor'];
//     $device_building = $_POST['device_building'];
//     $device_mode = $_POST['device_mode'];
//     $device_api_key = $_POST['device_api_key'];

//     if (isset($_POST['device_id'])) {
//         try {
//             $device_id = $_POST['device_id'];
//             $stmt = $pdo->prepare("UPDATE device_data SET device_name = :device_name, device_room = :device_room,device_floor = :device_floor,device_building = :device_building,device_mode = :device_mode, device_api_key= :device_api_key  WHERE id = :id");
//             $stmt->bindParam(':device_name', $device_name);
//             $stmt->bindParam(':device_room', $device_room);
//             $stmt->bindParam(':device_mode', $device_mode);
//             $stmt->bindParam(':device_floor', $device_floor);
//             $stmt->bindParam(':device_building', $device_building);
//             $stmt->bindParam(':device_api_key', $device_api_key);
//             $stmt->bindParam(':id', $device_id);
//             $stmt->execute();
//             echo json_encode(array('status' => 'success'));
//         } catch (PDOException $e) {
//             echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
//         }
//     } else {
//         try {
//             $stmt = $pdo->prepare('INSERT INTO device_data (device_name, device_room,device_floor,device_building, device_mode, device_api_key) VALUES (?, ?, ?,?, ?, ?)');
//             $stmt->execute([$device_name, $device_room, $device_floor, $device_building, $device_mode, $device_api_key]);
//             echo json_encode(array('status' => 'success'));
//         } catch (PDOException $e) {
//             echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
//         }
//     }
//     die;
// }

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
                    <section class=" card">
                        <!-- <div class="card-image">
                            <figure class="image is-1by1">
                                <img src="https://i.kym-cdn.com/photos/images/original/001/384/545/7b9.jpg"
                                    alt="Placeholder image">
                            </figure>
                        </div> -->
                        <div class="card-content" id="overviewStudent" hidden>
                            <div class="media">
                                <div class="media-content">
                                    <p class="title is-4">Kintoyyy</p>
                                    <p class="subtitle is-6">@kintoyyy</p>
                                </div>
                            </div>

                            <div class="content">
                                <ul>
                                    <li>Section: ST12P1</li>
                                    <li>Adviser: Sir luchi</li>
                                    <li>Room: A1-301</li>
                                    <li>Class: PM</li>
                                </ul>
                            </div>


                        </div>


                        <form id="form" class="card-content" method="post" enctype="multipart/form-data"
                            onsubmit="return false">

                            <p class="title">
                                Edit Student
                            </p>
                            <div>
                                <div class="field">
                                    <p class="control has-icons-left has-icons-right">
                                        <input class="input" type="text" placeholder="Student Name" name="student_name"
                                            id="student_name">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-graduation-cap"></i>
                                        </span>
                                    </p>
                                </div>



                                <div class="field">
                                    <p class="control has-icons-left">
                                        <span class="select is-fullwidth " id="student_gender">
                                            <select name="student_gender" id="student_gender_select">
                                                <option value="" selected disabled>Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </span>
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-venus-mars"></i>
                                        </span>
                                    </p>
                                </div>

                                <div class="field">
                                    <p class="control has-icons-left">
                                        <span class="select is-fullwidth " id="student_section">
                                            <select name="student_section" id="student_section_select">
                                                <option value="" selected disabled>Select Section</option>
                                                <?php $stmt = $pdo->query('SELECT * FROM student_sections');
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                                    <option value="<?= $row['id'] ?>"><?= $row['section_name'] ?> </option>
                                                <?php } ?>
                                            </select>
                                        </span>
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                        </span>
                                    </p>
                                </div>
                                <div class="field">
                                    <p class="control has-icons-left has-icons-right">
                                        <input class="input" type="number" placeholder="Student ID" name="student_id"
                                            id="student_id">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-id-card"></i>
                                        </span>
                                    </p>
                                </div>

                                <div class="field is-grouped">
                                    <p class="control is-expanded  has-icons-left">
                                        <input class="input" type="number" placeholder="Card ID" name="student_card_id"
                                            id="student_card_id">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-id-badge"></i>
                                        </span>
                                    </p>

                                    <!-- <p class="control">
                                        <button id="keygen" class="button is-warning" onclick="registercard()">
                                            <div class="icon">
                                                <i class="fa-solid fa-barcode"></i>
                                            </div>
                                            <span>Scan Card</span>
                                        </button>
                                    </p> -->
                                </div>
<!-- 
                                <div class="field">
                                    <p class="control has-icons-left">
                                        <span class="select is-fullwidth " id="student_section">
                                            <select name="student_card_id" id="student_card_id">
                                                <option value="" selected disabled>Select Card</option>
                                                <?php $stmt = $pdo->query('SELECT * FROM logsss ORDER BY id DESC');
                                                $ambot = $stmt->fetch(PDO::FETCH_ASSOC);
                                                for ($i = 0; $i < 5; $i++) {
                                                    echo $ambot[$i]['card'];
                                                }
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                                    <option value="<?= $row['card'] ?>"><?= $row['card'] ?> </option>
                                                <?php } ?>
                                            </select>
                                        </span>
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                        </span>
                                    </p>
                                </div> -->
                                <!-- 
                                <div class="field">
                                    <p class="control has-icons-left has-icons-right">
                                        <input class="input" type="text" placeholder="Adviser" value=""
                                            name="student_adviser" id="student_adviser">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-person-chalkboard"></i>
                                        </span>
                                    </p>
                                </div>

                                <div class="field">
                                    <p class="control has-icons-left has-icons-right">
                                        <input class="input" type="text" placeholder="room" value="" name="student_room"
                                            id="student_room">
                                        <span class="icon is-small is-left">
                                            <i class="fa-solid fa-school"></i>
                                        </span>
                                    </p>
                                </div> -->

                                <!-- 

                                <div class="field">
                                    <div class="control is-fullwidth file has-name is-right">
                                        <label class="file-label">
                                            <input class="file-input" type="file" name="resume">
                                            <span class="file-cta">
                                                <span class="file-icon">
                                                    <i class="fas fa-upload"></i>
                                                </span>
                                                <span class="file-label">
                                                    Choose a file…
                                                </span>
                                            </span>
                                            <span class="file-name">

                                            </span>
                                        </label>
                                    </div>
                                </div> -->



                                <input type="text" id="id" name="id" hidden>

                                <div class="field is-grouped ">

                                    <p class="control">
                                        <button class="button is-success is-fullwidth is-responsive"
                                            onclick="saveDevice()">
                                            <div class="icon">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                            <span>Save Student</span>
                                        </button>
                                    </p>

                                    <p class="control">
                                        <button class="button is-danger is-fullwidth"
                                            onclick="$('#form input').val(''); $('#student_gender_select').val('');$('#student_section_select').val('');">
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

                        <!-- <footer class="card-footer">
                            <a href="#" class="card-footer-item button is-danger">
                                <div class="icon is-small">
                                    <i class="fa-sharp fa-solid fa-question"></i>
                                </div>
                                <span>Absent</span>
                            </a>
                            <a href="#" class="card-footer-item button is-info">
                                <div class="icon is-small">
                                    <i class="fa-sharp fa-solid fa-clock"></i>
                                </div>
                                <span>Late</span>
                            </a>
                            <a href="#" class="card-footer-item button is-success">
                                <div class="icon is-small">
                                    <i class="fa-sharp fa-solid fa-user-graduate"></i>
                                </div>
                                <span>Present</span>
                            </a>
                        </footer> -->
                    </section>
                </div>
                <div class="column is-8-desktop">
                    <div class="card">
                        <div class="card-content">
                            <p class="title">
                                Students
                            </p>
                            <div class="table-container">
                                <div class="table-container">
                                    <table class="table is-fullwidth is-hoverable is-horizontal" id="students-table">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Gender</th>
                                                <!-- <th>Section</th> -->
                                                <th>ID</th>
                                                <th>Card</th>
                                                <!-- <th>Adviser</th> -->
                                                <th>Edit</th>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                                <th data-label="Student">Kent Rato</th>
                                                <td>Male</td>
                                                <td data-label="Student">ST12P1</td>
                                                <td>8172353</td>
                                                <td>A1-301</td>
                                                <td>
                                                    <button class="button is-success is-small"
                                                        onclick="editStudent(' + device.id + ')">
                                                        <div class="icon"><i class="fa-solid fa-eye"></i></div>
                                                    </button>
                                                </td>
                                            </tr> -->

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

<!-- <script>
    $(document).ready(function () {
        loadTable();
    });

    setInterval(function () {
        loadTable();
    }, 5000);



</script> -->


<script>
    $(document).ready(function () {
        loadTable();
    });

    // setInterval(function () {
    //     loadTable();
    // }, 1000);

    // function loadTable() {
    //     $.ajax({
    //         type: 'GET',
    //         data: 'get-table',
    //         success: function (response) {
    //             // console.log(response)
    //             var result = JSON.parse(response);
    //             var tbody = $('#students-table tbody');
    //             tbody.empty();
    //             for (var i = 0; i < result.length; i++) {
    //                 var device = result[i];
    //                 var tr = $('<tr>');
    //                 tr.append($('<td>').text(device.student_name));
    //                 tr.append($('<td>').text(device.student_section));
    //                 tr.append($('<td>').text(device.student_name + "F - " + device.student_id));
    //                 tr.append($('<td>').text((device.student_card_id == 0) ? "Enrollment" : "Attendance"));
    //                 tr.append($('<td>').text(device.student_adviser));
    //                 tr.append($('<td>').text(convertTime(device.device_created)));
    //                 tr.append($('<td>').html('<button class="button is-success is-small" onclick="editStudent(' + device.id + ')"><div class="icon"><i class="fa-solid fa-pen"></i></div></button ><button class="button is-danger is-small" onclick="removeDevice(' + device.id + ')"><div class="icon"><i class="fa-solid fa-trash"></i></div></button >'));
    //                 tbody.append(tr);
    //             }
    //         }
    //     });
    // }


    function registercard() {
        $.ajax({
            type: 'GET',
            data: 'register-card',
            success: function (response) {
                console.log(response)
            }
        });
    }
    function loadTable() {
        $.ajax({
            type: 'GET',
            data: 'get-table',
            success: function (response) {
                console.log(response)
                var result = JSON.parse(response);
                var tbody = $('#students-table tbody');
                tbody.empty();
                for (var i = 0; i < result.length; i++) {
                    var user = result[i];
                    var tr = $('<tr>');
                    tr.append($('<td>').text(user.student_name));
                    tr.append($('<td>').text(user.student_gender));
                    // tr.append($('<td>').text(user.section_name));
                    tr.append($('<td>').text(user.student_id));
                    tr.append($('<td>').text(user.student_card_id));
                    // tr.append($('<td>').text(user.section_adviser));
                    tr.append($('<td>').html('<button class="button is-success is-small" onclick="editStudent(' + user.id + ')"><div class="icon"><i class="fa-solid fa-pen"></i></div></button ><button class="button is-danger is-small" onclick="removeDevice(' + user.id + ')"><div class="icon"><i class="fa-solid fa-trash"></i></div></button >'));
                    tbody.append(tr);
                }
            }
        });
    }

    function editStudent(id) {
        console.log("edit student: " + id)
        $.ajax({
            type: 'GET',
            data: { 'edit_student': id },
            success: function (response) {
                var result = JSON.parse(response);

                $('#student_name').val(result.student_name)
                $('#student_gender_select').val(result.student_gender)
                $('#student_section_select').val(result.student_section)
                $('#id').val(result.id)
                $('#student_id').val(result.student_id)
                $('#student_card_id').val(result.student_card_id)
                // $('#student_adviser').val(result.student_adviser)
                // $('#apikey').val(result.student_adviser)
            }
        });
    }

    function removeDevice(id) {
        $.ajax({
            type: 'GET',
            data: { 'remove_student': id },
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


        console.log(obj)
        $.ajax({
            type: 'POST',
            data: obj,
            success: function () {
                loadTable();
                $('#form input').val('');
                $('#student_gender').val('');
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

</html>
