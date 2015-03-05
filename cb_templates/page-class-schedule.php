<div class="full_width col-md-12 centered">
    <div class="sub_accordian" style="float: left; width: 100% ! important;">
        <div class="panel-group" id="accordion">
            <?php
            if (CB\Posttypes::havePosts()) {
                $courses = CB\Posttypes::queryPosts();
                foreach ($courses as $course) :
            ?>
                <!-- repeat certificates -->
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab">
                        <h4 class="panel-title no-margin">
                            <button class="btn-link no-bg" href="#collapse<?php echo $course['category']['cat_id']; ?>" data-parent="#accordion" data-toggle="collapse">
                                <?php echo $course['classes'][0]['agency'] . ' ' . $course['classes'][0]['course']; ?>
                            </button>
                        </h4>
                    </div>
                    <div id="collapse<?php echo $course['category']['cat_id']; ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php if ($course['category']['cat_comment']) {
                                echo '<h4><strong>Course Description</strong></h4>';
                                echo '<h5>'. $course['category']['cat_comment'] . '</h5>';
                            }
                            foreach($course['classes'] as $class) :
                            ?>
                            <!-- repeat classes -->
                            <table width="100%"  border="1" class="table table-bordered">
                                <tr>
                                    <td>
                                        <div>
                                            <a href="<?php echo $class['url']; ?>" class="pull-left">
                                                <?php echo $class['datetime']; ?>
                                            </a>
                                            <div class="pull-right"><?php echo $class['location']; ?></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <!-- repeat classes -->
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <!-- repeat certificates -->
                <?php
                endforeach;
            } else { ?>
                <h3>No classes scheduled yet.</h3>
            <?php } ?>
        </div>
    </div>
</div>
