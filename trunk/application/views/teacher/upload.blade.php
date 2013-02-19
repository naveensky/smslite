<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('teacher.leftmenu')
        </div>
    </div>

    <div class="span9">
        <div class="box">
            <!-- Recent users email-verify -->
            <h3><i class="icon-th-list icon-large"></i>Import Teachers</h3>

            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                has been
                the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                galley
                of type and scrambled it to make a type specimen book.</p>

            <div class="row">
                <div class="span5 border-right">
                    <form class="form-horizontal">
                        <div class="control-group" ng-hide="files.length>0 || importStatus">
                            <a href="#" data-url="file/post_add" data-done="fileUploaded" data-mimes="csv"
                               id="teacher-file" class="file-uploader">
                                <i class="icon-upload"></i> Upload File</a>
                        </div>
                        <div ng-show="files.length >0">
                            <h5>File Uploaded</h5>
                            <p ng-repeat="file in files"><i class="icon-ok"></i> {{ file.filename }}</p>
                        </div>
                        <div ng-show="showError">
                            <p> {{ errorMessage }}</p>
                        </div>
                        <div class="control-group" ng-hide="showError || importStatus">
                            <button type="submit" class="btn" ng-click="importTeachers()">
                                <i class="icon-upload icon-large padding-right-5"></i>Import
                            </button>
                        </div>
                        <div class="control-group" ng-show="importStatus">
                            <p>Number of teacher Imported: {{numberOfTeachers}}</p>
                            <p ng-show="rowErrors!=0">Row Numbers having Errors: {{rowErrors}} </p>
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
<script type="text/javascript">
    initComponents();
</script>
