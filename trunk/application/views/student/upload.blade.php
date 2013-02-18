<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('student.leftmenu')
        </div>
    </div>

    <div class="span9">
        <div class="box">
            <!-- Recent users email-verify -->
            <h3><i class="icon-th-list icon-large"></i>Import Students</h3>

            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                has been
                the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                galley
                of type and scrambled it to make a type specimen book.</p>

            <div class="row">
                <div class="span5 border-right">
                    <form class="form-horizontal">
                        <div class="control-group">
                            <a href="#" data-url="home/post_upload" data-done="fileUploaded" data-mimes="csv"
                               id="student-file" class="file-uploader">
                                <i class="icon-upload"></i> Upload File</a>

                        </div>
                        <div ng-repeat="file in files">
                            <p>{{ file.path }}</p>
                        </div>
                        <div class="control-group">
                            <button type="submit" class="btn">
                                <i class="icon-upload icon-large padding-right-5"></i>Import
                            </button>
                        </div>
                    </form>
                </div>
                <div class="span3">
                    <h5>Download Sample file</h5>

                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                        been
                        the industry's</p>

                    <form class="form-horizontal">
                        <div class="control-group">
                            <button type="submit" class="btn"><i
                                    class="icon-download icon-large padding-right-5"></i>Download
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    initComponents();
</script>
