<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Ticket List</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->

                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <!--left sidebar-->

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                        <div class="Social-login-box">

                           <!-- <?php /*echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_ticket_form'),'admin'=>true)); */?>


                            <div class="form-group">
                                <div class="col-sm-4">

                                    <?php /*echo $this->Form->input('username', array('type' => 'text', 'placeholder' => 'Insert username', 'label' => 'Search by username', 'class' => 'form-control')); */?>
                                </div>
                                <div class="col-sm-4">

                                    <?php /*echo $this->Form->input('topic', array('type' => 'text', 'placeholder' => 'Insert title', 'label' => 'Search by Title', 'class' => 'form-control')); */?>
                                </div>
                                <div class="col-sm-2">
                                    <?php /*echo $this->Form->label('&nbsp;'); */?>
                                    <?php /*echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); */?>
                                </div>
                                <div class="col-sm-2">
                                    <?php /*echo $this->Form->label('&nbsp;'); */?>
                                    <div class="submit">
                                        <a href="<?php /*echo $this->Html->url(array('controller'=>'app_admin','action'=>'search_ticket')) */?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>

                            </div>

                            --><?php /*echo $this->Form->end(); */?>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <h3 class="ticket_status">Ticket Type</h3>
                                        <ul class="dashboard_icon_li">
                                                <li>
                                                    <a href="<?php echo Router::url('/app_admin/search_ticket/question',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img dash_text">
                                                                <?php echo $questionTickets; ?>
                                                            </div>
                                                            Questions
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo Router::url('/app_admin/search_ticket/incident',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img dash_text">
                                                                <?php echo $incidentTickets; ?>
                                                            </div>
                                                            Incidents
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo Router::url('/app_admin/search_ticket/problem',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img dash_text">
                                                                <?php echo $problemTickets; ?>
                                                            </div>
                                                            Problems
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo Router::url('/app_admin/search_ticket/task',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img dash_text">
                                                                <?php echo $taskTickets; ?>
                                                            </div>
                                                            Tasks
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                          </div>

                                    <div class="col-sm-12">
                                            <h3 class="ticket_status">Ticket Status</h3>
                                            <ul class="dashboard_icon_li">

                                            <li>
                                                <a href="<?php echo Router::url('/app_admin/search_ticket/open',true); ?>">
                                                    <div class="content_div">
                                                        <div class="dash_img dash_text">
                                                           <?php echo $openTickets; ?>
                                                        </div>
                                                        Open
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo Router::url('/app_admin/search_ticket/inprogress',true); ?>">
                                                    <div class="content_div">
                                                        <div class="dash_img dash_text">
                                                            <?php echo $inProgressTickets; ?>
                                                        </div>
                                                        In Progress
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo Router::url('/app_admin/search_ticket/solved',true); ?>">
                                                    <div class="content_div">
                                                        <div class="dash_img dash_text">
                                                            <?php echo $solvedTickets; ?>
                                                        </div>
                                                        Solved
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo Router::url('/app_admin/search_ticket/cancelled',true); ?>">
                                                    <div class="content_div">
                                                        <div class="dash_img dash_text">
                                                           <?php echo $canceledTickets; ?>
                                                        </div>
                                                        Cancelled
                                                    </div>
                                                </a>
                                            </li>




                                        </ul>

                                    </div>
                                </div>
                                <div class="clear"></div>



                        </div>



                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>

