<section class="container" style="margin-top:20px;">
    <div class="box">
        <!--<div class="tabbable">-->

        <!-- Tabs
       ================================================== -->
        <ul class="nav nav-tabs" id="register-tabs">
            <li><a href="#">Sign up</a></li>
            <li><a href="#">School Information</a></li>
            <li><a href="#">Verify Mobile</a></li>
            <li class="active"><a href="#">Verify Email</a></li>
            <li><a href="#">Welcome</a></li>
        </ul>
        <!-- / Tabs -->

        <!-- Tabs content
       ================================================== -->
        <div class="tab-content">

            <!-- Recent comments tab content -->
            <div class="tab-pane fade in active" id="sign-up">

                <form class="form-horizontal">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book.</p>

                    <div class="controls margin-top-20 margin-left-0">
                        <button type="button" class="btn">Send Email Again</button>

                    </div>
                </form>

                <hr>

                <form class="form-horizontal">
                    <h3><i class="icon-envelope-alt icon-large padding-right-5"></i>Need to update Email</h3>

                    <div class="control-group">
                        <label class="control-label" for="inputUpdateEmail">Update Email</label>

                        <div class="controls">
                            <input type="text" id="inputUpdateEmail" placeholder="Update Email">
                            <button type="submit" class="btn">Update</button>
                        </div>
                        <div class="controls margin-top-20">
                            <button type="button" class="btn btn-success">Next</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <!-- / Tabs content -->
    </div>
    <!--</div>-->
</section>

<script>
    $(function () {
        $('#register-tabs a').click(function (e) {
            e.preventDefault();
        });
    })
</script>