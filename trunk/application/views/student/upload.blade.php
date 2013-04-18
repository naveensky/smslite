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

            <p>To upload and add students, just click the "Upload File" link, choose your file and click "Upload".</p>

            <div class="row">
                <div class="span5 border-right">
                    <form class="form-horizontal">
                        <div class="control-group" ng-hide="files.length>0 || importStatus">
                            <a href="#" data-url="file/post_add" data-done="fileUploaded" data-mimes="csv"
                               id="student-file" class="file-uploader">
                                <i class="icon-upload"></i> Upload File</a>
                        </div>
                        <div ng-show="files.length >0">
                            <h5>File Uploaded</h5>

                            <p ng-repeat="file in files"><i class="icon-ok"></i> {{ file.filename }}</p>
                        </div>
                        <div class="alert alert-error margin-top-20" style="width:71%"
                             ng-show="showError">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            {{ errorMessage }}
                        </div>
                        <div class="control-group" ng-show="showSuccess">
                            <a href="#" data-url="file/post_add" data-done="fileUploaded" data-mimes="csv"
                               id="student-file" class="file-uploader">
                                <i class="icon-upload"></i> Upload Another File</a>
                        </div>
                        <div class="control-group" ng-hide="showError || importStatus">
                            <button type="submit" class="btn" ng-disabled="{{path==''}}" ng-click="importStudents()">
                                <i class="icon-upload icon-large padding-right-5"></i>Import
                            </button>
                        </div>
                        <div class="control-group" ng-show="importStatus">
                            <p>Number of students Imported: {{numberOfStudents}}</p>

                            <p ng-show="rowErrors!=0">Row Numbers having Errors: {{rowErrors}} </p>

                            <p>Want to upload another file <a href ng-click="resetModel()">Click Here</a></p>

                        </div>
                    </form>
                </div>
                <div class="span3">
                    <h5>Download Sample file</h5>

                    <p>Click the following button to download the sample file which you can use to upload your data.</p>

                    <form class="form-horizontal">
                        <div class="control-group">
                            <a class="btn" href="<%url::to('sampleFiles/student-sample.csv');%>" target="_blank"><i
                                class="icon-download icon-large padding-right-5"></i>Download
                            </a>
                        </div>
                    </form>
                    <p>Compulsory fields: <b> <br>Admission No <br>Full Name <br>Mobile1</b></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    initComponents();
</script>
