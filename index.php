<?php

    require_once './config.php';

    $connection = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    if ( !$connection ) {
        throw new Exception( "Cannot connect to database: " . mysqli_connect_error() );
    }

    $allTasks = "SELECT * FROM tasks WHERE complete = 0 ORDER BY date";
    $allTasksResult = mysqli_query( $connection, $allTasks );

    $completedTasks = "SELECT * FROM tasks WHERE complete = 1 ORDER BY date";
    $completedTasksResult = mysqli_query( $connection, $completedTasks );

    mysqli_close( $connection );

?>
<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark" />
    <link rel="stylesheet" href="./assets/pico.min.css">
    <link rel="stylesheet" href="./assets/styles.css">
    <title>Task Management</title>
</head>

<body>
    <main class="container wrapper">
        <h1 class="tc mb-2">Task Management</h1>

        <?php if ( mysqli_num_rows( $allTasksResult ) == 0 ): ?>
        <blockquote class="mb-2">No tasks found</blockquote>
        <?php else: ?>

        <div class="mb-3">
            <h4>Upcoming Tasks</h4>
            <form action="./query.php" method="POST">
                <table>
                    <thead>
                        <tr>
                            <th scope="col" class="tc">Checkbox</th>
                            <th scope="col" class="tc">Task</th>
                            <th scope="col" class="tc">Date</th>
                            <th scope="col" class="tc" width="35%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ( $data = mysqli_fetch_assoc( $allTasksResult ) ): ?>
                        <?php
    $timestamp = strtotime( $data['date'] );
    $date = date( "jS M, Y", $timestamp );
?>
                        <tr>
                            <th scope="row" class="tc">
                                <input name="bulkid[]" type="checkbox" role="switch"
                                    value="<?php echo $data['id']; ?>" />
                            </th>
                            <th class="tc"><?php echo $data['task']; ?></th>
                            <td class="tc"><?php echo $date; ?></td>
                            <td class="tc">
                                <a href="#" class="delete" data-task="<?=$data['id'];?>">Delete</a> |
                                <a href="#" class="complete" data-task="<?=$data['id'];?>"
                                    data-tooltip="Mark as Complete" data-placement="right">Complete</a>
                            </td>
                        </tr>
                        <?php endwhile;?>
                    </tbody>
                </table>
                <div class="flexbox">
                    <select name="action" class="select_field" id="bulkaction">
                        <option value="0">With selected</option>
                        <option value="bulkdelete">Delete</option>
                        <option value="bulkcomplete">Mark as complete</option>
                    </select>
                    <button class="secondary btn" id="bulksubmit">Submit</button>
                </div>
            </form>
        </div>
        <hr>
        <?php endif;?>

        <?php if ( mysqli_num_rows( $completedTasksResult ) > 0 ): ?>
        <div class="mb-3">
            <h4>Completed Tasks</h4>
            <table>
                <thead>
                    <tr>
                        <th scope="col" class="tc">Task</th>
                        <th scope="col" class="tc">Date</th>
                        <th scope="col" class="tc" width="35%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ( $completedData = mysqli_fetch_assoc( $completedTasksResult ) ): ?>
                    <?php
                        $timestamp = strtotime( $completedData['date'] );
                        $completedDate = date( "jS M, Y", $timestamp );
                    ?>
                    <tr>
                        <th class="tc"><?=$completedData['task'];?></th>
                        <td class="tc"><?=$completedDate;?></td>
                        <td class="tc">
                            <a href="#" class="delete" data-task="<?=$completedData['id'];?>">Delete</a> |
                            <a href="#" class="incomplete" data-task="<?=$completedData['id'];?>"
                                data-tooltip="Mark as Incomplete" data-placement="right">Incomplete</a>
                        </td>
                    </tr>
                    <?php endwhile;?>
                </tbody>
            </table>
        </div>
        <?php endif;?>

        <div>
            <h4>Add Tasks</h4>
            <?php
                $added = $_GET['added'] ?? "";
                if ( $added ) {
                    echo "<blockquote>Task successfully added.</blockquote>";
                }
            ?>

            <form action="./query.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="grid">
                    <div>
                        <label for="task">Task</label>
                        <input type="text" name="task" id="task" autocomplete="off">
                    </div>
                    <div>
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" autocomplete="off">
                    </div>
                </div>
                <button type="submit" name="submit" class="secondary btn">Add Task</button>
            </form>
        </div>

        <!-- for complete action -->
        <form action="./query.php" method="POST" id="completeform">
            <input type="hidden" name="action" value="complete">
            <input type="hidden" id="ctask" name="ctask">
        </form>

        <!-- for delete action -->
        <form action="./query.php" method="POST" id="deleteform">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" id="dtask" name="dtask">
        </form>

        <!-- for incomplete action -->
        <form action="./query.php" method="POST" id="incompleteform">
            <input type="hidden" name="action" value="incomplete">
            <input type="hidden" id="itask" name="itask">
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
    <script src="./assets/main.js"></script>
</body>

</html>